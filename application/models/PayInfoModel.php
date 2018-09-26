<?php
/**
 * Created by PhpStorm.
 * 支付记录
 * User: huiyong.yu
 * Date: 2018/5/8
 * Time: 16:01
 */
class PayInfoModel{

    private $filepath = 'pay_info';
    private $fields = 'id, user_id,order_type,order_sn,pay_sn,pay_type,fee,status,create_time,pay_time';

    public function BcModelInfoByOrderSn($orderSn){
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}' ";
        $orderby = " pay_time desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where,$orderby);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }

    public function ModelAdd($userId,$orderType,$orderSn,$payType,$fee){
        $setarray = array();
        $setarray[] = "user_id = {$userId}";
        $setarray[] = "order_type = '{$orderType}'";
        $setarray[] = "order_sn = '{$orderSn}'";
        $setarray[] = "pay_type = '{$payType}'";
        $setarray[] = "fee = {$fee}";
        $setarray[] = "status = 'paying'";
        $setarray[] = "create_time = now()";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray);
        return $ret;
    }

    public function ModelInfoByOrderSnAndFeeAndPayTypeAndUserId($orderSn,$fee,$payType,$userId){
        $dataval = array();
        $where = "AND order_sn = '{$orderSn}' AND pay_type = '{$payType}' AND fee = {$fee} AND user_id = {$userId}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }

    public function ModelInfoByUserIdAndOrderSnAndPayType($userId,$orderSn,$payType){
        $dataval = array();
        $where = "AND order_sn = '{$orderSn}' AND pay_type = '{$payType}' AND user_id = {$userId}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }

    public function ModelInfo($userId,$orderSn,$payType){
        $dataval = array();
        $where = "AND order_sn = '{$orderSn}' AND pay_type = '{$payType}' AND user_id = {$userId} AND status = 'payed'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }

    public function BcModelInfoByOrderSnAndPayed($orderSn){
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}' AND status = 'payed'";
        $orderby = " pay_time desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where,$orderby);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }
}