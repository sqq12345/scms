<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/7
 * Time: 13:34
 */
class RefundOrderItemsModel{

    private $filepath = 'refund_order_items';
    private $fields = 'id, refund_sn, order_sn, product_id, sku_id,product_name, model, attr, product_icon, product_price, num, create_time, update_time, status';

    public function ModelAdd($data) {
        $setarray = array();
        $setarray[] = " refund_sn = '{$data['refundSn']}'";
        $setarray[] = " order_sn = '{$data['orderSn']}'";
        $setarray[] = " product_id = {$data['productId']}";
        $setarray[] = " sku_id = {$data['skuId']}";
        $setarray[] = " product_name = '{$data['productName']}'";
        $setarray[] = " model = '{$data['model']}'";
        $setarray[] = " attr = '{$data['attr']}'";
        $setarray[] = " product_icon = '{$data['productIcon']}'";
        $setarray[] = " product_price = {$data['productPrice']}";
        $setarray[] = " num = {$data['num']}";
        $setarray[] = " status = 1";
        $setarray[] = " create_time = now()";
        $setarray[] = " update_time = now()";
        $insertId = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $setarray);
        if ($insertId > 0) {
            return $insertId;
        } else {
            return false;
        }
    }

    public function BcModelGetSumByOrderSnAndPidAndSkuId($orderSn,$pid,$skuId){
        $sum = 0;
        $where = " AND refund_order_items.order_sn = '{$orderSn}' AND refund_order.refund_sn = refund_order_items.refund_sn AND product_id = {$pid} AND sku_id = {$skuId} AND refund_order.status = 1 AND refund_order_items.status = 1 AND order_status in('refunding','refundSuccess')";
        $sum = $GLOBALS['DB']->getSelectSum(ECHO_AQL_SWITCH,'num',array('refund_order','refund_order_items'),$where);
        return intval($sum);
    }

    public function BcModelList($refundSn){
        $dataval = array();
        $where = " AND refund_sn = '{$refundSn}' AND status = 1";
        $orderby = "id desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        $productModel = get_load_model('reProduct');
        $beskpeakModel = get_load_model('reBespeakProduct');
        $skuModel = get_load_model('reProductSku');
        $bespeakSkuModel = get_load_model('reBespeakProductSku');
        $brandModel = get_load_model('brand');
        foreach ($list as $key => $val) {
            $dataval[$key]['productId'] = intval($val['product_id']);
            $dataval[$key]['skuId'] = intval($val['sku_id']);
            $dataval[$key]['productName'] = $val['product_name'];
            $dataval[$key]['brandName'] = "";
            $dataval[$key]['ref'] = '';
            $product = array();
            $sku = array();
            if(strstr($val['order_sn'],'CO')){
                $product = $productModel->BcModelInfo($val['product_id']);
                $sku = $skuModel->BcModelInfoById($val['sku_id']);
            }elseif (strstr($val['order_sn'],'YY')){
                $product = $beskpeakModel->BcModelInfo($val['product_id']);
                $sku = $bespeakSkuModel->ModelInfoById($val['sku_id']);
            }
            if(!empty($product)){
                $brand = $brandModel->BcModelInfo($product['brandId']);
                if(!empty($brand)){
                    $dataval[$key]['brandName'] = $brand['name'];
                }
            }
            if(!empty($sku)){
                $dataval[$key]['ref'] = $sku['ref'];
            }
            $dataval[$key]['model'] = $val['model'];
            $dataval[$key]['attr'] = $val['attr'];
            $dataval[$key]['productPrice'] = doubleval($val['product_price']);
            $dataval[$key]['num'] = intval($val['num']);
        }
        return $dataval;
    }

    public function BcModelGetRefundPrice($orderSn){
        $sum = 0;
        $where = " AND refund_order.order_sn = '{$orderSn}' AND order_status = 'refundSuccess' AND refund_order.status = 1 AND refund_order_items.status = 1 AND refund_order.refund_sn = refund_order_items.refund_sn";
        $orderby = " refund_order.id desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, array('refund_order','refund_order_items'), 'refund_order_items.product_price,refund_order_items.num', $where, $orderby);
        if(empty($list)){
            return doubleval($sum);
        }
        foreach ($list as $k => $v) {
            $sum = bcadd(bcmul($v['product_price'],$v['num'],2),$sum,2);
        }
        return doubleval($sum);
    }

    public function ModelList($refundSn){
        $dataval = array();
        $where = " AND refund_sn = '{$refundSn}' AND status = 1";
        $orderby = "id desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        $productModel = get_load_model('reProduct');
        $beskpeakModel = get_load_model('reBespeakProduct');
        $brandModel = get_load_model('brand');
        foreach ($list as $key => $val) {
            $dataval[$key]['productId'] = intval($val['product_id']);
            $dataval[$key]['skuId'] = intval($val['sku_id']);
            $dataval[$key]['productName'] = $val['product_name'];
            $dataval[$key]['productIcon'] = $val['product_icon'];
            $dataval[$key]['brandName'] = "";
            $product = array();
            if(strstr($val['order_sn'],'CO')){
                $product = $productModel->BcModelInfo($val['product_id']);
            }elseif (strstr($val['order_sn'],'YY')){
                $product = $beskpeakModel->BcModelInfo($val['product_id']);
            }
            if(!empty($product)){
                $brand = $brandModel->BcModelInfo($product['brandId']);
                if(!empty($brand)){
                    $dataval[$key]['brandName'] = $brand['name'];
                }
            }
            $dataval[$key]['model'] = $val['model'];
            $dataval[$key]['attr'] = $val['attr'];
            $dataval[$key]['productPrice'] = doubleval($val['product_price']);
            $dataval[$key]['num'] = intval($val['num']);
        }
        return $dataval;
    }

    public function BcModelGetSkuArray($orderSn){
        $dataval = array();
        $where = " AND refund_order_items.order_sn = '{$orderSn}' and refund_order.order_status in ('refunding','refundSuccess') and refund_order.refund_sn = refund_order_items.refund_sn";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, array('refund_order','refund_order_items'), 'DISTINCT(sku_id), SUM(num)', $where,'refund_order.id desc');
        if(empty($list)){
            return $dataval;
        }
        foreach ($list as $key => $val) {
            $dataval[$key]['skuId'] = $val['sku_id'];
            $dataval[$key]['num'] = $val['SUM(num)'];
        }
        return $dataval;
    }
}