<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/12
 * Time: 9:47
 */
class BcPurchase{

    public function add($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $username = inject_check(isset($data['username']) ? $data['username'] : '');
        $password = inject_check(isset($data['password']) ? $data['password'] : '');
        $nickname = inject_check(isset($data['nickname']) ? $data['nickname'] : '');
        $phone = inject_check(isset($data['phone']) ? $data['phone'] : '');
        $image = isset($data['image']) ? $data['image'] : '';
        $purchaseType = isset($data['purchaseType']) ? intval($data['purchaseType']) : 2; //1:用户 2：买手 3：平台运营 4：admin
        $status = isset($data['status']) ? intval($data['status']) : 0;
        if ($userid == 0 || empty($username) || empty($password) || empty($phone)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if(($type == 3 && $purchaseType > 2) || $type < 3){
            set_return_value(AUTH_ERROR, '');
            return false;
        }
   //     if (!preg_match("/1[34578]{1}\d{9}$/", $phone)) {
    //        set_return_value(PHONE_ERROR, '');
   //         return false;
   //     }
        $model = get_load_model('purchase');
        $password = md5($password.PASSWORD_MD5);
        $ret = $model->BcModelAdd($username,$password,$nickname,$phone,$image,$purchaseType,$status);
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'purchase',$ret,30);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $page = isset($_POST['page']) ? $_POST['page'] : 0;
        $limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        $phone = inject_check(isset($data['phone']) ? $data['phone'] : '');
        $status = isset($data['status']) ? intval($data['status']) : -1;
        if ($userid == 0 || $type < 3) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $offset = 0;
        if($page > 0){
            $offset = $limit * ($page - 1);
        }
        $purchaseType = 0;
        if($type == 3){
            $purchaseType = 2;
        }
        $model = get_load_model('purchase');
        $dataval = $model->BcModelList($name,$phone,$purchaseType,$status,$offset,$limit);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval['list'],$dataval['total']);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0; //目标用户的ID
        if ($userid == 0 || $id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('purchase');
        $dataval = $model->BcModelInfo($id);
        if (!empty($dataval)) {
            if(($type == 3 && $dataval['type'] > 2) || $type < 3){
                set_return_value(AUTH_ERROR, '');
                return false;
            }
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
        $phone = inject_check(isset($data['phone']) ? $data['phone'] : '');
        $image = inject_check(isset($data['image']) ? $data['image'] : '');
        $purchaseType = isset($data['purchaseType']) ? intval($data['purchaseType']) : 2; //1:用户 2：买手 3：平台运营 4：admin
        $status = isset($data['status']) ? intval($data['status']) : 0;
        if ($userid == 0 || $id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $set = array();
        $model = get_load_model('purchase');
        $info = $model->BcModelInfo($id);
        if(empty($info)){
            set_return_value(USER_NULL_ERROR, '');
            return false;
        }
        if(($type == 3 && $info['type'] > 2) || $type < 3 || ($type == 3 && $purchaseType > 2)){
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if(!empty($nickname)) $set[] = "nickname = '{$nickname}'";
        if(!empty($phone)) $set[] = "phone = '{$phone}'";
        if(!empty($image)) $set[] = "image = '{$image}'";
        if(!empty($password)){
            $password = md5($password.PASSWORD_MD5);
            $set[] = "password = '{$password}'";
        }
        $set[] = "type = {$purchaseType}";
        $set[] = "status = {$status}";
        $ret = $model->ModelUpdate($set, $id);
        if ($ret) {
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'purchase',$id,31);
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
        if ($userid == 0 || $type > 3 || empty($ids)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('purchase');
        $ret = $model->BcModelDelete($ids);
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function updateStatus($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;  //id
        $status = isset($data['status']) ? intval($data['status']) : 0;
        if ($userid == 0 || $type == 0 || $id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('purchase');
        $info = $model->BcModelInfo($id);
        if(empty($info)){
            set_return_value(USER_NULL_ERROR, '');
            return false;
        }
        if(($type == 3 && $info['type'] > 2) || $type < 3){
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $ret = $model->BcModelUpdateStatus($id,$status);
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'purchase',$id,32);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }
}