<?php
/**
 * Created by PhpStorm.
 * 物流公司
 * User: huiyong.yu
 * Date: 2018/5/24
 * Time: 10:37
 */
class Company{

    public function getList(){
        $dataval = array();
        $model = get_load_model('company');
        $dataval = $model->ModelList();
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}