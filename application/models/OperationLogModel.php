<?php
/**
 * Created by PhpStorm.
 * 操作日志
 * User: huiyong.yu
 * Date: 2018/5/10
 * Time: 11:00
 */
class OperationLogModel{
    private $filepath = 'operation_log';
    private $fields = 'id,purchase_id,type,relate,operation_id,create_time,update_time,status';

    public function ModelAdd($purchaseId,$type,$relate,$operationId) {
        $set = array();
        $set[] = " purchase_id = {$purchaseId}";
        $set[] = " type = '{$type}'";
        $set[] = " relate = {$relate}";
        $set[] = " operation_id = {$operationId}";
        $set[] = " status = 1";
        $set[] = " create_time = now()";
        $set[] = " update_time = now()";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $set);
        return $ret;
    }
}