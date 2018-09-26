<?php
/**
 * Created by PhpStorm.
 * 身份验证
 * User: huiyong.yu
 * Date: 2018/6/7
 * Time: 9:22
 */
class LimitValidateModel{

    private $filepath = 'limit_validate';
    private $fields = 'id, user_id, name, ip, times';

    public function ModelInfoByUserIdAndIpAndName($userId,$name,$ip){
        $dataval = array();
        $where = " AND user_id = {$userId} AND name = '{$name}' AND ip = '{$ip}'";
        $orderby = " id DESC";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $orderby);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }

    public function ModelInsert($userId,$name,$ip){
        $setarray = array();
        $setarray[] = "user_id = {$userId}";
        $setarray[] = "name = '{$name}'";
        $setarray[] = "ip = '{$ip}'";
        $setarray[] = "times = 1";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray);
        return $ret;
    }

    public function ModelAddTimes($id){
        $setarray = array();
        $setarray[] = "times = times + 1";
        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        return $ret;
    }
}