<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/10
 * Time: 10:52
 */
class BcBespeakCategory{

    public function add($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        $summary = inject_check(isset($data['summary']) ? $data['summary'] : '');
        $parentId = isset($data['parentId']) ? intval($data['parentId']) : 0;
        $img = inject_check(isset($data['img']) ? $data['img'] : '');
        $isShow = isset($data['isShow']) ? intval($data['isShow']) : 0;
        $priority = isset($data['priority']) ? intval($data['priority']) : 0;
        $status = isset($data['status']) ? intval($data['status']) : 0;
        if ($userid == 0 || $type == 0 || empty($name)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakCategory');
        $parentName = '';
        if($parentId > 0){
            $info = $model->BcModelInfo($parentId);
            if(!empty($info)){
                $parentName = $info['name'];
            }
        }
        $ret = $model->BcModelAdd($name,$summary,$parentId,$parentName,$img,$isShow,$priority,$status);
        if($ret == -1){
            set_return_value(CATEGORY_EXIST, '');
        }elseif ($ret > 0){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'bespeakCategory',$ret,4);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;
        if ($userid == 0 || $type == 0 || $id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakCategory');
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
        $summary = inject_check(isset($data['summary']) ? $data['summary'] : '');
        $parentId = isset($data['parentId']) ? intval($data['parentId']) : 0;
        $img = inject_check(isset($data['img']) ? $data['img'] : '');
        $isShow = isset($data['isShow']) ? intval($data['isShow']) : 0;
        $priority = isset($data['priority']) ? intval($data['priority']) : 0;
        $status = isset($data['status']) ? intval($data['status']) : 0;
        if ($userid == 0 || $type == 0 || empty($name)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakCategory');
        $parentName = '';
        if($parentId > 0){
            $info = $model->BcModelInfo($parentId);
            if(!empty($info)){
                $parentName = $info['name'];
            }
        }
        $ret = $model->BcModelUpdate($id,$name,$summary,$parentId,$parentName,$img,$isShow,$priority,$status);
        if($ret == -1){
            set_return_value(CATEGORY_EXIST, '');
        }elseif ($ret > 0){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'bespeakCategory',$id,5);
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
        $status = isset($data['status']) ? intval($data['status']) : -1;
        if ($userid == 0 || $type == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $offset = 0;
        if($page > 0){
            $offset = $limit * ($page - 1);
        }
        $model = get_load_model('bespeakCategory');
        $dataval = $model->BcModelList($name,$status,$offset,$limit);
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
        if ($userid == 0 || $type == 0 || empty($ids)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakCategory');
        $ret = $model->BcModelDelete($ids);
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getParentList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        if ($userid == 0 || $type == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakCategory');
        $dataval = $model->BcModelParentList();
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getDropList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        if ($userid == 0 || $type == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakCategory');
        $dataval = $model->BcModelDropList();
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
        if ($userid == 0 || $type == 0 || $id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakCategory');
        $ret = $model->BcModelUpdateStatus($id,$status);
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'bespeakCategory',$id,6);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getAllList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $model = get_load_model('bespeakCategory');
        $dataval = $model->BcModelGetAllList();
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}