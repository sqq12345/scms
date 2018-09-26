<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/17
 * Time: 17:37
 */
class BespeakOrderItemsModel{
    private $filepath = 'bespeak_order_items';
    private $fields = 'id, order_sn, product_id, sku_id, product_name, model, attr, product_icon, product_price,cost_price, num, create_time, update_time, status';

    public function ModelAdd($data) {
        $setarray = array();
        $setarray[] = " order_sn = '{$data['orderSn']}'";
        $setarray[] = " product_id = {$data['productId']}";
        $setarray[] = " sku_id = {$data['skuId']}";
        $setarray[] = " product_name = '{$data['productName']}'";
        $setarray[] = " model = '{$data['model']}'";
        $setarray[] = " attr = '{$data['attr']}'";
        $setarray[] = " product_icon = '{$data['productIcon']}'";
        $setarray[] = " product_price = {$data['productPrice']}";
        $setarray[] = " cost_price = {$data['costPrice']}";
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

    public function ModelList($orderSn) {
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}' AND status = 1";
        $orderby = "id desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        $productModel = get_load_model('reBespeakProduct');
        $brandModel = get_load_model('brand');
        foreach ($list as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
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
            $dataval[$key]['productPrice'] = doubleval($val['product_price']);
            $dataval[$key]['costPrice'] = doubleval($val['cost_price']);
            $dataval[$key]['num'] = intval($val['num']);
        }
        return $dataval;
    }

    public function BcModelList($orderSn) {
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}' AND status = 1";
        $orderby = "id desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        $productModel = get_load_model('reBespeakProduct');
        $skuModel = get_load_model('reBespeakProductSku');
        $brandModel = get_load_model('brand');
        foreach ($list as $key => $val) {
            $dataval[$key]['productId'] = intval($val['product_id']);
            $dataval[$key]['skuId'] = intval($val['sku_id']);
            $dataval[$key]['productName'] = $val['product_name'];
            $dataval[$key]['brandName'] = "";
            $dataval[$key]['ref'] = "";
            $product = $productModel->BcModelInfo($val['product_id']);
            if(!empty($product)){
                $brand = $brandModel->BcModelInfo($product['brandId']);
                if(!empty($brand)){
                    $dataval[$key]['brandName'] = $brand['name'];
                }
            }
            $sku = $skuModel->BcModelInfoById($val['sku_id']);
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

    public function BcModelInfoByOrderSnAndPidAndSkuId($orderSn,$pid,$skuId){
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}' AND product_id = {$pid} AND sku_id = {$skuId} AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        return $row;
    }

    public function ModelInfoByOrderSn($orderSn){
        $dataval = array();
        $where = "AND order_sn = '{$orderSn}' AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }

    public function ModelListByOrderSn($orderSn) {
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}' AND status = 1";
        $orderby = "id desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['pid'] = intval($val['product_id']);
            $dataval[$key]['skuId'] = intval($val['sku_id']);
            $dataval[$key]['productName'] = $val['product_name'];
            $dataval[$key]['model'] = $val['model'];
            $dataval[$key]['attr'] = $val['attr'];
            $dataval[$key]['productIcon'] = $val['product_icon'];
            $dataval[$key]['productPrice'] = doubleval($val['product_price']);
            $dataval[$key]['num'] = intval($val['num']);
        }
        return $dataval;
    }
}