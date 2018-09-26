<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/12
 * Time: 9:13
 */
class PurchaseModel{
    private $filepath = 'purchase';
    private $fields = 'id, username, password, nickname, phone, image, type, create_time, update_time, status';

    public function ModelUpdate($set, $id) {
        $where = "AND id = {$id} ";

        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function ModelLogin($username) {
        $dataval = array();
        $where = "AND username = '{$username}' AND status = 1";
        $buyer = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($buyer)){
            return $dataval;
        }
        return $buyer;
    }

    public function ModelBuyerLogin($username) {
        $dataval = array();
        $where = "AND username = '{$username}' AND status = 1 AND type = 2";
        $buyer = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($buyer)){
            return $dataval;
        }
        return $buyer;
    }

    public function BcModelAdd($username,$password,$nickname,$phone,$image,$type,$status){
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
            $set[] = "phone = '{$phone}'";
            $set[] = "image = '{$image}'";
            $set[] = " type = {$type}";
            $set[] = " create_time = now()";
            $set[] = " status = {$status}";
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
        $dataval['nickname'] = $user['nickname'];
        $dataval['phone'] = $user['phone'];
        $dataval['image'] = $user['image'];
        $dataval['type'] = intval($user['type']);
        $dataval['status'] = intval($user['status']);
        return $dataval;
    }

    public function BcModelList($name,$phone,$purchaseType,$status,$offset,$max)
    {
        $dataval = array();
        $where = "";
        if($status >= 0){
            $where.= " AND status = {$status}";
        }
        if(!empty($name)){
            $where.= " AND nickname LIKE '%{$name}%'";
        }
        if(!empty($phone)){
            $where.= " AND phone LIKE '%{$phone}%'";
        }
        if($purchaseType > 0){
            $where.= " AND type = {$purchaseType}";
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
            $dataval[$key]['phone'] = $val['phone'];
            $dataval[$key]['image'] = $val['image'];
            $dataval[$key]['type'] = intval($val['type']);
            $dataval[$key]['status'] = intval($val['status']);
        }
        $data = array();
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        $data['total'] = intval($total);
        $data['list'] = $dataval;
        return $data;
    }

    public function BcModelUpdateStatus($id,$status){
        $setarray[] =  "status = {$status}";
        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    public function BcModelGetAllBuyers(){
        $dataval = array();
        $where = " AND status = 1 AND type = 2";
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

    public function BcModelInfoByIdAndType($id,$type){
        $dataval = array();
        $where = " AND id = {$id} AND type = {$type} AND status = 1";
        $user = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($user)){
            return $dataval;
        }
        $dataval['id'] = intval($user['id']);
        $dataval['username'] = $user['username'];
        $dataval['nickname'] = $user['nickname'];
        $dataval['phone'] = $user['phone'];
        $dataval['image'] = $user['image'];
        $dataval['type'] = intval($user['type']);
        $dataval['status'] = intval($user['status']);
        return $dataval;
    }

    public function BcModelGetBuyerIds(){
        $dataval = array();
        $where = " AND type = 2 AND status = 1";
        $orderby = " id DESC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key=>$val){
            array_push($dataval,intval($val['id']));
        }
        return $dataval;
    }

}