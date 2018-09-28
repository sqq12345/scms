<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 16:51
 */
class BcBanner{

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
        $model = get_load_model('banner');
        $dataval = $model->BcModelList($offset,$limit);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval['list'],$dataval['total']);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function add($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $title = inject_check(isset($data['title']) ? $data['title'] : '');
        $img = inject_check(isset($data['img']) ? $data['img'] : '');
        $url = inject_check(isset($data['url']) ? $data['url'] : '');
        $priority = isset($data['priority']) ? intval($data['priority']) : 0;
        $status = isset($data['status']) ? intval($data['status']) : 0;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
 //       if (empty($title) || empty($img) || empty($url)) {
 //           set_return_value(WILL_FIELD_NULL, '');
  //          return false;
  //      }
        $model = get_load_model('banner');
        $ret = $model->BcModelAdd($title,$img,$url,$priority,$status);
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'banner',$ret,1);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function update($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;
        $title = inject_check(isset($data['title']) ? $data['title'] : '');
        $img = inject_check(isset($data['img']) ? $data['img'] : '');
        $url = inject_check(isset($data['url']) ? $data['url'] : '');
        $priority = isset($data['priority']) ? intval($data['priority']) : 0;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('banner');
        $ret = $model->BcModelUpdate($id,$title,$img,$url,$priority);
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'banner',$id,2);
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
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('banner');
        $dataval = $model->BcModelInfo($id);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
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
        $model = get_load_model('banner');
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
        $model = get_load_model('banner');
        $ret = $model->BcModelUpdateStatus($id,$status);
        if($ret){
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'banner',$id,3);
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    //增加热门标签
    public function addHotTag($data){
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $tag = isset($data['tag']) ? $data['tag'] : '';
        $sort = isset($data['sort']) ? intval($data['sort']) : 99;//排序
        $status = isset($data['status']) ? intval($data['status']) : 1;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $model = get_load_model('banner');
        $ret = $model->addHotTag($tag,$sort, $status);
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    //热门标签列表
    public function getTagList($data){
        $page = isset($data['page']) ? intval($data['page']) : 1;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        $model = get_load_model('banner');
        $dataval = $model->getTagList($page, $max);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    //更改热门标签状态
    public function updateTag($data){
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $tid = isset($data['tid']) ? intval($data['tid']) : 0;
        $tag = isset($data['tag']) ? $data['tag'] : '';
        $sort = isset($data['sort']) ? intval($data['sort']) : 99;//排序
        $status = isset($data['status']) ? intval($data['status']) : 1;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if($tid <= 0 || !$tag){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('banner');
        $ret = $model->updateTag($tid, $tag, $sort, $status);
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    //删除标签
    public function delTag($data){
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $tid = isset($data['tid']) ? intval($data['tid']) : 0;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if($tid <= 0){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('banner');
        $ret = $model->delTag($tid);
        if($ret){
            set_return_value(RESULT_SUCCESS, $ret);
        }else{
            set_return_value(DEFEATED_ERROR, '');
        }
    }

}