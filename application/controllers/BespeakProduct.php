<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/12
 * Time: 14:42
 */
class BespeakProduct{

    public function getList($data){
        $dataval = array();
        $cid = isset($data['cid']) ? intval($data['cid']) : 0;    //分类ID
        $bid = isset($data['bid']) ? intval($data['bid']) : 0;    //品牌ID
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        $name = inject_check(isset($data['name']) ? $data['name'] : ''); //商品名称
        $model = get_load_model('bespeakProduct');
        $dataval = $model->ModelListByCidOrBidOrName($cid,$bid,$name,$offset,$max);
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
        $model = get_load_model('bespeakProduct');
        $product = $model->ModelMiniInfo($id);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, $dataval);
            return false;
        }
        $skuModel = get_load_model('bespeakProductSku');
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