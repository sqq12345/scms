<?php
/**
 * Created by PhpStorm.
 * 用户月消费额度
 * User: huiyong.yu
 * Date: 2018/6/6
 * Time: 10:14
 */
class LimitAmountModel{

    private $filepath = 'limit_amount';
    private $fields = 'id, user_id, amount';

    public function ModelInfoByUserId($userId){
        $dataval = array();
        $where = " AND user_id = {$userId}";
        $orderby = " id DESC";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $orderby);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }

    public function ModelInsert($userId){
        $setarray = array();
        $setarray[] = "user_id = {$userId}";
        $setarray[] = "amount = 0.00";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray);
        return $ret;
    }

    public function ModelUpdate($userId, $amount,$type = 1){
        $where = " AND user_id = {$userId}";
        $setarray = array();
        if($type == 1){
            $setarray[] = "amount = amount + {$amount}";
        }else{
            $setarray[] = "amount = amount - {$amount}";
        }
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        return $ret;
    }
}