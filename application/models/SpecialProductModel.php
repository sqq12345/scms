<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/12
 * Time: 15:37
 */
class SpecialProductModel{
    private $filepath = 'special_product';
    private $fields = 'id, pid, sku_id, attr1, attr2, price, start_time, end_time, create_time,update_time, status';

    public function BcModelAdd($pid,$skuId,$attr1,$attr2,$price,$startTime,$endTime){
        $set = array();
        $set[] = "pid = {$pid}";
        $set[] = "sku_id = {$skuId}";
        $set[] = "attr1 = '{$attr1}'";
        $set[] = "attr2 = '{$attr2}'";
        $set[] = "price = {$price}";
        $set[] = "start_time = '{$startTime}'";
        $set[] = "end_time = '{$endTime}'";
        $set[] = "create_time = now()";
        $set[] = "status = 1";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH,2,$this->filepath,$set);
        return $ret;
    }

    public function BcModelUpdate($set, $id) {
        $where = "AND id = {$id} ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelListByPid($pid){
        $dataval = array();
        $where = " AND status = 1 AND pid = {$pid}";
        $orderby = "create_time desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['pid'] = intval($val['pid']);
            $dataval[$key]['skuId'] = intval($val['sku_id']);
            $dataval[$key]['attr1'] = $val['attr1'];
            $dataval[$key]['attr2'] = $val['attr2'];
            $dataval[$key]['price'] = doubleval($val['price']);
            $dataval[$key]['startTime'] = $val['start_time'];
            $dataval[$key]['endTime'] = $val['end_time'];
            $dataval[$key]['status'] = intval($val['status']);
        }
        return $dataval;
    }

    public function ModelList($offset,$max){
        $dataval = array();
        $time = date('Y-m-d H:i:s');
        $where = " AND status = 1 AND start_time <= '{$time}' AND end_time >= '$time' ";
        $limit = " LIMIT {$offset}, {$max}";
        $orderby = "create_time desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby,$limit);
        if (empty($list)) {
            return $dataval;
        }
        $productModel = get_load_model('product');
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['pid'] = intval($val['pid']);
            $product = $productModel->ModelMiniInfo($val['pid']);
            $dataval[$key]['name'] = $product['name'];
            $dataval[$key]['goodIcon'] = $product['goodIcon'];
            if(intval($val['sku_id']) == 0){
                $dataval[$key]['sku'] = $product['commodity_model'];
            }else{
                $dataval[$key]['sku'] = $val['attr1'].$val['attr2'];
            }
            $dataval[$key]['skuId'] = intval($val['sku_id']);
            $dataval[$key]['price'] = doubleval($val['price']);
            $dataval[$key]['dutyFreePrice'] = doubleval($val['dutyFreePrice']);
        }
        return $dataval;
    }

    public function ModelInfoByIdAndTime($id){
        $dataval = array();
        $time = date('Y-m-d H:i:s');
        $where = " AND id = {$id} AND status = 1 AND start_time <= '{$time}' AND end_time >= '$time'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['pid'] = intval($row['pid']);
        $dataval['skuId'] = intval($row['sku_id']);
        $dataval['price'] = doubleval($row['price']);
        return $dataval;
    }
}