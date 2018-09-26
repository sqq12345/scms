<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/12
 * Time: 10:11
 */
class BcBuyer{

    public function add($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $username = inject_check(isset($data['username']) ? $data['username'] : '');
        $password = inject_check(isset($data['password']) ? $data['password'] : '');
        $nickname = inject_check(isset($data['nickname']) ? $data['nickname'] : '');
        $image = isset($data['image']) ? $data['image'] : '';
        if ($userid == 0 || $type == 0 || empty($username) || empty($password)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('buyer');
        $password = md5($password.PASSWORD_MD5);
        $ret = $model->BcModelAdd($username,$password,$nickname,$image);
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        $phone = inject_check(isset($data['phone']) ? $data['phone'] : '');
        $status = isset($data['status']) ? intval($data['status']) : 1;
        if ($userid == 0 || $type == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('buyer');
        $dataval = $model->BcModelList($name,$phone,$status,$offset,$max);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0; //目标用户的ID
        if ($userid == 0 || $type == 0 || $id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('buyer');
        $dataval = $model->BcModelInfo($id);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function update($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;  //目标用户的id
        $nickname = inject_check(isset($data['nickname']) ? $data['nickname'] : '');
        $password = inject_check(isset($data['password']) ? $data['password'] : '');
        $image = inject_check(isset($data['image']) ? $data['image'] : '');
        if ($userid == 0 || $type == 0 || $id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $set = array();
        $model = get_load_model('buyer');
        if(!empty($nickname)) $set[] = "nickname = '{$nickname}'";
        if(!empty($image)) $set[] = "image = '{$image}'";
        if(!empty($password)){
            $password = md5($password.PASSWORD_MD5);
            $set[] = "password = '{$password}'";
        }
        $ret = $model->ModelUpdate($set, $id);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function delete($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $ids = isset($data['ids']) ? $data['ids'] : '';  //ids
        if ($userid == 0 || $type == 0 || empty($ids)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('buyer');
        $ret = $model->BcModelDelete($ids);
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

}