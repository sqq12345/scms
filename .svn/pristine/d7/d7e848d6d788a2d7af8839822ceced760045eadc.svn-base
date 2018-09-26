<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/13
 * Time: 9:14
 */
class BcSpecialProduct{

    public function add($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $pid = isset($data['pid']) ? intval($data['pid']) : 0;
        $list = isset($data['list']) ? $data['list'] : '';
        if($userid == 0 || $type == 0 || $pid == 0 || empty($list)){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $productModel = get_load_model('product');
        $product = $productModel->ModelMiniInfo($pid);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, $dataval);
            return false;
        }
        if(sizeof($list) > 0){
            $skuModel = get_load_model('productSku');
            $ret = 0;
            $specialProductModel = get_load_model('specialProduct');
            foreach ($list as $val){
                $skuId = 0;
                if(!empty($val['attr1'])){
                    $sku = $skuModel->ModelSkuByPidAndAttr($pid,$val['attr1'],$val['attr2']);
                    if(!empty($sku)){
                        $skuId = $sku['id'];
                    }
                }
                $ret = $specialProductModel->BcModelAdd($pid,$skuId,$val['attr1'],$val['attr2'],$val['price'],$val['startTime'],$val['endTime']);
            }
            if($ret){
                set_return_value(RESULT_SUCCESS, $dataval);
            }else{
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function update($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;
        $price = isset($data['price']) ? doubleval($data['price']) : 0;
        $startTime = isset($data['startTime']) ? $data['startTime'] : '';
        $endTime = isset($data['endTime']) ? $data['endTime'] : '';
        if($userid == 0 || $type == 0 || $id == 0 || $price == 0 || empty($startTime) || empty($endTime)){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('specialProduct');
        if($price > 0) $set[] = "price = {$price}";
        if(!empty($startTime)) $set[] = "start_time = '{$startTime}'";
        if(!empty($endTime)) $set[] = "end_time = '{$endTime}'";
        $ret = $model->BcModelUpdate($set, $id);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

}