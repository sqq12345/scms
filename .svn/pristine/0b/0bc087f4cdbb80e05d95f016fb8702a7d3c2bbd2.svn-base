<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 17:00
 */
class Category{

    public function getList(){
        $dataval = array();
        $categorymodel = get_load_model('category');
        $dataval = $categorymodel->ModelList();
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}