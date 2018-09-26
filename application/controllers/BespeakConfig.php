<?php
/**
 * Created by PhpStorm.
 * 三大页面品牌分类
 * User: zx
 * Date: 2018/5/10
 * Time: 11:24
 */
class BespeakConfig{

    //landtype:1.现货，2.海外，3.预约
    public function getList($data){
        $dataval = array();
        $type = isset($data['landtype']) ? $data['landtype'] : 1;
        $brandModel = get_load_model('brand');
        $dataval['west'] = $brandModel->ModelList($type, 1);
        $dataval['korea'] = $brandModel->ModelList($type, 2);

        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

}