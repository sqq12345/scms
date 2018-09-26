<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/10
 * Time: 10:18
 */
class Bcbrand{
    public function add($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        $img = inject_check(isset($data['img']) ? $data['img'] : '');
        $priority = isset($data['priority']) ? intval($data['priority']) : 0;
        $brandType = isset($data['brandType']) ? intval($data['brandType']) : 1;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($name)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('brand');
        $ret = $model->BcModelAdd($name,$img,$brandType,$priority);
        if($ret > 0){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'brand',$ret,17);
            set_return_value(RESULT_SUCCESS, $dataval);
        }elseif($ret < 0){
            set_return_value(BRAND_EXIST, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('brand');
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
        $id = isset($data['id']) ? intval($data['id']) : 0;
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        $img = inject_check(isset($data['img']) ? $data['img'] : '');
        $priority = isset($data['priority']) ? intval($data['priority']) : 0;
        $brandType = isset($data['brandType']) ? intval($data['brandType']) : 1;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($name)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('brand');
        $ret = $model->BcModelUpdate($id,$name,$img,$brandType,$priority);
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'brand',$id,18);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        $page = isset($_POST['page']) ? $_POST['page'] : 0;
        $limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $offset = 0;
        if($page > 0){
            $offset = $limit * ($page - 1);
        }
        $model = get_load_model('brand');
        $dataval = $model->BcModelList($name,$offset,$limit);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval['list'],$dataval['total']);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
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
        $model = get_load_model('brand');
        $ret = $model->BcModelDelete($ids);
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function bcGetList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $model = get_load_model('brand');
        $dataval = $model->BcModelGetList();
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
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
        $model = get_load_model('brand');
        $ret = $model->BcModelUpdateStatus($id,$status);
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'brand',$id,19);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getListByName($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($name)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('brand');
        $dataval = $model->BcModelGetListByName($name);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}