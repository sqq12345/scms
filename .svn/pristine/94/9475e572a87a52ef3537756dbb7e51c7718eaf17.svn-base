<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 10:52
 */
class TokenModel{
    private $filepath = 'token';
    private $fields = 'id, user_id,type, token';

    public function ModelInfo($userid) {
        $dataval = array();
        $where = "AND user_id = {$userid} ";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['userId'] = intval($row['user_id']);
        $dataval['type'] = intval($row['type']);
        $dataval['token'] = $row['token'];
        return $dataval;
    }

    public function ModelAdd($userid, $token, $type) {
        $where = "AND user_id = {$userid} AND type = {$type}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(!empty($row)){
            $set = array();
            $set[] = " token = '{$token}'";
            $set[] = " type = {$type}";
            $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
            return $ret;
        }else{
            $set = array();
            $set[] = "user_id = {$userid}";
            $set[] = "token = '{$token}'";
            $set[] = "type = {$type}";
            $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH,2,$this->filepath,$set);
            return $ret;
        }
    }

    public function ModelInfoByToken($token) {
        $dataval = array();
        $where = "AND token = '{$token}' ";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['userId'] = intval($row['user_id']);
        $dataval['type'] = intval($row['type']);
        $dataval['token'] = $row['token'];
        return $dataval;
    }

}