<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/11
 * Time: 13:21
 */
class BcProduct{

    public function addProduct($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;
        $name = inject_check(isset($data['name']) ? $data['name'] : '');   //商品名称
        $info = isset($data['info']) ? $data['info'] : '';   //商品描述
        $price = isset($data['price']) ? doubleval($data['price']) : 0;  //商品售价
        $dutyFreePrice = isset($data['dutyFreePrice']) ? doubleval($data['dutyFreePrice']) : 0;  //商品免税店价
        $minPurchaseNum = isset($data['minPurchaseNum']) ? intval($data['minPurchaseNum']) : 0; //最小购买数量
        $monthLimitNum = isset($data['monthLimitNum']) ? intval($data['monthLimitNum']) : 0; //月限购数量
        $categoryId = isset($data['categoryId']) ? intval($data['categoryId']) : 0;  //分类ID
        $brandId = isset($data['brandId']) ? intval($data['brandId']) : 0;  //品牌ID
        $shortageDisplay = inject_check(isset($data['shortageDisplay']) ? $data['shortageDisplay'] : '');   //商品缺货显示
        $commodityModel = inject_check(isset($data['commodityModel']) ? $data['commodityModel'] : '');   //商品型号
        $ref = inject_check(isset($data['ref']) ? $data['ref'] : '');   //商品编码
        $isReduceStock = isset($data['isReduceStock']) ? intval($data['isReduceStock']) : 0;  //购买后是否减少库存  1：是 0：否
        $distributionFee = isset($data['distributionFee']) ? doubleval($data['distributionFee']) : 0;  //配送费
        $hotLevel = isset($data['hotLevel']) ? intval($data['hotLevel']) : 1; //商品热门级别
        $goodStatus = isset($data['goodStatus']) ? intval($data['goodStatus']) : 0; //商品状态  0：已下架，1：已上架
        $goodIcon = isset($data['goodIcon']) ? $data['goodIcon'] : '';  //商品主图
        $goodImg = isset($data['goodImg']) ? $data['goodImg'] : '';  //商品附加图

        if($userid == 0 || $type == 0 || empty($name) || $price == 0 || $dutyFreePrice == 0 || $minPurchaseNum == 0 || $monthLimitNum == 0 || $categoryId == 0 || empty($shortageDisplay)){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if($brandId > 0){
            $brandModel = get_load_model('brand');
            $brand = $brandModel->BcModelInfo($brandId);
            if(empty($brand)){
                set_return_value(BRAND_NULL, '');
                return false;
            }
        }
        $categoryModel = get_load_model('category');
        $category = $categoryModel->BcModelInfo($categoryId);
        if(empty($category)){
            set_return_value(CATEGORY_NULL, '');
            return false;
        }
        $goodImg = str_replace('\/', '/', json_encode($goodImg));
        $model = get_load_model('product');
        $ret = $model->BcModelAdd($name, $info, $price,$dutyFreePrice,$minPurchaseNum,$monthLimitNum,$categoryId,$brandId,
            $shortageDisplay,$commodityModel,$ref,$isReduceStock,$distributionFee,$hotLevel,$goodStatus,$goodIcon,$goodImg);
        if($ret){
            $dataval['id'] = intval($ret);
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
        if($userid == 0 || $type == 0 || $pid == 0){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('product');
        $product = $model->ModelMiniInfo($pid);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, $dataval);
            return false;
        }
        if(sizeof($list) > 0){
            $total = 0;
            foreach ($list as $val){
                $total = $total + $val['stock'];
            }
        }

        $set = array();
        $set[] = "stock = '{$total}'";
        $ret = $model->BcModelUpdate($set,$pid);
        if($ret){
            if(sizeof($list) > 0){
                $skuModel = get_load_model('productSku');
                foreach ($list as $val){
                    $model1 = isset($val['model1']['name']) ? $val['model1']['name'] : '';
                    $attr1 = isset($val['model1']['attr']) ? $val['model1']['attr'] : '';
                    $model2 = isset($val['model2']['name']) ? $val['model2']['name'] : '';
                    $attr2 = isset($val['model2']['attr']) ? $val['model2']['attr'] : '';
                    $stock = isset($val['stock']) ? intval($val['stock']) : 0;
                    $price = isset($val['price']) ? doubleval($val['price']) : 0;
                    $skuModel->BcModelAdd($pid,$model1,$attr1,$model2,$attr2,$stock,$price);
                }
            }
            $dataval['id'] = intval($pid);
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
        $model = get_load_model('product');
        $product = $model->BcModelInfo($id);
        if(empty($product)){
            set_return_value(DEFEATED_ERROR, $dataval);
            return false;
        }
        $dataval['info'] = $product;
        $skuModel = get_load_model('productSku');
        $list = $skuModel->BcModelListByPid($id);
        $dataval['list'] = $list;
        $templateList = $skuModel->ModelTemplateListByPid($id);
        $dataval['templateList'] = $templateList;
        $specialProductModel = get_load_model('specialProduct');
        $dataval['specialList'] = $specialProductModel->BcModelListByPid($id);
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
        $info = inject_check(isset($data['info']) ? $data['info'] : '');   //商品描述
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
        $goodStatus = isset($data['goodStatus']) ? intval($data['goodStatus']) : 0; //商品状态  0：已下架，1：已上架
        $goodIcon = isset($data['goodIcon']) ? $data['goodIcon'] : '';  //商品主图
        $goodImg = isset($data['goodImg']) ? $data['goodImg'] : '';  //商品附加图

        if($userid == 0 || $type == 0 || $id == 0 || empty($name) || $price == 0 || $dutyFreePrice == 0 || $minPurchaseNum == 0 || $monthLimitNum == 0 || $categoryId == 0 || $brandId == 0 || empty($shortageDisplay) || empty($commodityModel) || empty($ref)){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('product');
        $product = $model->BcModelInfo($id);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, $dataval);
            return false;
        }
        $goodImg = str_replace('\/', '/', json_encode($goodImg));
        $ret = $model->BcModelUpdateInfo($id,$name, $info, $price,$dutyFreePrice,$minPurchaseNum,$monthLimitNum,$categoryId,$brandId,
            $shortageDisplay,$commodityModel,$ref,$isReduceStock,$distributionFee,$hotLevel,$goodStatus,$goodIcon,$goodImg);
        if($ret){
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
        $model = get_load_model('product');
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
        $page = isset($_POST['page']) ? $_POST['page'] : 0;
        $limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        $commodityModel = inject_check(isset($data['commodityModel']) ? $data['commodityModel'] : '');   //商品型号
        $priceMin = isset($data['priceMin']) ? doubleval($data['priceMin']) : 0;  //商品售价
        $priceMax = isset($data['priceMax']) ? doubleval($data['priceMax']) : 0;  //商品售价
        $categoryId = isset($data['categoryId']) ? intval($data['categoryId']) : 0;  //分类ID
        $status = isset($data['status']) ? intval($data['status']) : 1;
        $goodStatus = isset($data['goodStatus']) ? intval($data['goodStatus']) : -1; //商品上架下架状态  1：商家，0：下架
        if ($userid == 0 || $type == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $offset = 0;
        if($page > 0){
            $offset = $limit * ($page - 1);
        }
        $model = get_load_model('product');
        $dataval = $model->BcModelList($name,$commodityModel,$priceMin,$priceMax,$categoryId,$status,$goodStatus,$offset,$limit);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval['list'],$dataval['total']);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function updateSku($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;
        $pid = isset($data['pid']) ? intval($data['pid']) : 0;
        $total = isset($data['total']) ? intval($data['total']) : 0;
        $list = isset($data['list']) ? $data['list'] : '';
        if($userid == 0 || $type == 0 || $pid == 0){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('product');
        $product = $model->ModelMiniInfo($pid);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, $dataval);
            return false;
        }
        if(sizeof($list) > 0){
            $total = 0;
            foreach ($list as $val){
                $total = $total + $val['stock'];
            }
        }
        $set = array();
        $set[] = "stock = '{$total}'";
        $ret = $model->BcModelUpdate($set,$pid);
        if($ret){
            if(sizeof($list) > 0){
                $skuModel = get_load_model('productSku');
                $skuModel->BcModelDelete($pid);
                foreach ($list as $val){
                    $model1 = isset($val['model1']['name']) ? $val['model1']['name'] : '';
                    $attr1 = isset($val['model1']['attr']) ? $val['model1']['attr'] : '';
                    $model2 = isset($val['model2']['name']) ? $val['model2']['name'] : '';
                    $attr2 = isset($val['model2']['attr']) ? $val['model2']['attr'] : '';
                    $stock = isset($val['stock']) ? intval($val['stock']) : 0;
                    $price = isset($val['price']) ? doubleval($val['price']) : 0;
                    $skuModel->BcModelAdd($pid,$model1,$attr1,$model2,$attr2,$stock,$price);
                }
            }
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function updateStatus($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? $data['id'] : 0;  //商品的ID
        $goodStatus = isset($data['goodStatus']) ? intval($data['goodStatus']) : 0; //商品状态  0：已下架，1：已上架
        if ($userid == 0 || $type == 0 || $id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('product');
        $set = array();
        $set[] = "good_status = {$goodStatus}";
        $ret = $model->BcModelUpdate($set,$id);
        if($ret){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }
}