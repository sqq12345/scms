<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/3
 * Time: 12:00
 */
class ReConfig{

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $clazz = inject_check(isset($data['clazz']) ? $data['clazz'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($clazz)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('config');
        $dataval = $model->BcModelInfo($clazz);
        set_return_value(RESULT_SUCCESS, $dataval);
    }

    public function doSubmit($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $clazz = inject_check(isset($data['clazz']) ? $data['clazz'] : '');
        $cType = isset($data['cType']) ? intval($data['cType']) : 1;  //邮费配置，type：1 一件代发，2 预约爆款， 3 全部
        $value = isset($data['value']) ? intval($data['value']) : 0;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $model = get_load_model('config');
        $info = $model->BcModelInfo($clazz);
        $ret = 0;
        if(empty($info)){
            $ret = $model->BcModelAdd($clazz,$cType,$value);
        }else{
            $ret = $model->BcModelUpdate($clazz,$cType,$value);
        }
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getNowTime(){
        date_default_timezone_set('PRC');
        $nowtime = date('Y-m-d H:i:s');
        set_return_value(RESULT_SUCCESS, $nowtime);
    }

    public function getRegionList(){
        $dataval = array();
        $model = get_load_model('region');
        $dataval = $model->ModelList();
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getFareTemplateList(){
        $dataval = array();
        $model = get_load_model('fareTemplate');
        $dataval = $model->BcModelList();
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}