<?php
/**
 * 活动页面
 * User: zx
 * Date: 2018/4/9
 * Time: 16:49
 */
class Activity{
    //首页活动图标
    public function getActivityList($data){
        $dataval = array();
        $page = isset($data['page']) ? intval($data['page']) : 1;
        $max = isset($data['max']) ? intval($data['max']) : 4;
        $model = get_load_model('activity');
        $dataval = $model->getActivityList($page, $max);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    //3大商品分类2级品牌页面上部轮播图
    public function getHeaderBanner($data){
        $dataval = array();
        $landtype = isset($data['landtype']) ? intval($data['landtype']) : 1;
        $page = isset($data['page']) ? intval($data['page']) : 1;
        $max = isset($data['max']) ? intval($data['max']) : 3;
        $model = get_load_model('activity');
        $dataval = $model->getHeaderBanner($landtype, $page, $max);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    //根据活动id获得活动轮播图
    public function getActivityBanner($data){
        $dataval = array();
        $aid = isset($data['aid']) ? intval($data['aid']) : 0;
        $page = isset($data['page']) ? intval($data['page']) : 1;
        $max = isset($data['max']) ? intval($data['max']) : 3;
        if($aid <= 0){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('activity');
        $dataval = $model->getActivityBanner($aid, $page, $max);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    //根据活动id获得活动商品列表
    public function getActivityProduct($data){
        $dataval = array();
        $aid = isset($data['aid']) ? intval($data['aid']) : 0;
        $page = isset($data['page']) ? intval($data['page']) : 1;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        if($aid <= 0){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('activity');
        $dataval = $model->getActivityProduct($aid, $page, $max);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    //添加二级页面各分类下的轮播图
    public function addHeaderBanner($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $title = inject_check(isset($data['title']) ? $data['title'] : '');
        $img = inject_check(isset($data['img']) ? $data['img'] : '');
        $url = inject_check(isset($data['url']) ? $data['url'] : '');
        $sort = isset($data['sort']) ? intval($data['sort']) : 0;
        $status = isset($data['status']) ? intval($data['status']) : 1;
        $landtype = isset($data['landtype']) ? intval($data['landtype']) : 1;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $model = get_load_model('activity');
        $ret = $model->addHeaderBanner($title, $img, $url, $sort, $status, $landtype);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    //更新
    public function updateHeaderBanner($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;
        $title = inject_check(isset($data['title']) ? $data['title'] : '');
        $img = inject_check(isset($data['img']) ? $data['img'] : '');
        $url = inject_check(isset($data['url']) ? $data['url'] : '');
        $sort = isset($data['sort']) ? intval($data['sort']) : 0;
        $status = isset($data['status']) ? intval($data['status']) : 1;
        $landtype = isset($data['landtype']) ? intval($data['landtype']) : 1;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if($id <= 0){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('activity');
        $ret = $model->updateHeaderBanner($id, $title, $img, $url, $sort, $status, $landtype);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}
