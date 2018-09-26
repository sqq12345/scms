<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/16
 * Time: 13:17
 */
class Payment{

    public function doSubmit2($data){
        $dataval = array();
        $payTypeAllows = array("wxpay","alipay","h5wxpay","qrcodepay","xcxpay");
        $userid = isset($data['userid']) ? intval($data['userid']) : 0; //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');  //订单的编号ordersn
        $openId = inject_check(isset($data['openId']) ? $data['openId'] : '');  //openid
        $addressId = intval(isset($data['addressId']) ? $data['addressId'] : 0);  //地址的id
        $payType = inject_check(isset($data['payType']) ? strtolower($data['payType']) : '');
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
        if(empty($payType) || !in_array($payType, $payTypeAllows)){
            set_return_value(ORDER_PAY_TYPE_ERROR, '');
            return false;
        }

        $orderModel = get_load_model('order');
        $payInfoModel = get_load_model('payInfo');
        $orderLogModel = get_load_model('orderLog');
        if(!empty($orderSn)){
            $orderInfo = $orderModel->ModelCanToPay($orderSn, $userid);
            if (empty($orderInfo)) {
                set_return_value(ORDER_MESSAGE_NULL, '');
                return false;
            }
            $itemsModel = get_load_model('orderItems');
            $items = $itemsModel->ModelList($orderSn);
            if(empty($items)){
                set_return_value(ORDER_MESSAGE_NULL, '');
                return false;
            }
            $payInfo = $payInfoModel->ModelInfoByUserIdAndOrderSnAndPayType($userid,$orderSn,$payType);
            if(!empty($payInfo)){
                if($payType == 'alipay' && $payInfo['status'] == 'paying'){
                    //调用支付
                    get_load_libraries('alipay');
                    $pay = new alipay();
                    $payData = array(
                        "title" 	 => "时差买手",
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "product_code"  => "QUICK_WAP_WAY",
                        "fee"        => $payInfo['fee']/100,
                        "timeout_express"=>"1m"
                    );
                    $result = $pay->doPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($payType == 'wxpay' && $payInfo['status'] == 'paying' && !empty($openId)){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $payInfo['fee'],
                        "openId"  =>$openId,
                    );
                    $result = $pay->doJsApiPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($payType == 'h5wxpay' && $payInfo['status'] == 'paying'){
                    //微信支付
//                    var_dump("h5wxpay");
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $payInfo['fee'],
                    );
                    $result = $pay->doH5Pay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($payType == 'qrcodepay' && $payInfo['status'] == 'paying'){
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $payInfo['fee'],
                    );
                    $result = $pay->doQrCodePay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }
                elseif ($payType == 'xcxpay' && $payInfo['status'] == 'paying' && !empty($openId)){
                    //微信支付
                  get_cpalog('小程序：微信支付-订单内', $openId);
                  get_load_libraries('wxpay');
                  $pay = new wxpay();
                  $payData = array(
                    "body"       => $items[0]['productName'],
                    "orderNo"    => $orderSn,
                    "fee"        => $payInfo['fee']/100,
                    "openId"  =>$openId,
                );
                  $result = $pay->doXcxPay($payData);
                  $dataval['info'] = $result;
                  $dataval['orderSn'] = $orderSn;
                  if(!empty($dataval['info'])){
                    set_return_value(RESULT_SUCCESS, $dataval);
                }else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }
            else{
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }else{
                //新增
//                var_dump("不存在支付记录，新增");
            $fee = doubleval($orderInfo['distribution_fee']);
            foreach ($items as $val){
                $fee = bcadd(bcmul($val['productPrice'],$val['num'],2),$fee,2);
            }
            $return = $payInfoModel->ModelAdd($userid,'common',$orderSn,$payType,intval($fee*100));
            if($return !== false && $payType == 'alipay'){
                    //调用支付
                get_load_libraries('alipay');
                $pay = new alipay();
                $payData = array(
                    "title" 	 => "时差买手",
                    "body"       => $items[0]['productName'],
                    "orderNo"    => $orderSn,
                    "product_code"  => "QUICK_WAP_WAY",
                    "fee"        => $fee,
                    "timeout_express"=>"1m"
                );
                $result = $pay->doPay($payData);
                $dataval['info'] = $result;
                $dataval['orderSn'] = $orderSn;
                if(!empty($dataval['info'])){
                    set_return_value(RESULT_SUCCESS, $dataval);
                }else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }elseif ($return !== false && $payType == 'wxpay' && !empty($openId)){
                    //微信支付
                get_load_libraries('wxpay');
                $pay = new wxpay();
                $payData = array(
                    "body"       => $items[0]['productName'],
                    "orderNo"    => $orderSn,
                    "fee"        => $fee*100,
                    "openId"  =>$openId,
                );
                $result = $pay->doJsApiPay($payData);
                $dataval['info'] = $result;
                $dataval['orderSn'] = $orderSn;
                if(!empty($dataval['info'])){
                    set_return_value(RESULT_SUCCESS, $dataval);
                }else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }elseif ($return !== false && $payType == 'h5wxpay'){
                    //微信支付
                get_load_libraries('wxpay');
                $pay = new wxpay();
                $payData = array(
                    "body"       => $items[0]['productName'],
                    "orderNo"    => $orderSn,
                    "fee"        => $fee*100,
                );
                $result = $pay->doH5Pay($payData);
                $dataval['info'] = $result;
                $dataval['orderSn'] = $orderSn;
                if(!empty($dataval['info'])){
                    set_return_value(RESULT_SUCCESS, $dataval);
                }else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            } elseif ($return !== false && $payType == 'qrcodepay'){
                get_load_libraries('wxpay');
                $pay = new wxpay();
                $payData = array(
                    "body"       => $items[0]['productName'],
                    "orderNo"    => $orderSn,
                    "fee"        => $fee*100,
                );
                $result = $pay->doQrCodePay($payData);
                $dataval['info'] = $result;
                $dataval['orderSn'] = $orderSn;
                if(!empty($dataval['info'])){
                    set_return_value(RESULT_SUCCESS, $dataval);
                }else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }elseif ($return !== false && $payType == 'xcxpay' && !empty($openId)){
                    //微信支付
              get_cpalog('小程序：微信支付-', $openId);
              get_load_libraries('wxpay');
              $pay = new wxpay();
              $payData = array(
                "body"       => $items[0]['productName'],
                "orderNo"    => $orderSn,
                "fee"        => $fee,
                "openId"  =>$openId,
            );
              $result = $pay->doXcxPay($payData);
              $dataval['info'] = $result;
              $dataval['orderSn'] = $orderSn;
              if(!empty($dataval['info'])){
                set_return_value(RESULT_SUCCESS, $dataval);
            }else{
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }
        else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }
}
else{
    if($addressId == 0){
        set_return_value(ORDER_ADDRESS_NULL, '');
        return false;
    }
    $addressModel = get_load_model('address');
    $address = $addressModel->ModelInfo($userid,$addressId);
    if(empty($address)){
        set_return_value(ADDRESS_NULL, '');
        return false;
    }
    $cartModel = get_load_model('cart');
    $items = $cartModel->ModelListByUserId($userid);
    if (empty($items)) {
        set_return_value(CERT_NULL_ERROR, '');
        return false;
    }
    if(sizeof($items) > 0){
        $productModel = get_load_model('reProduct');
        $skuModel = get_load_model('reProductSku');
        $specialModel = get_load_model('reSpecialProduct');
        $list = array();
        $total = 0;
        $distributionFees = array();
        foreach ($items as $val){
            $product = $productModel->ModelMiniInfo($val['pid']);
            if(empty($product)){
                set_return_value(PRODUCT_NULL_ERROR, '');
                return false;
            }
            $sku = array();
            $specialProduct = array();
            $price = 0;
            if($val['pType'] == 1){
                $sku = $skuModel->ModelInfoByIdAndPid($val['skuId'],$val['pid']);
                if(empty($sku)){
                    set_return_value(PRODUCT_SKU_ERROR, '');
                    return false;
                }
                $price = $sku['price'];
                $total = bcadd(bcmul($sku['price'],$val['num'],2),$total,2);
            }elseif ($val['pType'] == 2){
                $specialProduct = $specialModel->ModelFindBySkuIdAndTime($val['skuId']);
                if(empty($specialProduct)){
                    set_return_value(SPECIAL_PRODUCT_NULL_ERROR, '');
                    return false;
                }
                $sku = $skuModel->ModelInfoById($specialProduct['sku_id']);
                if(empty($sku)){
                    set_return_value(PRODUCT_SKU_ERROR, '');
                    return false;
                }
                $price = doubleval($specialProduct['price']);
                $total = bcadd(bcmul($specialProduct['price'],$val['num'],2),$total,2);
            }else{
                set_return_value(DEFEATED_ERROR, $dataval);
                return false;
            }
            $pro = array();
            $pro['id'] = $val['id'];
            $pro['pid'] = $val['pid'];
            $pro['skuId'] = $sku['id'];
            $pro['pType'] = $val['pType'];
            $pro['productName'] = $product['name'];
            $pro['model'] = $sku['model'];
            $pro['attr'] = $sku['attr'];
            $pro['productIcon'] = $sku['img'];
            $pro['isReduceStock'] = $product['isReduceStock'];
            $pro['productPrice'] = $price;
            $pro['costPrice'] = $sku['costPrice'];
            $pro['num'] = $val['num'];
            $pro['total'] = doubleval(bcmul($price,$val['num'],2));
            array_push($list,$pro);
            array_push($distributionFees,$product['distributionFee']);
        }
    }
    $postData = array();
    $postData['userId'] = $userid;
    $postData['distributionFee'] = max($distributionFees);
    $configModel = get_load_model('config');
    $info = $configModel->BcModelInfo('postage');
    if(!empty($info)){
        if($info['cType'] == 1 || $info['cType'] == 3){
            if($total > $info['value']){
                $postData['distributionFee'] = 0;
            }
        }
    }
    $postData['items'] = $list;
    $postData['address'] = $address;
    $postData['total'] = $total + $postData['distributionFee'];

    $ret = $orderModel->ModelAdd($postData);
    if($ret !== false){
        $dataval['orderSn'] = $ret;
        $orderLogModel->ModelAdd($ret,$userid,$type,'','waitforpay','下单成功');

        $fee = bcadd($total,$postData['distributionFee'],2) * 100;
        $return = $payInfoModel->ModelAdd($userid,'common',$ret,$payType,$fee);
        if($return !== false && $payType == 'alipay'){
                    //调用支付
            get_load_libraries('alipay');
            $pay = new alipay();
            $payData = array(
                "title" 	 => "时差买手",
                "body"       => $postData['items'][0]['productName'],
                "orderNo"    => $ret,
                "product_code"  => "QUICK_WAP_WAY",
                "fee"        => $fee/100,
                "timeout_express"=>"1m"
            );
            $result = $pay->doPay($payData);
            $dataval['info'] = $result;
            $dataval['orderSn'] = $ret;
            if(!empty($dataval['info'])){
                set_return_value(RESULT_SUCCESS, $dataval);
            }else{
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }elseif ($return !== false && $payType == 'wxpay' && !empty($openId)){
                    //微信支付
            get_load_libraries('wxpay');
            $pay = new wxpay();
            $payData = array(
                "body"       => $postData['items'][0]['productName'],
                "orderNo"    => $ret,
                "fee"        => $fee,
                "openId"  =>$openId,
            );
            $result = $pay->doJsApiPay($payData);
            $dataval['info'] = $result;
            $dataval['orderSn'] = $ret;
            if(!empty($dataval['info'])){
                set_return_value(RESULT_SUCCESS, $dataval);
            }else{
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }elseif ($return !== false && $payType == 'h5wxpay'){
                    //微信支付
            get_load_libraries('wxpay');
            $pay = new wxpay();
            $payData = array(
                "body"       => $postData['items'][0]['productName'],
                "orderNo"    => $ret,
                "fee"        => $fee,
            );
            $result = $pay->doH5Pay($payData);
            $dataval['info'] = $result;
            $dataval['orderSn'] = $ret;
            if(!empty($dataval['info'])){
                set_return_value(RESULT_SUCCESS, $dataval);
            }else{
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }elseif ($return !== false && $payType == 'qrcodepay'){
            get_load_libraries('wxpay');
            $pay = new wxpay();
            $payData = array(
                "body"       => $postData['items'][0]['productName'],
                "orderNo"    => $ret,
                "fee"        => $fee,
            );
            $result = $pay->doQrCodePay($payData);
            $dataval['info'] = $result;
            $dataval['orderSn'] = $ret;
            if(!empty($dataval['info'])){
                set_return_value(RESULT_SUCCESS, $dataval);
            }else{
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }elseif ($return !== false && $payType == 'xcxpay' && !empty($openId)){
                    //微信支付
            get_load_libraries('wxpay');
            $pay = new wxpay();
            $payData = array(
                "body"       => $postData['items'][0]['productName'],
                "orderNo"    => $ret,
                "fee"        => $fee,
                "openId"  =>$openId,
            );
            $result = $pay->doXcxPay($payData);
            $dataval['info'] = $result;
            $dataval['orderSn'] = $ret;
            if(!empty($dataval['info'])){
                set_return_value(RESULT_SUCCESS, $dataval);
            }else{
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }
        else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    } else {
        set_return_value(DEFEATED_ERROR, $dataval);
    }
}
}

public function doBespeakSubmit($data){
    $dataval = array();
    $payTypeAllows = array("wxpay", "alipay","h5wxpay","qrcodepay","xcxpay");
        $userid = isset($data['userid']) ? intval($data['userid']) : 0; //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');  //订单的编号ordersn
        $openId = inject_check(isset($data['openId']) ? $data['openId'] : '');  //openid
        $id = isset($data['id']) ? intval($data['id']) : 0;  //商品的id
        $skuId = isset($data['skuId']) ? intval($data['skuId']) : 0;  //商品sku的id
        $num = isset($data['num']) ? intval($data['num']) : 1;  //购买数量
        $addressId = intval(isset($data['addressId']) ? $data['addressId'] : 0);  //地址的id
        $payType = inject_check(isset($data['payType']) ? strtolower($data['payType']) : '');
        $scareBuyTime = isset($data['scareBuyTime']) ? $data['scareBuyTime'] : ''; //抢购时间
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $customerModel = get_load_model('customer');
        $customer = $customerModel->ModelVerifyVipTime($userid);
        if (empty($customer)) {
            set_return_value(CUSTOMER_VIP_TIME_ERROR, '');
            return false;
        }
        if (empty($payType) || !in_array($payType, $payTypeAllows)) {
            set_return_value(ORDER_PAY_TYPE_ERROR, '');
            return false;
        }
        $orderModel = get_load_model('bespeakOrder');
        $orderLogModel = get_load_model('orderLog');
        $payInfoModel = get_load_model('payInfo');
        $bespeakProductModel = get_load_model('reBespeakProduct');
        $bespeakProductSkuModel = get_load_model('reBespeakProductSku');
        if (!empty($orderSn)) {
            $orderInfo = $orderModel->ModelCanToPay($orderSn, $userid);
            if (empty($orderInfo)) {
                set_return_value(ORDER_MESSAGE_NULL, '');
                return false;
            }
            $itemsModel = get_load_model('bespeakOrderItems');
            $items = $itemsModel->ModelList($orderSn);
            if (empty($items)) {
                set_return_value(ORDER_MESSAGE_NULL, '');
                return false;
            }
            $payInfo = $payInfoModel->ModelInfoByUserIdAndOrderSnAndPayType($userid, $orderSn, $payType);
            if (!empty($payInfo)) {
                if ($payType == 'alipay' && $payInfo['status'] == 'paying') {
                    //调用支付
                    get_load_libraries('alipay');
                    $pay = new alipay();
                    $payData = array(
                        "title" => "时差买手",
                        "body" => $items[0]['productName'],
                        "orderNo" => $orderSn,
                        "product_code" => "QUICK_WAP_WAY",
                        "fee" => $payInfo['fee'] / 100,
                        "timeout_express" => "1m"
                    );
                    $result = $pay->doPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                } elseif ($payType == 'wxpay' && $payInfo['status'] == 'paying' && !empty($openId)){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee" => $payInfo['fee'],
                        "openId" =>$openId,
                    );
                    $result = $pay->doJsApiPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($payType == 'h5wxpay' && $payInfo['status'] == 'paying'){
                    //微信支付
//                    var_dump("h5wxpay");
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee" => $payInfo['fee'],
                    );
                    $result = $pay->doH5Pay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($payType == 'qrcodepay' && $payInfo['status'] == 'paying'){
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee" => $payInfo['fee'],
                    );
                    $result = $pay->doQrCodePay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($payType == 'xcxpay' && $payInfo['status'] == 'paying' && !empty($openId)){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee" => $payInfo['fee'],
                        "openId" =>$openId,
                    );
                    $result = $pay->doXcxPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }
                else {
                    set_return_value(DEFEATED_ERROR, $dataval);
                    return false;
                }
            } else {
                $fee = doubleval($orderInfo['distribution_fee']);
                foreach ($items as $val) {
                    $fee = bcadd(bcmul($val['productPrice'], $val['num'], 2), $fee, 2);
                }
                $return = $payInfoModel->ModelAdd($userid, 'bespeak', $orderSn, $payType, intval($fee * 100));
                if ($return !== false && $payType == 'alipay') {
                    //调用支付
                    get_load_libraries('alipay');
                    $pay = new alipay();
                    $payData = array(
                        "title" => "时差买手",
                        "body" => $items[0]['productName'],
                        "orderNo" => $orderSn,
                        "product_code" => "QUICK_WAP_WAY",
                        "fee" => $fee,
                        "timeout_express" => "1m"
                    );
                    $result = $pay->doPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                } elseif ($return !== false && $payType == 'wxpay' && !empty($openId)){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $fee*100,
                        "openId" =>$openId,
                    );
                    $result = $pay->doJsApiPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($return !== false && $payType == 'h5wxpay'){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $fee*100,
                    );
                    $result = $pay->doH5Pay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($return !== false && $payType == 'qrcodepay'){
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $fee*100,
                    );
                    $result = $pay->doQrCodePay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }
                else {
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }
        }
        else {
            if ($num == 0 || $num > 1) {
                set_return_value(BESPEAK_PRODUCT_LIMIT_ERRPR, '');
                return false;
            }
            if ($id == 0 || $skuId == 0 || empty($scareBuyTime)) {
                set_return_value(WILL_FIELD_NULL, '');
                return false;
            }
            date_default_timezone_set('PRC');
            $time = date('Y-m-d H:i:s');
            if (strtotime($time) >= strtotime($scareBuyTime)) {
                set_return_value(BESPEAK_PRODUCT_TIME_ERROR, '');
                return false;
            }
            if ($addressId == 0) {
                set_return_value(ORDER_ADDRESS_NULL, '');
                return false;
            }
            $addressModel = get_load_model('address');
            $address = $addressModel->ModelInfo($userid, $addressId);
            if (empty($address)) {
                set_return_value(ADDRESS_NULL, '');
                return false;
            }
            $bespeakProduct = $bespeakProductModel->ModelMiniInfo($id);
            if (empty($bespeakProduct)) {
                set_return_value(PRODUCT_NULL_ERROR, '');
                return false;
            }
            $sku = $bespeakProductSkuModel->ModelInfoByIdAndPid($skuId, $id);
            if (empty($sku)) {
                set_return_value(PRODUCT_SKU_ERROR, '');
                return false;
            }
            $count = $orderModel->ModelGetCountBySkuIdAndStatus($userid, $skuId);
            if ($count > 5) {
                set_return_value(BESPEAK_PRODUCT_SKU_COUNT_ERROR, '');
                return false;
            }
            $pro = array();
            $pro['pid'] = $id;
            $pro['skuId'] = $skuId;
            $pro['productName'] = $bespeakProduct['name'];
            $pro['model'] = $sku['model'];
            $pro['attr'] = $sku['attr'];
            $pro['productIcon'] = $sku['img'];
            $pro['productPrice'] = $sku['price'];
            $pro['costPrice'] = $sku['costPrice'];
            $pro['dutyFreePrice'] = $sku['dutyFreePrice'];
            $pro['num'] = $num;
            $postData = array();
            $postData['userId'] = $userid;
            $postData['distributionFee'] = $bespeakProduct['distributionFee'];;
            $postData['pro'] = $pro;
            $postData['address'] = $address;
            $postData['scareBuyTime'] = $scareBuyTime;
            $configModel = get_load_model('config');
            $info = $configModel->BcModelInfo('postage');
            if (!empty($info)) {
                if ($info['cType'] == 2 || $info['cType'] == 3) {
                    $total = doubleval(bcmul($sku['price'], $num, 2));
                    if ($total > $info['value']) {
                        $postData['distributionFee'] = 0;
                    }
                }
            }
            $ret = $orderModel->ModelAdd($postData);
            if ($ret !== false) {
                $dataval['orderSn'] = $ret;
                $orderLogModel->ModelAdd($ret, $userid, $type, '', 'waitforpay', '下单成功');
                $total = doubleval(bcadd(bcmul($pro['productPrice'], $pro['num'], 2), $postData['distributionFee'], 2));
                $fee = intval($total * 100);
                $return = $payInfoModel->ModelAdd($userid, 'bespeak', $ret, $payType, $fee);
                if ($return && $payType == 'alipay') {
                    //调用支付
                    get_load_libraries('alipay');
                    $pay = new alipay();
                    $postData = array(
                        "title" => "时差买手",
                        "body" => $pro['productName'],
                        "orderNo" => $ret,
                        "product_code" => "QUICK_WAP_WAY",
                        "fee" => $total,
                        "timeout_express" => "1m"
                    );
                    $result = $pay->doPay($postData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $ret;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }
                elseif ($return !== false && $payType == 'wxpay' && !empty($openId)){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $pro['productName'],
                        "orderNo"    => $ret,
                        "fee" => $total*100,
                        "openId" =>$openId,
                    );
                    $result = $pay->doJsApiPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $ret;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($return !== false && $payType == 'h5wxpay'){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $pro['productName'],
                        "orderNo"    => $ret,
                        "fee" => $total*100,
                    );
                    $result = $pay->doH5Pay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $ret;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($return !== false && $payType == 'qrcodepay'){
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $pro['productName'],
                        "orderNo"    => $ret,
                        "fee" => $total*100,
                    );
                    $result = $pay->doQrCodePay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $ret;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($return !== false && $payType == 'xcxpay' && !empty($openId)){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $pro['productName'],
                        "orderNo"    => $ret,
                        "fee" => $total*100,
                        "openId" =>$openId,
                    );
                    $result = $pay->doXcxPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $ret;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }
                else {
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }
        }
    }

    public function getInfo($data){
        $dataval = array();
        $payTypeAllows = array("wxpay","alipay","h5wxpay","qrcodepay");
        $userid = isset($data['userid']) ? intval($data['userid']) : 0; //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');  //订单的编号ordersn
        $payType = inject_check(isset($data['payType']) ? strtolower($data['payType']) : '');
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if(empty($payType) || !in_array($payType, $payTypeAllows)){
            set_return_value(ORDER_PAY_TYPE_ERROR, '');
            return false;
        }
        $payInfoModel = get_load_model('payInfo');
        $payInfo = $payInfoModel->ModelInfo($userid,$orderSn,$payType);
        $ret = 0;
        if(!empty($payInfo)){
            $ret = 1;
        }
        $dataval['data'] = $ret;
        set_return_value(RESULT_SUCCESS, $dataval);
    }

    public function doSubmit($data){
        $dataval = array();
        $payTypeAllows = array("wxpay","alipay","h5wxpay","qrcodepay",'xcxpay');
        $userid = isset($data['userid']) ? intval($data['userid']) : 0; //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');  //订单的编号ordersn
        $openId = inject_check(isset($data['openId']) ? $data['openId'] : '');  //openid
        $addressId = intval(isset($data['addressId']) ? $data['addressId'] : 0);  //地址的id
        $payType = inject_check(isset($data['payType']) ? strtolower($data['payType']) : '');
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
        if(empty($payType) || !in_array($payType, $payTypeAllows)){
            set_return_value(ORDER_PAY_TYPE_ERROR, '');
            return false;
        }

        $orderModel = get_load_model('order');
        $payInfoModel = get_load_model('payInfo');
        $orderLogModel = get_load_model('orderLog');
        if(!empty($orderSn)){
            $orderInfo = $orderModel->ModelCanToPay($orderSn, $userid);
            if (empty($orderInfo)) {
                set_return_value(ORDER_MESSAGE_NULL, '');
                return false;
            }
            $itemsModel = get_load_model('orderItems');
            $items = $itemsModel->ModelList($orderSn);
            if(empty($items)){
                set_return_value(ORDER_MESSAGE_NULL, '');
                return false;
            }
            $payInfo = $payInfoModel->ModelInfoByUserIdAndOrderSnAndPayType($userid,$orderSn,$payType);
            if(!empty($payInfo)){
                if($payType == 'alipay' && $payInfo['status'] == 'paying'){
                    //调用支付
                    get_load_libraries('alipay');
                    $pay = new alipay();
                    $payData = array(
                        "title" 	 => "时差买手",
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "product_code"  => "QUICK_WAP_WAY",
                        "fee"        => $payInfo['fee']/100,
                        "timeout_express"=>"1m"
                    );
                    $result = $pay->doPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($payType == 'wxpay' && $payInfo['status'] == 'paying' && !empty($openId)){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $payInfo['fee'],
                        "openId"  =>$openId,
                    );
                    $result = $pay->doJsApiPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($payType == 'h5wxpay' && $payInfo['status'] == 'paying'){
                    //微信支付
//                    var_dump("h5wxpay");
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $payInfo['fee'],
                    );
                    $result = $pay->doH5Pay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($payType == 'qrcodepay' && $payInfo['status'] == 'paying'){
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $payInfo['fee'],
                    );
                    $result = $pay->doQrCodePay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($payType == 'xcxpay' && $payInfo['status'] == 'paying' && !empty($openId)){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $payInfo['fee'],
                        "openId"  =>$openId,
                    );
                    $result = $pay->doXcxPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }
                else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }else{
                //新增
//                var_dump("不存在支付记录，新增");
                $fee = doubleval($orderInfo['distribution_fee']);
                foreach ($items as $val){
                    $fee = bcadd(bcmul($val['productPrice'],$val['num'],2),$fee,2);
                }
                $return = $payInfoModel->ModelAdd($userid,'common',$orderSn,$payType,intval($fee*100));
                if($return !== false && $payType == 'alipay'){
                    //调用支付
                    get_load_libraries('alipay');
                    $pay = new alipay();
                    $payData = array(
                        "title" 	 => "时差买手",
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "product_code"  => "QUICK_WAP_WAY",
                        "fee"        => $fee,
                        "timeout_express"=>"1m"
                    );
                    $result = $pay->doPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($return !== false && $payType == 'wxpay' && !empty($openId)){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $fee*100,
                        "openId"  =>$openId,
                    );
                    $result = $pay->doJsApiPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($return !== false && $payType == 'h5wxpay'){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $fee*100,
                    );
                    $result = $pay->doH5Pay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                } elseif ($return !== false && $payType == 'qrcodepay'){
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $fee*100,
                    );
                    $result = $pay->doQrCodePay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($return !== false && $payType == 'xcxpay' && !empty($openId)){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $items[0]['productName'],
                        "orderNo"    => $orderSn,
                        "fee"        => $fee*100,
                        "openId"  =>$openId,
                    );
                    $result = $pay->doXcxPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $orderSn;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }
                else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }
        }
        else{
            if($addressId == 0){
                set_return_value(ORDER_ADDRESS_NULL, '');
                return false;
            }
            $addressModel = get_load_model('address');
            $address = $addressModel->ModelInfo($userid,$addressId);
            if(empty($address)){
                set_return_value(ADDRESS_NULL, '');
                return false;
            }
            $cartModel = get_load_model('cart');
            $items = $cartModel->ModelListByUserId($userid);
            if (empty($items)) {
                set_return_value(CERT_NULL_ERROR, '');
                return false;
            }
            if(sizeof($items) > 0){
                $productModel = get_load_model('reProduct');
                $skuModel = get_load_model('reProductSku');
                $specialModel = get_load_model('reSpecialProduct');
                $list = array();
                $total = 0;
                $templateArray = array();
                foreach ($items as $val){
                    $product = $productModel->ModelMiniInfo($val['pid']);
                    if(empty($product)){
                        set_return_value(PRODUCT_NULL_ERROR, '');
                        return false;
                    }
                    $sku = array();
                    $specialProduct = array();
                    $price = 0;
                    if($val['pType'] == 1){
                        $sku = $skuModel->ModelInfoByIdAndPid($val['skuId'],$val['pid']);
                        if(empty($sku)){
                            set_return_value(PRODUCT_SKU_ERROR, '');
                            return false;
                        }
                        $price = $sku['price'];
                        $total = bcadd(bcmul($sku['price'],$val['num'],2),$total,2);
                    }elseif ($val['pType'] == 2){
                        $specialProduct = $specialModel->ModelFindBySkuIdAndTime($val['skuId']);
                        if(empty($specialProduct)){
                            set_return_value(SPECIAL_PRODUCT_NULL_ERROR, '');
                            return false;
                        }
                        $sku = $skuModel->ModelInfoById($specialProduct['sku_id']);
                        if(empty($sku)){
                            set_return_value(PRODUCT_SKU_ERROR, '');
                            return false;
                        }
                        $price = doubleval($specialProduct['price']);
                        $total = bcadd(bcmul($specialProduct['price'],$val['num'],2),$total,2);
                    }elseif ($val['pType'] == 3) {
                        $skillProduct = $specialModel->ModelSkillBySkuIdAndTime($val['skuId']);
                        if(empty($skillProduct)){
                            set_return_value(SPECIAL_PRODUCT_NULL_ERROR, '');
                            return false;
                        }
                        $sku = $skuModel->ModelInfoById($skillProduct['skuid']);
                        if(empty($sku)){
                            set_return_value(PRODUCT_SKU_ERROR, '');
                            return false;
                        }
                        $price = doubleval($skillProduct['price_limit']);
                        $total = bcadd(bcmul($skillProduct['price_limit'],$val['num'],2),$total,2);
                        $specialModel->ModelSkillUpdate($skillProduct['id'], $val['num'], 1);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                        return false;
                    }
                    $pro = array();
                    $pro['id'] = $val['id'];
                    $pro['pid'] = $val['pid'];
                    $pro['skuId'] = $sku['id'];
                    $pro['pType'] = $val['pType'];
                    $pro['productName'] = $product['name'];
                    $pro['model'] = $sku['model'];
                    $pro['attr'] = $sku['attr'];
                    $pro['productIcon'] = $sku['img'];
                    $pro['isReduceStock'] = $product['isReduceStock'];
                    $pro['productPrice'] = $price;
                    $pro['costPrice'] = $sku['costPrice'];
                    $pro['num'] = $val['num'];
                    $pro['total'] = doubleval(bcmul($price,$val['num'],2));
                    array_push($list,$pro);
                    $row = array(
                        'tid'=>$sku['fareId'],
                        'pid'=>$val['pid'],
                        'skuId'=>$val['skuId'],
                        'num'=>$val['num'],
                        'weight'=>$sku['weight'],
                    );
                    array_push($templateArray,$row);
                }
            }
            $postData = array();
            $postData['userId'] = $userid;
            $postData['distributionFee'] = DISTRIBUTION;
            $configModel = get_load_model('config');
            $info = $configModel->BcModelInfo('postage');
            if(!empty($info)){
                if($info['cType'] == 1 || $info['cType'] == 3){
                    if($total > $info['value']){
                        $postData['distributionFee'] = 0;
                    }
                }
            }
            if($postData['distributionFee'] == 0){
                //满足全场包邮
                //var_dump("满足全场包邮");
            }
            else{
                $postData['distributionFee'] = 0;
                //var_dump("不满足全场包邮");
                if(!empty($templateArray) && sizeof($templateArray) > 0){
                    $fareTemplateModel = get_load_model('fareTemplate');
                    $carryModeModel = get_load_model('carryMode');
                    $sortData = array();
                    foreach ($templateArray as $key => $value){
                        $sortData[$value['tid']][] = $value;
                    }
                    ksort($sortData);
                    foreach ($sortData as $key => $value){
                        $template = $fareTemplateModel->ModelInfo($key);
                        $fee = 0;
                        if(empty($template)){
                            set_return_value(TEMPLATE_NULL_ERROR, '');
                            return false;
                        }
                        if($template['isInclPostage'] == 1){
                            //自定义运费
                            $carryMode = $carryModeModel->ModelInfoByFareId($key);
                            if(empty($carryMode)){
                                set_return_value(TEMPLATE_NULL_ERROR, '');
                                return false;
                            }
                            if($template['valuationModel'] == 1){
                                //按照件数计费
                                $num = 0;
                                foreach ($value as $v){
                                    $num = $num + $v['num'];
                                }
                                if($num <= $carryMode['firstPiece']){
                                    $fee = $carryMode['firstAmount'];
                                }else{
                                    $fee = $carryMode['firstAmount'] + ceil(($num-$carryMode['firstPiece'])/$carryMode['secondPiece']) * $carryMode['secondAmount'];
                                }

                            }elseif ($template['valuationModel'] == 2){
                                //按照重量计费
                                $weight = 0;
                                foreach ($value as $v){
                                    $weight = $v['num'] * $v['weight'] + $weight;
                                }
                                if($weight <= $carryMode['firstWeight']){
                                    $fee = $carryMode['firstAmount'];
                                }else{
                                    $fee = $carryMode['firstAmount'] + ceil(($weight-$carryMode['firstWeight'])/$carryMode['secondWeight']) * $carryMode['secondAmount'];
                                }
                            }else{

                            }
                        }elseif($template['isInclPostage'] == 2 || $template['isInclPostage'] == 3){
                            //卖家包邮，货到付款，运费为0
                            $fee = 0;
                        }else{

                        }
                        $postData['distributionFee'] = $postData['distributionFee'] + $fee;
                    }
                }else{

                }
                //var_dump($postData);
            }
            $postData['items'] = $list;
            $postData['address'] = $address;
            $postData['total'] = $total + $postData['distributionFee'];

            $ret = $orderModel->ModelAdd($postData);
            if($ret !== false){

                $orderLogModel->ModelAdd($ret,$userid,$type,'','waitforpay','下单成功');

                $fee = bcadd($total,$postData['distributionFee'],2) * 100;
                $return = $payInfoModel->ModelAdd($userid,'common',$ret,$payType,$fee);
                if($return !== false && $payType == 'alipay'){
                    //调用支付
                    get_load_libraries('alipay');
                    $pay = new alipay();
                    $payData = array(
                        "title" 	 => "时差买手",
                        "body"       => $postData['items'][0]['productName'],
                        "orderNo"    => $ret,
                        "product_code"  => "QUICK_WAP_WAY",
                        "fee"        => $fee/100,
                        "timeout_express"=>"1m"
                    );
                    $result = $pay->doPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $ret;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($return !== false && $payType == 'wxpay' && !empty($openId)){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $postData['items'][0]['productName'],
                        "orderNo"    => $ret,
                        "fee"        => $fee,
                        "openId"  =>$openId,
                    );
                    $result = $pay->doJsApiPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $ret;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($return !== false && $payType == 'h5wxpay'){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $postData['items'][0]['productName'],
                        "orderNo"    => $ret,
                        "fee"        => $fee,
                    );
                    $result = $pay->doH5Pay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $ret;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($return !== false && $payType == 'qrcodepay'){
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $postData['items'][0]['productName'],
                        "orderNo"    => $ret,
                        "fee"        => $fee,
                    );
                    $result = $pay->doQrCodePay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $ret;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }elseif ($return !== false && $payType == 'xcxpay' && !empty($openId)){
                    //微信支付
                    get_load_libraries('wxpay');
                    $pay = new wxpay();
                    $payData = array(
                        "body"       => $postData['items'][0]['productName'],
                        "orderNo"    => $ret,
                        "fee"        => $fee,
                        "openId"  =>$openId,
                    );
                    $result = $pay->doXcxPay($payData);
                    $dataval['info'] = $result;
                    $dataval['orderSn'] = $ret;
                    if(!empty($dataval['info'])){
                        set_return_value(RESULT_SUCCESS, $dataval);
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }
                else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            } else {
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }
    }
}