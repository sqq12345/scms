<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/12
 * Time: 10:18
 */
class Purchase{

    public function login($data){
        $dataval = array();
        $username = inject_check(isset($data['username']) ? $data['username'] : '');
        $password = inject_check(isset($data['password']) ? $data['password'] : '');

        if (empty($username) || empty($password)) {
            set_return_value(WILL_FIELD_NULL, $dataval);
            return false;
        }
        $model = get_load_model('purchase');
        $purchase = $model->ModelLogin($username);
        if (empty($purchase)) {
            set_return_value(USER_LOGIN_ERROR, $dataval);
            return false;
        }
        if($purchase['type'] < 3){
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $password = md5($password.PASSWORD_MD5);
        if ($purchase['password'] == $password) {
            $dataval = array(
                "id" => intval($purchase["id"]),
                "username" => $purchase["username"],
                "nickname" => $purchase["nickname"],
                "image" => $purchase["image"],
                "type" => intval($purchase["type"]),
            );
            //token机制
            $tokenmodel = get_load_model('token');
            $token = md5($purchase['id'].time());
            $tokenmodel->ModelAdd($purchase['id'],$token,$purchase['type']);
            $dataval['token'] = $token;
          	sleep(0.5);
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(PWD_LOGIN_ERROR, $dataval);
        }
    }

    public function update($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $nickname = inject_check(isset($data['nickname']) ? $data['nickname'] : '');
        $password = inject_check(isset($data['password']) ? $data['password'] : '');
        $image = inject_check(isset($data['image']) ? $data['image'] : '');
        if ($userid == 0 || $type == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $set = array();
        $model = get_load_model('purchase');
        if(!empty($nickname)) $set[] = "nickname = '{$nickname}'";
        if(!empty($image)) $set[] = "image = '{$image}'";
        if(!empty($password)){
            $password = md5($password.PASSWORD_MD5);
            $set[] = "password = '{$password}'";
        }
        $ret = $model->ModelUpdate($set, $userid);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        if ($userid == 0 || $type == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('purchase');
        $dataval = $model->ModelInfo($userid);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

}