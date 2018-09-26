<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/12
 * Time: 10:13
 */
class Buyer{

    public function login($data){
        $dataval = array();
        $username = inject_check(isset($data['username']) ? $data['username'] : '');
        $password = inject_check(isset($data['password']) ? $data['password'] : '');

        if (empty($username) || empty($password)) {
            set_return_value(WILL_FIELD_NULL, $dataval);
            return false;
        }
        $model = get_load_model('purchase');
        $buyer = $model->ModelBuyerLogin($username);
        if (empty($buyer)) {
            set_return_value(USER_LOGIN_ERROR, $dataval);
            return false;
        }
        $password = md5($password.PASSWORD_MD5);
        if ($buyer['password'] == $password) {
            $dataval = array(
                "id" => intval($buyer["id"]),
                "username" => $buyer["username"],
                "nickname" => $buyer["nickname"],
                "image" => $buyer["image"],
            );
            //token机制
            $tokenmodel = get_load_model('token');
            $token = md5($buyer['id'].time());
            $tokenmodel->ModelAdd($buyer['id'],$token,2);
            $dataval['token'] = $token;
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(PWD_LOGIN_ERROR, $dataval);
        }
    }

    public function noGoods($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $ids = isset($data['ids']) ? $data['ids'] : '';  //分配订单的ids
        if ($userid == 0 || $type < 2 || $type > 2) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($ids)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if(sizeof($ids) > 0){
            $assignOrderModel = get_load_model('assignOrder');
            $ret = 0;
            foreach ($ids as $id){
                $ret = $assignOrderModel->ModelUpdateToNoGoods($id,$userid);
            }
            if($ret){
                set_return_value(RESULT_SUCCESS, $dataval);
            }else{
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function purchased($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $ids = isset($data['ids']) ? $data['ids'] : '';  //分配订单的ids
        if ($userid == 0 || $type < 2 || $type > 2) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($ids)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if(sizeof($ids) > 0){
            $assignOrderModel = get_load_model('assignOrder');
            $ret = 0;
            foreach ($ids as $id){
                $ret = $assignOrderModel->ModelUpdateToPurchased($id,$userid);
            }
            if($ret){
                set_return_value(RESULT_SUCCESS, $dataval);
            }else{
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        if ($userid == 0 || $type < 2 || $type > 2) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $model = get_load_model('assignOrder');
        $dataval = $model->ModelList($userid,$offset,$max);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}