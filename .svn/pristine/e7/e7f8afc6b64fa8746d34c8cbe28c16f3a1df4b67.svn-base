<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/8
 * Time: 12:44
 */
class ReSpecialProduct{

    public function getList($data){
        $dataval = array();
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $max = isset($data['max']) ? intval($data['max']) : 10; //一次查询数量
        $model = get_load_model('reSpecialProduct');
        $dataval = $model->ModelList($offset,$max);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    //秒杀
    public function getKillList($data){
        $daydate = isset($data['daydate']) ? $data['daydate'] : date('Y-m-d');
        $max = isset($data['max']) ? intval($data['max']) : '';
        $model = get_load_model('reSpecialProduct');
        $dataval['list'] = $model->getKillList($daydate,$max);
        foreach ($dataval['list'] as $key => $value) {
            $timestamp = strtotime($value['endtime']);
            $dataval['session'] = substr($value['starttime'],11,5);
            $dataval['endtime'] = date("Y/m/d H:i:s", $timestamp);
        }
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}