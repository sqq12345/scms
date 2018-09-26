<?php
/**
 * Created by PhpStorm.
 * 分单商品
 * User: huiyong.yu
 * Date: 2018/6/27
 * Time: 10:54
 */
class SplitOrderItemsModel{
    private $filepath = 'split_order_items';
    private $fields = 'id, split_order_sn, product_id, sku_id,product_type,product_name, model, attr, product_icon, product_price, num,refund_num, create_time, update_time, status';

    public function BcModelList($splitOrderSn) {
        $dataval = array();
        $where = " AND split_order_sn = '{$splitOrderSn}' AND status = 1";
        $orderby = "id desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        $productModel = get_load_model('reProduct');
        $brandModel = get_load_model('brand');
        $skuModel = get_load_model('reProductSku');
        foreach ($list as $key => $val) {
            $dataval[$key]['productId'] = intval($val['product_id']);
            $dataval[$key]['skuId'] = intval($val['sku_id']);
            $dataval[$key]['productName'] = $val['product_name'];
            $dataval[$key]['brandName'] = "";
            $product = $productModel->BcModelInfo($val['product_id']);
            if(!empty($product)){
                $brand = $brandModel->BcModelInfo($product['brandId']);
                if(!empty($brand)){
                    $dataval[$key]['brandName'] = $brand['name'];
                }
            }
            $dataval[$key]['model'] = $val['model'];
            $dataval[$key]['attr'] = $val['attr'];
            $dataval[$key]['productIcon'] = $val['product_icon'];
            $dataval[$key]['ref'] = '';
            $sku = $skuModel->BcModelInfoById($val['sku_id']);
            if(!empty($sku)){
                $dataval[$key]['ref'] = $sku['ref'];
            }
            $dataval[$key]['productPrice'] = doubleval($val['product_price']);
            $dataval[$key]['num'] = intval($val['num']);
        }
        return $dataval;
    }

    public function BcModelUpdateRefundNum($orderSn,$userId,$skuId,$num){
        $where = "AND order_sn = '{$orderSn}' AND user_id = {$userId} AND sku_id = {$skuId} AND split_order_items.status = 1 AND split_order.split_order_sn = split_order_items.split_order_sn";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, array('split_order','split_order_items'), 'split_order_items.id,refund_num', $where);
        if (empty($row)) {
            return false;
        }
        if($num + $row['refund_num'] > $num){
            return false;
        }
        $refundNum = $num + $row['refund_num'];
        $set = array();
        $set[] = "refund_num = {$refundNum}";
        $where = " AND id = {$row['id']}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

}