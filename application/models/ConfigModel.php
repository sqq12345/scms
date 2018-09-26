<?php
/**
 * 配置
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/3
 * Time: 11:27
 */
class ConfigModel{

    private $filepath = 're_config';
    private $fields = 'id, clazz, type, value, status';

    public function BcModelAdd($clazz,$type,$value){
        $setarray = array();
        $setarray[] = "clazz = '{$clazz}'";
        $setarray[] = "type = {$type}";
        $setarray[] = "value = {$value}";
        $setarray[] = "status = 1";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $setarray);
        return $ret;
    }

    public function BcModelUpdate($clazz,$type,$value){
        $setarray = array();
        if (!empty($type)) $setarray[] = "type = {$type}";
        if (!empty($value)) $setarray[] = "value = {$value}";
        $where = " AND clazz = '{$clazz}' AND status = 1";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        return $ret;
    }

    public function BcModelInfo($clazz) {
        $dataval = array();
        $where = " AND clazz = '{$clazz}' AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['clazz'] = $row['clazz'];
        $dataval['cType'] = intval($row['type']);
        $dataval['value'] = intval($row['value']);
        $dataval['status'] = intval($row['status']);
        return $dataval;
    }

    //更新汇率
    public function updateExchangeRate($rate){
        $setarray = array();
        $setarray[] = "rate = {$exchange}";
        $setarray[] = "updatetime = now()";
        $where = " AND id = 1";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        return $ret;
    }
}