<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 16:49
 */
class Banner{
    //轮播图
    public function getList(){
        $dataval = array();
        $model = get_load_model('banner');
        $dataval = $model->ModelList();
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    //随机URL跳转
    public function randomUrlJump($data){
        $max = isset($data['max']) ? $data['max'] : 5;  //商品的ID
        $model = get_load_model('banner');
        $dataval = $model->getRandomUrlJupm($max);
        shuffle($dataval);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}
