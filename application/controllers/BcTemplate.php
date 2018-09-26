<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/10
 * Time: 17:31
 */
class BcTemplate{

    public function add($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        $elementNames = isset($data['elementNames']) ? $data['elementNames'] : '';
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($name) || empty($elementNames)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('template');
        $ret = $model->BcModelAdd($name);
        if(sizeof($elementNames) > 0){
            $elementmodel = get_load_model(('templateElement'));
            foreach ($elementNames as $val){
                if(!empty($val)){
                    $elementmodel->BcModelAdd($ret,$val);
                }
            }
        }
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'template',$ret,35);
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
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $offset = 0;
        if($page > 0){
            $offset = $limit * ($page - 1);
        }
        $model = get_load_model('template');
        $dataval = $model->BcModelList($offset,$limit);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS,  $dataval['list'],$dataval['total']);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function delete($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;  //模板id
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('template');
        $ret = $model->BcModelDelete($id);
        if($ret){
            $elementModel = get_load_model('templateElement');
            $elementModel->BcModelDelete($id);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function update($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;  //模板id
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        $elementNames = isset($data['elementNames']) ? $data['elementNames'] : '';
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0 || empty($name) || empty($elementNames)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('template');
        $ret = $model->BcModelUpdate($id,$name);
        $elementModel = get_load_model('templateElement');
        $elementModel->BcModelDelete($id);
        if(sizeof($elementNames) > 0){
            foreach ($elementNames as $val){
                if(!empty($val)){
                    $elementModel->BcModelAdd($id,$val);
                }
            }
        }
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'template',$id,36);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function updateStatus($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;  //模板id
        $status = isset($data['status']) ? intval($data['status']) : 0;  //status
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if($status != 1){
            $status = 0;
        }
        $model = get_load_model('template');
        $ret = $model->BcModelUpdateStatus($id,$status);
        if($ret){
            $elementModel = get_load_model('templateElement');
            $elementModel->BcModelUpdateStatus($id,$status);
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'template',$id,37);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getAllList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $model = get_load_model('template');
        $dataval = $model->BcModelAllList();
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS,  $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getListById($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;    //
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('templateElement');
        $dataval = $model->BcModelListById($id);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS,  $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}