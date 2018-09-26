<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/11
 * Time: 16:17
 */
class BcBespeakProduct{

    public function addProduct($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;
        $name = inject_check(isset($data['name']) ? $data['name'] : '');   //商品名称
        $describe = inject_check(isset($data['describe']) ? $data['describe'] : '');   //商品描述
        $price = isset($data['price']) ? doubleval($data['price']) : 0;  //商品售价
        $dutyFreePrice = isset($data['dutyFreePrice']) ? doubleval($data['dutyFreePrice']) : 0;  //商品免税店价
        $minPurchaseNum = isset($data['minPurchaseNum']) ? intval($data['minPurchaseNum']) : 0; //最小购买数量
        $monthLimitNum = isset($data['monthLimitNum']) ? intval($data['monthLimitNum']) : 0; //月限购数量
        $categoryId = isset($data['categoryId']) ? intval($data['categoryId']) : 0;  //分类ID
        $brandId = isset($data['brandId']) ? intval($data['brandId']) : 0;  //品牌ID
        $shortageDisplay = inject_check(isset($data['shortageDisplay']) ? $data['shortageDisplay'] : '');   //商品缺货显示
        $commodityModel = inject_check(isset($data['commodityModel']) ? $data['commodityModel'] : '');   //商品型号
        $ref = inject_check(isset($data['ref']) ? $data['ref'] : '');   //商品编码
        $isReduceStock = isset($data['isReduceStock']) ? intval($data['isReduceStock']) : 1;  //购买后是否减少库存  1：是 2：否
        $distributionFee = isset($data['distributionFee']) ? doubleval($data['distributionFee']) : 0;  //配送费
        $hotLevel = isset($data['hotLevel']) ? intval($data['hotLevel']) : 1; //商品热门级别
        $goodStatus = isset($data['goodStatus']) ? intval($data['goodStatus']) : 1; //商品状态  1：已下架，2：已上架
        $goodIcon = isset($data['goodIcon']) ? $data['goodIcon'] : '';  //商品主图
        $goodImg = isset($data['goodImg']) ? $data['goodImg'] : '';  //商品附加图

        if($userid == 0 || $type == 0 || empty($name) || $price == 0 || $dutyFreePrice == 0 || $minPurchaseNum == 0 || $monthLimitNum == 0 || $categoryId == 0 || $brandId == 0 || empty($shortageDisplay) || empty($commodityModel) || empty($ref)){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $goodImg = str_replace('\/', '/', json_encode($goodImg));
        $model = get_load_model('bespeakProduct');
        $ret = $model->BcModelAdd($name, $describe, $price,$dutyFreePrice,$minPurchaseNum,$monthLimitNum,$categoryId,$brandId,
            $shortageDisplay,$commodityModel,$ref,$isReduceStock,$distributionFee,$hotLevel,$goodStatus,$goodIcon,$goodImg);
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function addSku($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;
        $pid = isset($data['pid']) ? intval($data['pid']) : 0;
        $total = isset($data['total']) ? intval($data['total']) : 0;
        $list = isset($data['list']) ? $data['list'] : '';
        if($userid == 0 || $type == 0 || $pid == 0 || $total == 0){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakProduct');
        $product = $model->BcModelInfo($pid);
        if(empty($product)){
            set_return_value(DEFEATED_ERROR, $dataval);
            return false;
        }
        $set = array();
        $set[] = "stock = '{$total}'";
        $ret = $model->BcModelUpdate($set,$pid);
        if($ret){
            if(sizeof($list) > 0){
                $skuModel = get_load_model('bespeakProductSku');
                foreach ($list as $val){
                    $skuModel->BcModelAdd($pid,$val['model1']['name'],$val['model1']['attr'],$val['model2']['name'],$val['model2']['attr'],$val['stock'],$val['price']);
                }
            }
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;
        $id = isset($data['id']) ? intval($data['id']) : 0;
        if($userid == 0 || $type == 0 || $id == 0){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakProduct');
        $product = $model->BcModelInfo($id);
        if(empty($product)){
            set_return_value(DEFEATED_ERROR, $dataval);
            return false;
        }
        $dataval['info'] = $product;
        $skuModel = get_load_model('bespeakProductSku');
        $list = $skuModel->BcModelListByPid();
        $dataval['list'] = $list;
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function update($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;
        $id = isset($data['id']) ? intval($data['id']) : 0;
        $name = inject_check(isset($data['name']) ? $data['name'] : '');   //商品名称
        $describe = inject_check(isset($data['describe']) ? $data['describe'] : '');   //商品描述
        $price = isset($data['price']) ? doubleval($data['price']) : 0;  //商品售价
        $dutyFreePrice = isset($data['dutyFreePrice']) ? doubleval($data['dutyFreePrice']) : 0;  //商品免税店价
        $minPurchaseNum = isset($data['minPurchaseNum']) ? intval($data['minPurchaseNum']) : 0; //最小购买数量
        $monthLimitNum = isset($data['monthLimitNum']) ? intval($data['monthLimitNum']) : 0; //月限购数量
        $categoryId = isset($data['categoryId']) ? intval($data['categoryId']) : 0;  //分类ID
        $brandId = isset($data['brandId']) ? intval($data['brandId']) : 0;  //品牌ID
        $shortageDisplay = inject_check(isset($data['shortageDisplay']) ? $data['shortageDisplay'] : '');   //商品缺货显示
        $commodityModel = inject_check(isset($data['commodityModel']) ? $data['commodityModel'] : '');   //商品型号
        $ref = inject_check(isset($data['ref']) ? $data['ref'] : '');   //商品编码
        $isReduceStock = isset($data['isReduceStock']) ? intval($data['isReduceStock']) : 1;  //购买后是否减少库存  1：是 2：否
        $distributionFee = isset($data['distributionFee']) ? doubleval($data['distributionFee']) : 0;  //配送费
        $hotLevel = isset($data['hotLevel']) ? intval($data['hotLevel']) : 1; //商品热门级别
        $goodStatus = isset($data['goodStatus']) ? intval($data['goodStatus']) : 1; //商品状态  1：已下架，2：已上架
        $goodIcon = isset($data['goodIcon']) ? $data['goodIcon'] : '';  //商品主图
        $goodImg = isset($data['goodImg']) ? $data['goodImg'] : '';  //商品附加图
        $total = isset($data['total']) ? intval($data['total']) : 0; //商品库存
        $list = isset($data['list']) ? $data['list'] : '';  //sku

        if($userid == 0 || $type == 0 || $id == 0 || empty($name) || $price == 0 || $dutyFreePrice == 0 || $minPurchaseNum == 0 || $monthLimitNum == 0 || $categoryId == 0 || $brandId == 0 || empty($shortageDisplay) || empty($commodityModel) || empty($ref)){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakProduct');
        $product = $model->BcModelInfo($id);
        if(empty($product)){
            set_return_value(DEFEATED_ERROR, $dataval);
            return false;
        }
        $ret = $model->BcModelUpdateInfo($id,$name, $describe, $price,$dutyFreePrice,$minPurchaseNum,$monthLimitNum,$categoryId,$brandId,
            $shortageDisplay,$commodityModel,$ref,$isReduceStock,$distributionFee,$hotLevel,$goodStatus,$goodIcon,$goodImg,$total);
        if($ret){
            if(sizeof($list) > 0){
                $skuModel = get_load_model('bespeakProductSku');
                $skuModel->BcModelDelete($id);
                foreach ($list as $val){
                    $skuModel->BcModelAdd($id,$val['model1']['name'],$val['model1']['attr'],$val['model2']['name'],$val['model2']['attr'],$val['stock'],$val['price']);
                }
            }
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function delete($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $ids = isset($data['ids']) ? $data['ids'] : '';  //ids
        if ($userid == 0 || $type == 0 || empty($ids)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakProduct');
        $ret = $model->BcModelDelete($ids);
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        $commodityModel = inject_check(isset($data['commodityModel']) ? $data['commodityModel'] : '');   //商品型号
        $price = isset($data['price']) ? doubleval($data['price']) : 0;  //商品售价
        $stock = isset($data['stock']) ? intval($data['stock']) : 0; //商品库存
        $categoryId = isset($data['categoryId']) ? intval($data['categoryId']) : 0;  //分类ID
        $status = isset($data['status']) ? intval($data['status']) : 1;
        if ($userid == 0 || $type == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakProduct');
        $dataval = $model->BcModelList($name,$commodityModel,$price,$stock,$categoryId,$status,$offset,$max);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}