<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/2
 * Time: 15:59
 */
class ReProduct{

    public function getListByCid($data){
        $dataval = array();
        $cid = isset($data['cid']) ? intval($data['cid']) : 0;    //分类ID
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        $model = get_load_model('reProduct');
        $list = $model->ModelListByCid($cid,$offset,$max);
        if (!empty($list)) {
            $dataval['cid'] = $cid;
            $dataval['list'] = $list;
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getSkuListById($data){
        $dataval = array();
        $id = isset($data['id']) ? intval($data['id']) : 0;    //商品ID
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('reProduct');
        $product = $model->ModelMiniInfo($id);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, $dataval);
            return false;
        }
        $skuModel = get_load_model('reProductSku');
        $list = $skuModel->ModelSkuListByPid($id);
        if (!empty($list) && sizeof($list) > 0) {
            $dataval['id'] = $id;
            $dataval['name'] = $product['name'];
            $dataval['model'] = $list[0]['model'];
            $dataval['list'] = $list;
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    //根据id获取预约商品详情
    public function getBespeakSkuById($data){
        $dataval = array();
        $id = isset($data['id']) ? intval($data['id']) : 0;    //商品ID
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('reProduct');
        $product = $model->ModelMiniInfo($id);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, $dataval);
            return false;
        }
        $skuModel = get_load_model('reProductSku');
        $list = $skuModel->ModelSkuListByPid($id);
        if (!empty($list) && sizeof($list) > 0) {
            $dataval['id'] = $id;
            $dataval['name'] = $product['name'];
            $dataval['model'] = $list[0]['model'];
            $dataval['list'] = $list;
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}