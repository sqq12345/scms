<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/13
 * Time: 10:09
 */
class SpecialProduct{

    public function getList($data){
        $dataval = array();
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        $model = get_load_model('specialProduct');
        $dataval = $model->ModelList($offset,$max);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function buy($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;  //特价商品的id
        $num = isset($data['num']) ? intval($data['num']) : 1;  //购买数量
        if ($userid == 0 || $type == 0 || $id == 0 || $num == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $specialProductModel = get_load_model('specialProduct');
        $specialProduct = $specialProductModel->ModelInfoByIdAndTime($id);
        if(empty($specialProduct)){
            set_return_value(SPECIAL_PRODUCT_NULL_ERROR, '');
            return false;
        }
        $productModel = get_load_model('product');
        $product = $productModel->ModelMiniInfo($specialProduct['pid']);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, '');
            return false;
        }
        if($product['isReduceStock'] > 0){
            if($specialProduct['skuId'] > 0){
                $skuModel = get_load_model('productSku');
                $sku = $skuModel->ModelInfoById($specialProduct['skuId']);
                if($sku['stock'] < $num){
                    set_return_value(PRODUCT_STOCK_NULL, '');
                    return false;
                }
            }else{
                if($product['stock'] < $num){
                    set_return_value(PRODUCT_STOCK_NULL, '');
                    return false;
                }
            }
        }
        //todo
        //判断月购买数量以及金额限制
        $orderModel = get_load_model('order');
        $postData = array();
        $postData['userId'] = $userid;
        $postData['distributionFee'] = $product['distributionFee'];
        $postData['type'] = 2;  //1:一件代发 2:特价
        $pro = array();
        $pro['productId'] = $id;
        $pro['skuId'] = $specialProduct['skuId'];
        $pro['productName'] = $product['name'];
        $pro['attr1'] = $specialProduct['attr1'];
        $pro['attr2'] = $specialProduct['attr2'];
        $pro['productIcon'] = $product['goodIcon'];
        $pro['productPrice'] = $specialProduct['price'];
        $pro['num'] = $num;
        $items = array();
        array_push($items,$pro);
        $postData['items'] = $items;
        $ret = $orderModel->ModelAdd($postData);
        if ($ret !== false) {
            $orderLogModel = get_load_model('orderLog');
            $orderLogModel->ModelAdd($ret,$userid,$type,'','waitforpay','下单成功');
            $dataval = $orderModel->ModelInfo($ret);
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }

    }
}