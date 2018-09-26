<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/17
 * Time: 15:45
 */
class Token{

    public function validate($data){
        $dataval = array();
        $token = inject_check(isset($data['token']) ? $data['token'] : ''); //token的值
        if (empty($token)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('token');
        $info = $model->ModelInfoByToken($token);
        if(empty($info)){
            set_return_value(USER_TOKEN_ERROR, '');
        }
        if($info['type'] == 1){
            $customerModel = get_load_model('customer');
            $customer = $customerModel->ModelInfo($info['userId']);
            $dataval = array(
                "id" => intval($customer["id"]),
                "username" => $customer["username"],
                "nickname" => $customer["nickname"],
                "image" => $customer["img"],
                "type" => intval($customer["type"]),
                "token" => $token,
            );
        }elseif ($info['type'] == 2 || $info['type'] == 3 || $info['type'] == 4){
            $purchaseModel = get_load_model('purchase');
            $purchase = $purchaseModel->BcModelInfo($info['userId']);
            $dataval = array(
                "id" => intval($purchase["id"]),
                "username" => $purchase["username"],
                "nickname" => $purchase["nickname"],
                "image" => $purchase["image"],
                "type" => intval($purchase["type"]),
                "token" => $token,
            );
        }
        sleep(0.5);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(USER_TOKEN_ERROR, $dataval);
        }
    }

    public function getInfoByToken($data){
        $dataval = array();
        $token = inject_check(isset($data['token']) ? $data['token'] : ''); //token的值
        if (empty($token)) {
            set_return_value(USER_TOKEN_ERROR, '');
            return false;
        }
        $model = get_load_model('token');
        $info = $model->ModelInfoByToken($token);
        if(empty($info)){
            set_return_value(USER_TOKEN_ERROR, '');
            return false;
        }
        if($info['type'] == 1){
            $customerModel = get_load_model('customer');
            $dataval = $customerModel->ModelInfo($info['userId']);
            if (!empty($dataval)) {
                set_return_value(RESULT_SUCCESS, $dataval);
            } else {
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }
}