<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 10:03
 */
class CustomerModel{
    private $filepath = 'customer';
    private $fields = 'id, username, password,nickname,img,email,id_card,positive_img,opposite_img,vip_time,openid,xcx_openid,create_time, update_time, status';


    public function ModelAdd($username, $password,$idCard,$positiveImg,$oppositeImg,$vipDays) {
        $ret = 0;
        $where = "AND username = '{$username}'";
        $user = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        if($user > 0){
            return $ret;
        }else{
            //15112526359
            $nickname = substr($username,0,4).'****'.substr($username,8);
            date_default_timezone_set('PRC');
            $time = date('Y-m-d H:i:s');
            $vip_time = date('Y-m-d H:i:s',strtotime("$time+".$vipDays." days"));
            $set = array();
            $set[] = "username = '{$username}'";
            $set[] = "password = '{$password}'";
            $set[] = "nickname = '{$nickname}'";
            $set[] = "img = 'http://upload.hljr.com.cn/ava@3x.png'";
            $set[] = "id_card = '{$idCard}'";
            $set[] = "positive_img = '{$positiveImg}'";
            $set[] = "opposite_img = '{$oppositeImg}'";
            $set[] = " create_time = now()";
            $set[] = " vip_time = '{$vip_time}'";
            $set[] = " status = 1";
            $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH,2,$this->filepath,$set);
            return $ret;
        }
    }

    public function ModelLogin($username) {
        $dataval = array();
        $where = "AND username = '{$username}' ";
        $user = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($user)){
            return $dataval;
        }
        return $user;
    }

    public function ModelUpdate($set, $id) {
        $where = "AND id = {$id} ";

        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function ModelInfo($id) {
        $dataval = array();
        $where = " AND id = {$id} ";
        $user = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($user)){
            return $dataval;
        }
        $dataval['id'] = intval($user['id']);
        $dataval['username'] = $user['username'];
        $dataval['nickname'] = $user['nickname'];
        $dataval['img'] = $user['img'];
        $dataval['email'] = $user['email'];
        date_default_timezone_set('PRC');
        $time = date('Y-m-d H:i:s');
        if(strtotime($time) > strtotime($user['vip_time'])){
            $dataval['days'] = 0;
        }else{
            $dataval['days'] = round((strtotime($user['vip_time']) - strtotime($time))/3600/24);
        }
        return $dataval;
    }

    public function GetInfoByPhone($phone){
        $id = 0;
        $where = "AND username = '{$phone}' ";
        $id = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 3, $this->filepath, 'id', $where);
        return $id;
    }

    public function ModelUpdatePwd($userid,$password,$oldpwd) {
        $set[] = "password = '{$password}'";
        $where = "AND user_id = {$userid} AND password = '{$oldpwd}'";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function ModelUpdatePassWord($userid,$password){
        $set[] = "password = '{$password}'";
        $where = "AND id = {$userid}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
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
        $dataval['info']['id'] = intval($user['id']);
        $dataval['info']['username'] = $user['username'];
        $dataval['info']['nickname'] = $user['nickname'];
        $dataval['info']['img'] = $user['img'];
        $dataval['info']['email'] = $user['email'];
        $dataval['info']['idCard'] = $user['id_card'];
        $dataval['info']['positiveImg'] = $user['positive_img'];
        $dataval['info']['oppositeImg'] = $user['opposite_img'];
        $dataval['info']['status'] = intval($user['status']);

        $addressmodel = get_load_model('address');
        $dataval['addressList'] = $addressmodel->BcModelList($id);

        return $dataval;
    }

    public function BcModelList($name,$phone,$status,$offset,$max){
        $dataval = array();
        $where = "";
        if($status >= 0){
            $where.= " AND status = {$status}";
        }
        if(!empty($name)){
            $where.= " AND nickname LIKE '%{$name}%'";
        }
        if(!empty($phone)){
            $where.= " AND username LIKE '%{$phone}%'";
        }
        $limit = " LIMIT {$offset}, {$max}";
        $orderby = "create_time desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby,$limit);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['username'] = $val['username'];
            $dataval[$key]['nickname'] = $val['nickname'];
            $dataval[$key]['img'] = $val['img'];
            $dataval[$key]['email'] = $val['email'];
            $dataval[$key]['status'] = intval($val['status']);
        }
        $data = array();
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        $data['total'] = intval($total);
        $data['list'] = $dataval;
        return $data;
    }

    public function BcModelIdListByPhone($phone){
        $dataval = array();
        $where = " AND status = 1 ";
        if(!empty($phone)){
            $where.= " AND username LIKE '%{$phone}%'";
        }
        $orderby = "create_time desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
        if (empty($list)) {
            return $dataval;
        }
        $ids = array();
        foreach ($list as $val){
            array_push($ids,intval($val['id']));
        }
        return $ids;
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

    public function ModelVerifyVipTime($id){
        date_default_timezone_set('PRC');
        $time = date('Y-m-d H:i:s');
        $dataval = array();
        $where = " AND id = {$id} AND vip_time > '{$time}'";
        $user = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($user)){
            return $dataval;
        }
        return $user;
    }

    public function ModelUpdateOpenId($userid,$openId) {
        $set[] = "openid = '{$openId}'";
        $where = "AND id = {$userid} ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function ModelUpdateXcxOpenId($userid,$openId) {
        $set[] = "xcx_openid = '{$openId}'";
        $where = "AND id = {$userid} ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }
}