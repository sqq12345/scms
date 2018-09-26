<?php
/**
 * Created by PhpStorm.
 * 会员充值记录
 * User: huiyong.yu
 * Date: 2018/5/21
 * Time: 14:07
 */
class RechargeRecordModel{

    private $filepath = 'recharge_record';
    private $fields = 'id, record_sn, user_id,name,days,price, record_status, create_time, update_time, status';

    public function ModelAdd($userId,$name,$days,$price){
        $recordSn = 'RR'.date('YmdHis') . rand(10000, 99999);
        $setarray = array();
        $setarray[] = " record_sn = '{$recordSn}'";
        $setarray[] = " user_id = {$userId}";
        $setarray[] = " name = '{$name}'";
        $setarray[] = " days = {$days}";
        $setarray[] = " price = {$price}";
        $setarray[] = " record_status = 'waitforpay'";
        $setarray[] = " status = 1";
        $setarray[] = " create_time = now()";
        $setarray[] = " update_time = now()";

        $insertId = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $setarray);
        if ($insertId > 0) {
            return $recordSn;
        } else {
            return false;
        }
    }
}