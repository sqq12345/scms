<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/10
 * Time: 11:49
 */
class BcCustomer{

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
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $offset = 0;
        if($page > 0){
            $offset = $limit * ($page - 1);
        }
        $model = get_load_model('customer');
        $dataval = $model->BcModelList($name,$phone,$status,$offset,$limit);
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
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('customer');
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
        $img = inject_check(isset($data['img']) ? $data['img'] : '');
        $email = inject_check(isset($data['email']) ? $data['email'] : '');
        $idCard = inject_check(isset($data['idCard']) ? $data['idCard'] : '');
        $positiveImg = inject_check(isset($data['positiveImg']) ? $data['positiveImg'] : '');
        $oppositeImg = inject_check(isset($data['oppositeImg']) ? $data['oppositeImg'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $set = array();
        $model = get_load_model('customer');
        if(!empty($nickname)) $set[] = "nickname = '{$nickname}'";
        if(!empty($img)) $set[] = "img = '{$img}'";
        if(!empty($email)) $set[] = "email = '{$email}'";
        if(!empty($idCard)) $set[] = "id_card = '{$idCard}'";
        if(!empty($positiveImg)) $set[] = "positive_img = '{$positiveImg}'";
        if(!empty($oppositeImg)) $set[] = "opposite_img = '{$oppositeImg}'";
        $ret = $model->ModelUpdate($set, $id);
        if ($ret) {
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'customer',$id,23);
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
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($ids)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('customer');
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
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('customer');
        $ret = $model->BcModelUpdateStatus($id,$status);
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'customer',$id,24);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }
}