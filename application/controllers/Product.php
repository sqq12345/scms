<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/12
 * Time: 10:25
 */
class Product{

    public function getListByCid($data){
        $dataval = array();
        $cid = isset($data['cid']) ? intval($data['cid']) : 0;    //分类ID
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        $model = get_load_model('product');
        $dataval = $model->ModelListByCid($cid,$offset,$max);
        if (!empty($dataval)) {
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
        $model = get_load_model('product');
        $product = $model->ModelMiniInfo($id);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, $dataval);
            return false;
        }
        $skuModel = get_load_model('productSku');
        $dataval = $skuModel->ModelSkuListByPid($id);
        if (!empty($dataval)) {
            $dataval['id'] = $id;
            $dataval['name'] = $product['name'];
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}