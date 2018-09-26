<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/4
 * Time: 14:35
 */
class ReBespeakProduct{

    public function getList($data){
        $dataval = array();
        $cid = isset($data['cid']) ? intval($data['cid']) : 0;    //分类ID
        $bid = isset($data['bid']) ? intval($data['bid']) : 0;    //品牌ID
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        $name = inject_check(isset($data['name']) ? $data['name'] : ''); //商品名称
        $model = get_load_model('reBespeakProduct');
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
        $model = get_load_model('reBespeakProduct');
        $product = $model->ModelMiniInfo($id);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, $dataval);
            return false;
        }
        $skuModel = get_load_model('reBespeakProductSku');
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

    public function buy($data){
        $dataval = array();
        $payTypeAllows = array("alipay","wxpay");
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;  //商品的id
        $skuId = isset($data['skuId']) ? intval($data['skuId']) : 0;  //商品sku的id
        $num = isset($data['num']) ? intval($data['num']) : 1;  //购买数量
        $addressId = intval(isset($data['addressId']) ? $data['addressId'] : 0);  //地址的id
        $scareBuyTime = isset($data['scareBuyTime']) ? $data['scareBuyTime'] : ''; //抢购时间
        $payType = isset($data['payType']) ? $data['payType'] : ''; //支付方式
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $customerModel = get_load_model('customer');
        $customer = $customerModel->ModelVerifyVipTime($userid);
        if(empty($customer)){
            set_return_value(CUSTOMER_VIP_TIME_ERROR, '');
            return false;
        }
        if ($id == 0|| $skuId == 0 || $num == 0 || $addressId == 0 || empty($scareBuyTime) || empty($payType) || !in_array($payType, $payTypeAllows)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        date_default_timezone_set('PRC');
        $time = date('Y-m-d H:i:s');
        if(strtotime($time) >= strtotime($scareBuyTime)){
            set_return_value(BESPEAK_PRODUCT_TIME_ERROR,'');
            return false;
        }
        $addressModel = get_load_model('address');
        $address = $addressModel->ModelInfo($userid,$addressId);
        if(empty($address)){
            set_return_value(ADDRESS_NULL,'');
            return false;
        }
        $bespeakProductModel = get_load_model('reBespeakProduct');
        $bespeakProduct = $bespeakProductModel->ModelMiniInfo($id);
        if(empty($bespeakProduct)){
            set_return_value(PRODUCT_NULL_ERROR, '');
            return false;
        }
        $bespeakProductSkuModel = get_load_model('reBespeakProductSku');
        $sku = $bespeakProductSkuModel->ModelInfoById($skuId);
        if(empty($sku)){
            set_return_value(PRODUCT_SKU_ERROR, '');
            return false;
        }
        $bespeakOrderModel = get_load_model('bespeakOrder');
        $count = $bespeakOrderModel->ModelGetCountBySkuIdAndStatus($userid,$skuId);
        if($count > 5){
            set_return_value(BESPEAK_PRODUCT_SKU_COUNT_ERROR, '');
            return false;
        }
//        if($bespeakProduct['isReduceStock'] > 0){
//            if($sku['stock'] < $num){
//                set_return_value(PRODUCT_STOCK_NULL, '');
//                return false;
//            }
//        }
        $pro = array();
        $pro['productId'] = $id;
        $pro['skuId'] = $skuId;
        $pro['productName'] = $bespeakProduct['name'];
        $pro['model'] = $sku['model'];
        $pro['attr'] = $sku['attr'];
        $pro['productIcon'] = $bespeakProduct['goodIcon'];
        $pro['isReduceStock'] = $bespeakProduct['isReduceStock'];
        $pro['productPrice'] = $sku['price'];
        $pro['costPrice'] = $sku['costPrice'];
        $pro['num'] = $num;
        $postData = array();
        $postData['userId'] = $userid;
        $postData['pro'] = $pro;
        $postData['address'] = $address;
        $postData['distributionFee'] = $bespeakProduct['distributionFee'];
        $postData['scareBuyTime'] = $scareBuyTime;
        $configModel = get_load_model('config');
        $info = $configModel->BcModelInfo('postage');
        if(!empty($info)){
            if($info['cType'] == 2 || $info['cType'] == 3){
                $total = doubleval(bcmul($sku['price'],$num,2));
                if($total > $info['value']){
                    $postData['distributionFee'] = 0;
                }
            }
        }
        $ret = $bespeakOrderModel->ModelAdd($postData);
        if($ret !== false){
            $orderLogModel = get_load_model('orderLog');
            $orderLogModel->ModelAdd($ret,$userid,$type,'','waitforpay','下单成功');
            //todo
            //下单成功，调用支付
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function account($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;  //商品的id
        $skuId = isset($data['skuId']) ? intval($data['skuId']) : 0;  //商品sku的id
        $num = isset($data['num']) ? intval($data['num']) : 1;  //购买数量
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if($num == 0 || $num > 1){
            set_return_value(BESPEAK_PRODUCT_LIMIT_ERRPR, '');
            return false;
        }
        $customerModel = get_load_model('customer');
        $customer = $customerModel->ModelVerifyVipTime($userid);
        if(empty($customer)){
            set_return_value(CUSTOMER_VIP_TIME_ERROR, '');
            return false;
        }
        if ($id == 0|| $skuId == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $limitAmountModel = get_load_model('limitAmount');
        $amount = $limitAmountModel->ModelInfoByUserId($userid);
        if(!empty($amount)){
            if($amount['amount'] > LIMIT_AMOUNT){
                set_return_value(LIMIT_AMOUNT_ERROR, '');
                return false;
            }
        }else{
            $limitAmountModel->ModelInsert($userid);
        }
        $bespeakProductModel = get_load_model('reBespeakProduct');
        $bespeakProduct = $bespeakProductModel->ModelMiniInfo($id);
        if(empty($bespeakProduct)){
            set_return_value(PRODUCT_NULL_ERROR, '');
            return false;
        }
        $bespeakProductSkuModel = get_load_model('reBespeakProductSku');
        $sku = $bespeakProductSkuModel->ModelInfoByIdAndPid($skuId,$id);
        if(empty($sku)){
            set_return_value(PRODUCT_SKU_ERROR, '');
            return false;
        }
        $bespeakOrderModel = get_load_model('bespeakOrder');
        $count = $bespeakOrderModel->ModelGetCountBySkuIdAndStatus($userid,$skuId);
        if($count > 5){
            set_return_value(BESPEAK_PRODUCT_SKU_COUNT_ERROR, '');
            return false;
        }
        $pro = array();
        $pro['pid'] = $id;
        $pro['skuId'] = $skuId;
        $pro['productName'] = $bespeakProduct['name'];
        $pro['model'] = $sku['model'];
        $pro['attr'] = $sku['attr'];
        $pro['productIcon'] = $bespeakProduct['goodIcon'];
        $pro['productPrice'] = $sku['price'];
        $pro['costPrice'] = $sku['costPrice'];
        $pro['dutyFreePrice'] = $sku['dutyFreePrice'];
        $pro['num'] = $num;
        $dataval['pro'] = $pro;
        $dataval['distributionFee'] = $bespeakProduct['distributionFee'];
        $configModel = get_load_model('config');
        $info = $configModel->BcModelInfo('postage');
        if(!empty($info)){
            if($info['cType'] == 2 || $info['cType'] == 3){
                $total = doubleval(bcmul($sku['price'],$num,2));
                if($total > $info['value']){
                    $dataval['distributionFee'] = 0;
                }
            }
        }
        $dataval['total'] = doubleval(bcadd(bcmul($sku['price'],$num,2),$dataval['distributionFee'],2));
        if($dataval['total'] > LIMIT_AMOUNT || ($dataval['total'] + $amount['amount']) > LIMIT_AMOUNT){
            set_return_value(LIMIT_AMOUNT_ERROR, '');
            return false;
        }
        //若该用户存在默认地址，添加到订单
        $dataval['address'] = array();
        $addressModel = get_load_model('address');
        $address = $addressModel->ModelDefaultAddress($userid);
        if(!empty($address)){
            $dataval['address'] = $address;
        }

        //是否实名认证
        $dataval['isValidate'] = 0;
        $realNameModel = get_load_model('realName');
        $realNameInfo = $realNameModel->ModelInfoByUserId($userid);
        if(!empty($realNameInfo)){
            $dataval['isValidate'] = 1;
        }

        if(!empty($dataval)){
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }
}