<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/12
 * Time: 10:08
 */
class BuyerModel{
    private $filepath = 'buyer';
    private $fields = 'id, username, password, nickname, image, create_time, update_time, status';

    public function ModelUpdate($set, $id) {
        $where = "AND id = {$id} ";

        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function ModelLogin($username) {
        $dataval = array();
        $where = "AND username = '{$username}' ";
        $buyer = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($buyer)){
            return $dataval;
        }
        return $buyer;
    }

    public function BcModelAdd($username,$password,$nickname,$image){
        $ret = 0;
        $where = "AND username = '{$username}'";
        $user = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        if($user > 0){
            return $ret;
        }else{
            $set = array();
            $set[] = "username = '{$username}'";
            $set[] = "password = '{$password}'";
            $set[] = "nickname = '{$nickname}'";
            $set[] = "image = '{$image}'";
            $set[] = " create_time = now()";
            $set[] = " status = 1";
            $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH,2,$this->filepath,$set);
            return $ret;
        }
    }

    public function BcModelDelete($ids){
        $ids = json_encode($ids);
        $ids = str_replace("[","(",$ids);
        $ids = str_replace("]",")",$ids);
        $setarray = array();
        $setarray[] =  "status = 0";
        $where = " AND id in {$ids}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    public function BcModelInfo($id) {
        $dataval = array();
        $where = " AND id = {$id} ";
        $user = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($user)){
            return $dataval;
        }
        $dataval['id'] = intval($user['id']);
        $dataval['username'] = $user['username'];
        $dataval['password'] = $user['password'];
        $dataval['nickname'] = $user['nickname'];
        $dataval['image'] = $user['image'];
        $dataval['createTime'] = $user['create_time'];
        $dataval['status'] = intval($user['status']);
        return $dataval;
    }

    public function BcModelList($name,$phone,$status,$offset,$max){
        $dataval = array();
        $where = " AND status = {$status}";
        if(!empty($name)){
            $where.= " AND nickname LIKE '%{$name}%'";
        }
        if(!empty($phone)){
            $where.= " AND username LIKE '%{$phone}%'";
        }
        $orderby = " id DESC";
        $limit = " LIMIT {$offset}, {$max}";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['username'] = $val['username'];
            $dataval[$key]['nickname'] = $val['nickname'];
            $dataval[$key]['image'] = $val['image'];
            $dataval[$key]['status'] = intval($val['status']);
        }
        $data = array();
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        $data['total'] = intval($total);
        $data['list'] = $dataval;
        return $data;
    }

    public function BcModelGetList(){
        $dataval = array();
        $where = " AND status = 1";
        $orderby = " id DESC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['nickname'] = $val['nickname'];
        }
        return $dataval;
    }

    public function BcModelAllList(){

    }
}