<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/13
 * Time: 14:01
 */
class OrderDeliveryModel{
    private $filepath = 'order_delivery';
    private $fields = 'id,order_sn,delivery_sn,company,code,create_by,update_by,create_time,update_time,status';

    public function ModelInfo($orderSn) {
        $dataval = array();
        $where = " and order_sn = '{$orderSn}'";
        $order = "id desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
        if (empty($row)) {
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['orderSn'] = $row['order_sn'];
        $dataval['deliverySn'] = $row['delivery_sn'];
        $dataval['company'] = $row['company'];
        $dataval['code'] = $row['code'];
        return $dataval;
    }

    public function ModelAdd($orderSn,$deliverySn,$company,$code,$purchaseId) {
        $setarray = array();
        $setarray[] = " order_sn = '{$orderSn}'";
        $setarray[] = " delivery_sn = '{$deliverySn}'";
        $setarray[] = " company = '{$company}'";
        $setarray[] = " code = '{$code}'";
        $setarray[] = " create_by = {$purchaseId}";
        $setarray[] = " status = 1";
        $setarray[] = " create_time = now()";
        $setarray[] = " update_time = now()";
        $insertId = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $setarray);
        if($insertId>0){
            return $insertId;
        } else {
            return false;
        }
    }

    public function ModelDelete($id) {
        $where = " AND id = {$id}";
        $setarray[] = " status= 0 ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        return $ret!==false;
    }

    public function ModelUpdate($orderSn,$deliverySn,$company,$code,$purchaseId){
        $where = " AND order_sn = '{$orderSn}'";
        $setarray = array();
        $setarray[] = " delivery_sn = '{$deliverySn}'";
        $setarray[] = " company = '{$company}'";
        $setarray[] = " code = '{$code}'";
        $setarray[] = " update_by = {$purchaseId}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        return $ret!==false;
    }
}