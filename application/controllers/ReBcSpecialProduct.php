<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/28
 * Time: 13:30
 */
class ReBcSpecialProduct{

    public function add($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $pType = isset($data['pType']) ? intval($data['pType']) : 1;  //商品类型 1：一件代发  2：预约爆款
        $pid = isset($data['pid']) ? intval($data['pid']) : 0;
        $skuId = isset($data['skuId']) ? intval($data['skuId']) : 0;
        $price = isset($data['price']) ? doubleval($data['price']) : 0;  //商品售价
        $priority = isset($data['priority']) ? intval($data['priority']) : 0;
        $startTime = inject_check(isset($data['startTime']) ? $data['startTime'] : '');
        $endTime = inject_check(isset($data['endTime']) ? $data['endTime'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if($pid == 0 || $skuId == 0 || $price == 0 || empty($startTime) || empty($endTime)){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if(strtotime($startTime) >= strtotime($endTime)){
            set_return_value(TIME_ERROR, '');
            return false;
        }
        if($pType == 1){
            $productModel = get_load_model('reProduct');
            $product = $productModel->BcModelMiniInfo($pid);
            if(empty($product)){
                set_return_value(PRODUCT_NULL_ERROR, $dataval);
                return false;
            }
            $skuModel = get_load_model('reProductSku');
            $sku = $skuModel->BcModelInfoById($skuId);
            if(empty($sku)){
                set_return_value(PRODUCT_SKU_ERROR, $dataval);
                return false;
            }
            $model = get_load_model('reSpecialProduct');
            $row = $model->BcModelFindBySkuIdAndTime($pType,$skuId);
            if(!empty($row)){
                set_return_value(SPECIAL_SKU_ERROR, $dataval);
                return false;
            }
            $ret = $model->BcModelAdd($pType,$pid,$product['name'],$skuId,$price,$priority,$startTime,$endTime,$userid);
            if($ret){
                $logModel = get_load_model('operationLog');
                $logModel->ModelAdd($userid,'specialProduct',$ret,48);
                set_return_value(RESULT_SUCCESS, $dataval);
            }else{
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }elseif ($pType == 2){
            $productModel = get_load_model('reBespeakProduct');
            $product = $productModel->BcModelMiniInfo($pid);
            if(empty($product)){
                set_return_value(PRODUCT_NULL_ERROR, $dataval);
                return false;
            }
            $skuModel = get_load_model('reBespeakProductSku');
            $sku = $skuModel->BcModelInfoById($skuId);
            if(empty($sku)){
                set_return_value(PRODUCT_SKU_ERROR, $dataval);
                return false;
            }
            $model = get_load_model('reSpecialProduct');
            $row = $model->BcModelFindBySkuIdAndTime($pType,$skuId);
            if(!empty($row)){
                set_return_value(SPECIAL_SKU_ERROR, $dataval);
                return false;
            }
            $ret = $model->BcModelAdd($pType,$pid,$product['name'],$skuId,$price,$priority,$startTime,$endTime,$userid);
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
        $id = isset($data['id']) ? $data['id'] : 0;  //特价商品的ID
        $price = isset($data['price']) ? doubleval($data['price']) : 0;  //商品售价
        $priority = isset($data['priority']) ? intval($data['priority']) : 0;
        $startTime = inject_check(isset($data['startTime']) ? $data['startTime'] : '');
        $endTime = inject_check(isset($data['endTime']) ? $data['endTime'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0 || $price == 0 || empty($startTime) || empty($endTime)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if(strtotime($startTime) >= strtotime($endTime)){
            set_return_value(TIME_ERROR, '');
            return false;
        }
        $model = get_load_model('reSpecialProduct');
        $set = array();
        $set[] = " price = {$price}";
        $set[] = " priority = {$priority}";
        $set[] = " start_time = '{$startTime}'";
        $set[] = " end_time = '{$endTime}'";
        $set[] = " update_by = {$userid}";
        $ret = $model->BcModelUpdate($set, $id);
        if ($ret) {
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'specialProduct',$id,49);
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
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
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $offset = 0;
        if($page > 0){
            $offset = $limit * ($page - 1);
        }
        $model = get_load_model('reSpecialProduct');
        $dataval = $model->BcModelList($name,$offset,$limit);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval['list'],$dataval['total']);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getProductList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $pType = isset($data['pType']) ? intval($data['pType']) : 1;  //商品类型 1：一件代发  2：预约爆款
        $name = inject_check(isset($data['name']) ? $data['name'] : '');  //商品的名称
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($name)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if($pType == 1){
            $model = get_load_model('reProduct');
            $dataval = $model->BcModelListByName($name);
            if (!empty($dataval)) {
                set_return_value(RESULT_SUCCESS, $dataval);
            } else {
                set_return_value(RESULT_ERROR_NULL, $dataval);
            }
        }elseif ($pType == 2){
            $model = get_load_model('reBespeakProduct');
            $dataval = $model->BcModelListByName($name);
            if (!empty($dataval)) {
                set_return_value(RESULT_SUCCESS, $dataval);
            } else {
                set_return_value(RESULT_ERROR_NULL, $dataval);
            }
        }else{
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getSkuList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $pType = isset($data['pType']) ? intval($data['pType']) : 1;  //商品类型 1：一件代发  2：预约爆款
        $id = isset($data['id']) ? intval($data['id']) : 0;  //商品ID
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if($pType == 1){
            $model = get_load_model('reProductSku');
            $dataval = $model->BcModelMiniListByPid($id);
            if (!empty($dataval)) {
                set_return_value(RESULT_SUCCESS, $dataval);
            } else {
                set_return_value(RESULT_ERROR_NULL, $dataval);
            }
        }elseif ($pType == 2){
            $model = get_load_model('reBespeakProductSku');
            $dataval = $model->BcModelMiniListByPid($id);
            if (!empty($dataval)) {
                set_return_value(RESULT_SUCCESS, $dataval);
            } else {
                set_return_value(RESULT_ERROR_NULL, $dataval);
            }
        }else{
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('reSpecialProduct');
        $dataval = $model->BcModelInfo($id);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    //增加秒杀
    public function addSecondKill($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $ptype = isset($data['ptype']) ? intval($data['ptype']) : 1;  //商品类型 
        $goodsid = isset($data['goodsid']) ? intval($data['goodsid']) : 0;
        $skuid = isset($data['skuid']) ? intval($data['skuid']) : 0;
        $num = isset($data['num']) ? intval($data['num']) : 0;//数量
        $price_limit = isset($data['price_limit']) ? doubleval($data['price_limit']) : 0;  //商品售价
        $starttime = inject_check(isset($data['starttime']) ? $data['starttime'] : '');
        $endtime = inject_check(isset($data['endtime']) ? $data['endtime'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if($goodsid == 0 || $skuid == 0 || $price_limit == 0 || $num == 0 || empty($starttime) || empty($endtime)){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if(strtotime($starttime) >= strtotime($endtime)){
            set_return_value(TIME_ERROR, '');
            return false;
        }
        $skuModel = get_load_model('reProductSku');
        $list = $skuModel->ModelSkuListByPid($skuid);
        if($num >= $list[0]['stock']){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('reSpecialProduct');
        $res = $model->addSecondKill($ptype, $goodsid, $skuid, $price_limit, $num, $starttime, $endtime);
        if($res){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    //调整百分比
    public function adjustPercent($data){
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $perid = isset($data['perid']) ? intval($data['perid']) : 0;
        $percent = isset($data['perid']) ? intval($data['perid']) : 0;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if($perid == 0 || $percent <= 0 || $percent > 100){
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('reSpecialProduct');
        $res = $model->updatePercent($perid, $percent);
        if($res){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }
}