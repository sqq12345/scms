<?php
/**
 * Created by PhpStorm.
 * 实名认证
 * User: huiyong.yu
 * Date: 2018/6/5
 * Time: 9:57
 */
class RealNameModel{

    private $filepath = 'real_name';
    private $fields = 'id, user_id, name, id_card, positive_img, opposite_img, create_time, update_time, status';

    public function ModelAdd($userId,$name,$idCard,$positiveImg,$oppositeImg){
        $set = array();
        $set[] = "user_id = {$userId}";
        $set[] = "name = '{$name}'";
        $set[] = "id_card = '{$idCard}'";
        $set[] = "positive_img = '{$positiveImg}'";
        $set[] = "opposite_img = '{$oppositeImg}'";
        $set[] = "create_time = now()";
        $set[] = "status = 1";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $set);
        return $ret;
    }

    public function ModelInfoByUserId($userId){
        $dataval = array();
        $where = " AND user_id = {$userId} AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }

    public function ModelInfoByUserIdAndNameAndIdCard($userId,$name,$idCard){
        $dataval = array();
        $where = " AND user_id = {$userId} AND status = 1 AND name = '{$name}' AND id_card = '{$idCard}'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }
}