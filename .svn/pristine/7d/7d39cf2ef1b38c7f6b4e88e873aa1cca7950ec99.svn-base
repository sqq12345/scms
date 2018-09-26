<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/28
 * Time: 12:48
 */
class Cart{

    public function add($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0; //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $pid = isset($data['pid']) ? intval($data['pid']) : 0;
        $skuId = isset($data['skuId']) ? intval($data['skuId']) : 0;
        $pType = isset($data['pType']) ? intval($data['pType']) : 1;    //商品的类型  1：现货 2：特价 3:秒杀  4:直邮
        $operation = isset($data['operation']) ? intval($data['operation']) : 1;    //运算符（1:加法，2：减法）
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($skuId == 0 || $pid == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('reProduct');
        $product = $model->ModelMiniInfo($pid);
        if(empty($product)){
            set_return_value(PRODUCT_NULL_ERROR, $dataval);
            return false;
        }
        $sku = array();
        $productModel = get_load_model('reProductSku');
        if($pType == 1 || $pType == 4){
            $sku = $productModel->ModelInfoByIdAndPid($skuId,$pid);
            if(empty($sku)){
                set_return_value(PRODUCT_SKU_ERROR, '');
                return false;
            }
        }elseif ($pType == 2){
            $specialModel = get_load_model('reSpecialProduct');
            $special = $specialModel->ModelFindBySkuIdAndTime($skuId);
            if(empty($special)){
                set_return_value(SPECIAL_PRODUCT_NULL_ERROR, '');
                return false;
            }
            $sku = $productModel->ModelInfoByIdAndPid($skuId,$pid);
            if(empty($sku)){
                set_return_value(PRODUCT_SKU_ERROR, '');
                return false;
            }
            //新加秒杀
        }elseif($pType == 3){
            $killproductModel = get_load_model('reSpecialProduct');
            $killproduct = $killproductModel->ModelSkillBySkuIdAndTime($skuId);
            if(empty($killproduct)){
                set_return_value(SPECIAL_PRODUCT_NULL_ERROR, '');
                return false;
            }
            $sku = $productModel->ModelInfoByIdAndPid($skuId,$pid);
            if(empty($sku)){
                set_return_value(PRODUCT_SKU_ERROR, '');
                return false;
            }
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
            return false;
        }
        $model = get_load_model('cart');
        $info = $model->ModelInfoByUserIdAndSkuId($userid,$skuId,$pType);
        if(empty($info)){
            if($operation ==2){
                set_return_value(CART_PRODUCT_NULL_ERROR, $dataval);
                return false;
            }
            //秒杀库存
            if($sku['stock'] < 1){
                set_return_value(PRODUCT_STOCK_NULL, $dataval);
                return false;
            }
            //秒杀库存
            if($pType == 3){
                if($killproduct['num'] < 1){
                    set_return_value(PRODUCT_STOCK_NULL, $dataval);
                    return false;
                }
            }
            $ret = $model->ModelAdd($userid,$pid,$skuId,$pType,1);
            if ($ret) {
                set_return_value(RESULT_SUCCESS, $dataval);
            } else {
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }else{
            if($operation == 2){
                if($info['num'] == 1){
                    $ret = $model->ModelDelete($info['id']);
                    if ($ret) {
                        set_return_value(RESULT_SUCCESS, $dataval);
                    } else {
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }else{
                    $set = array();
                    $num = $info['num'] - 1;
                    $set[] = " num = {$num}";
                    $ret = $model->ModelUpdate($set, $info['id'], $userid);
                    if ($ret) {
                        set_return_value(RESULT_SUCCESS, $dataval);
                    } else {
                        set_return_value(DEFEATED_ERROR, $dataval);
                    }
                }
            }else{
                $set = array();
                $num = $info['num'] + 1;
                //秒杀和预约只能买一件
                if($pType == 3){
                    if(1 < $num){
                        set_return_value(PRODUCT_SKU_LIMIT_ERROR, $dataval);
                        return false;
                    }
                }
                if($sku['stock'] < $num){
                    set_return_value(PRODUCT_STOCK_NULL, $dataval);
                    return false;
                }
                if($sku['limitNum'] < $num){
                    set_return_value(PRODUCT_SKU_LIMIT_ERROR, $dataval);
                    return false;
                }
                $set[] = " num = {$num}";
                $ret = $model->ModelUpdate($set, $info['id'], $userid);
                if ($ret) {
                    set_return_value(RESULT_SUCCESS, $dataval);
                } else {
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }
        }

    }

    public function getList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0; //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $model = get_load_model('cart');
        $dataval = $model->ModelGetList($userid);
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
        $items = isset($data['items']) ? $data['items'] : '';  //items
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
        if (empty($items)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if(sizeof($items) > 0){
            $orderModel = get_load_model('order');
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
                if($product['isReduceStock'] > 0){
                    if($val['pType'] == 1){
                        if($sku['stock'] < $val['num']){
                            set_return_value(PRODUCT_STOCK_NULL, '');
                            return false;
                        }
                    }elseif ($val['pType'] == 2){

                        if($sku['stock'] < $val['num']){
                            set_return_value(PRODUCT_STOCK_NULL, '');
                            return false;
                        }
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                        return false;
                    }
                }
                array_push($distributionFees,$product['distributionFee']);
                $times = array();
                if($sku['limitTime'] == 1){
                    $times = getTheWeek();
                }elseif ($sku['limitTime'] == 2){
                    $times = getTheMonth();
                }elseif ($sku['limitTime'] == 3){
                    $times = getTheSeason();
                }elseif ($sku['limitTime'] == 4){
                    $times = getTheYear();
                }else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                    return false;
                }
                $sum = $orderModel->ModelGetSumByTime($userid,$sku['id'],$times[0],$times[1]);
                $sum = $sum + $val['num'];
                if($sum > $sku['limitNum']){
                    set_return_value(PRODUCT_SKU_LIMIT_ERROR, '');
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
                $pro['productIcon'] = $product['goodIcon'];
                $pro['isReduceStock'] = $product['isReduceStock'];
                $pro['productPrice'] = $price;
                $pro['costPrice'] = doubleval($sku['costPrice']);
                $pro['num'] = $val['num'];
                array_push($list,$pro);
            }
            $postData = array();
            $postData['userId'] = $userid;
            $postData['items'] = $list;
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
            //若该用户存在默认地址，添加到订单
            $postData['address'] = array();
            $addressModel = get_load_model('address');
            $address = $addressModel->ModelDefaultAddress($userid);
            if(!empty($address)){
                $postData['address'] = $address;
            }
            $orderModel = get_load_model('order');
            $ret = $orderModel->ModelAdd($postData);
            if ($ret !== false) {
                $orderLogModel = get_load_model('orderLog');
                $orderLogModel->ModelAdd($ret,$userid,$type,'','waitforpay','下单成功');
                $dataval = $orderModel->ModelInfo($ret);
                set_return_value(RESULT_SUCCESS, $dataval);
            } else {
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function account1($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
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
        $cartModel = get_load_model('cart');
        $items = $cartModel->ModelListByUserId($userid);
        if (empty($items)) {
            set_return_value(CERT_NULL_ERROR, '');
            return false;
        }
        if(sizeof($items) > 0){
            $orderModel = get_load_model('order');
            $productModel = get_load_model('reProduct');
            $skuModel = get_load_model('reProductSku');
            $specialModel = get_load_model('reSpecialProduct');
            $list = array();
            $total = 0;
            $distributionFees = array();
            $notice = array();
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
                $stockError = array();
                if($product['isReduceStock'] > 0){
                    if($val['pType'] == 1){
                        if($sku['stock'] < $val['num']){
                            $stockRow = array();
                            $stockRow['productName'] = $product['name'];
                            $stockRow['model'] = $sku['model'];
                            $stockRow['attr'] = $sku['attr'];
                            $stockRow['num'] = intval($sku['stock']);
                            array_push($stockError,$stockRow);
                        }
                    }elseif ($val['pType'] == 2){
                        if($sku['stock'] < $val['num']){
                            $stockRow = array();
                            $stockRow['productName'] = $product['name'];
                            $stockRow['model'] = $sku['model'];
                            $stockRow['attr'] = $sku['attr'];
                            $stockRow['num'] = intval($sku['stock']);
                            array_push($stockError,$stockRow);
                        }
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                        return false;
                    }
                }
                array_push($distributionFees,$product['distributionFee']);
                $times = array();
                if($sku['limitTime'] == 1){
                    $times = getTheWeek();
                }elseif ($sku['limitTime'] == 2){
                    $times = getTheMonth();
                }elseif ($sku['limitTime'] == 3){
                    $times = getTheSeason();
                }elseif ($sku['limitTime'] == 4){
                    $times = getTheYear();
                }else{
//                    set_return_value(DEFEATED_ERROR, $dataval);
//                    return false;
                }
                $count = $orderModel->ModelGetSumByTime($userid,$sku['id'],$times[0],$times[1]);
                $sum = $count + $val['num'];
                if($sum > $sku['limitNum']){
                    $noticeRow = array();
                    $noticeRow['productName'] = $product['name'];
                    $noticeRow['model'] = $sku['model'];
                    $noticeRow['attr'] = $sku['attr'];
                    $noticeRow['num'] = intval($sku['limitNum'] - $count);
                    array_push($notice,$noticeRow);
                }
                $pro = array();
                $pro['id'] = $val['id'];
                $pro['pid'] = $val['pid'];
                $pro['skuId'] = $sku['id'];
                $pro['pType'] = $val['pType'];
                $pro['productName'] = $product['name'];
                $pro['model'] = $sku['model'];
                $pro['attr'] = $sku['attr'];
                $pro['productIcon'] = $product['goodIcon'];
                $pro['isReduceStock'] = $product['isReduceStock'];
                $pro['productPrice'] = $price;
                $pro['costPrice'] = $sku['costPrice'];
                $pro['num'] = $val['num'];
                $pro['total'] = doubleval(bcmul($price,$val['num'],2));
                array_push($list,$pro);
            }
//            $dataval['userId'] = $userid;
            if(!empty($notice)){
                set_return_value(LIMIT_ERROR, $notice);
                return false;
            }
            if(!empty($stockError)){
                set_return_value(LIMIT_ERROR, $stockError);
                return false;
            }
            $dataval['items'] = $list;
            $dataval['distributionFee'] = max($distributionFees);
            $configModel = get_load_model('config');
            $info = $configModel->BcModelInfo('postage');
            if(!empty($info)){
                if($info['cType'] == 1 || $info['cType'] == 3){
                    if($total > $info['value']){
                        $dataval['distributionFee'] = 0;
                    }
                }
            }
            $dataval['total'] = $total + $dataval['distributionFee'];
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
            $dataval['list'] = array();
            $dataval['num'] = 0;
            $data = $cartModel->ModelGetListByUserId($userid);
            if(!empty($data)){
                $dataval['list'] = $data['list'];
                $dataval['num'] = $data['num'];
            }

            //是否实名认证
            $dataval['isValidate'] = 0;
            $realNameModel = get_load_model('realName');
            $realNameInfo = $realNameModel->ModelInfoByUserId($userid);
            if(!empty($realNameInfo)){
                $dataval['isValidate'] = 1;
            }
            if (!empty($dataval)) {
                set_return_value(RESULT_SUCCESS, $dataval);
            } else {
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function cancel($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0; //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $pid = isset($data['pid']) ? intval($data['pid']) : 0; //商品的id
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($pid == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('cart');
        $ret = $model->ModelDeleteByPid($userid,$pid);
        if ($ret) {
            $dataval = $model->ModelGetList($userid);
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }


    public function account($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
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
        $cartModel = get_load_model('cart');
        $items = $cartModel->ModelListByUserId($userid);
        if (empty($items)) {
            set_return_value(CERT_NULL_ERROR, '');
            return false;
        }
        if(sizeof($items) > 0){
            $orderModel = get_load_model('order');
            $productModel = get_load_model('reProduct');
            $skuModel = get_load_model('reProductSku');
            $specialModel = get_load_model('reSpecialProduct');
            $list = array();
            $total = 0;
            $notice = array();
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
                if($val['pType'] == 1 || $val['pType'] == 4){
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
                }elseif($pType == 3){
                    $killproductModel = get_load_model('reSpecialProduct');
                    $killproduct = $killproductModel->ModelSkillBySkuIdAndTime($skuId);
                    if(empty($killproduct)){
                        set_return_value(SPECIAL_PRODUCT_NULL_ERROR, '');
                        return false;
                    }
                    $sku = $productModel->ModelInfoByIdAndPid($skuId,$pid);
                    if(empty($sku)){
                        set_return_value(PRODUCT_SKU_ERROR, '');
                        return false;
                    }
                    $price = doubleval($killproduct['price_limit']);
                    $total = bcadd(bcmul($killproduct['price_limit'],$val['num'],2),$total,2);
                }else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                    return false;
                }
                $stockError = array();
                if($product['isReduceStock'] > 0){
                    if($val['pType'] == 1){
                        if($sku['stock'] < $val['num']){
                            $stockRow = array();
                            $stockRow['productName'] = $product['name'];
                            $stockRow['model'] = $sku['model'];
                            $stockRow['attr'] = $sku['attr'];
                            $stockRow['num'] = intval($sku['stock']);
                            array_push($stockError,$stockRow);
                        }
                    }elseif ($val['pType'] == 2){
                        if($sku['stock'] < $val['num']){
                            $stockRow = array();
                            $stockRow['productName'] = $product['name'];
                            $stockRow['model'] = $sku['model'];
                            $stockRow['attr'] = $sku['attr'];
                            $stockRow['num'] = intval($sku['stock']);
                            array_push($stockError,$stockRow);
                        }
                    }else{
                        set_return_value(DEFEATED_ERROR, $dataval);
                        return false;
                    }
                }
                $times = array();
                if($sku['limitTime'] == 1){
                    $times = getTheWeek();
                }elseif ($sku['limitTime'] == 2){
                    $times = getTheMonth();
                }elseif ($sku['limitTime'] == 3){
                    $times = getTheSeason();
                }elseif ($sku['limitTime'] == 4){
                    $times = getTheYear();
                }else{
//                    set_return_value(DEFEATED_ERROR, $dataval);
//                    return false;
                }
                $count = $orderModel->ModelGetSumByTime($userid,$sku['id'],$times[0],$times[1]);
                $sum = $count + $val['num'];
                if($sum > $sku['limitNum']){
                    $noticeRow = array();
                    $noticeRow['productName'] = $product['name'];
                    $noticeRow['model'] = $sku['model'];
                    $noticeRow['attr'] = $sku['attr'];
                    $noticeRow['num'] = intval($sku['limitNum'] - $count);
                    array_push($notice,$noticeRow);
                }
                $pro = array();
                $pro['id'] = $val['id'];
                $pro['pid'] = $val['pid'];
                $pro['skuId'] = $sku['id'];
                $pro['pType'] = $val['pType'];
                $pro['productName'] = $product['name'];
                $pro['model'] = $sku['model'];
                $pro['attr'] = $sku['attr'];
                $pro['productIcon'] = $product['goodIcon'];
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
//            $dataval['userId'] = $userid;
            if(!empty($notice)){
                set_return_value(LIMIT_ERROR, $notice);
                return false;
            }
            if(!empty($stockError)){
                set_return_value(LIMIT_ERROR, $stockError);
                return false;
            }
            $dataval['items'] = $list;
            $dataval['distributionFee'] = DISTRIBUTION;
            $configModel = get_load_model('config');
            $info = $configModel->BcModelInfo('postage');
            if(!empty($info)){
                if($info['cType'] == 1 || $info['cType'] == 3){
                    if($total > $info['value']){
                        $dataval['distributionFee'] = 0;
                    }
                }
            }
            if($dataval['distributionFee'] == 0){
                //满足全场包邮
//                var_dump("满足全场包邮");
            }
            else{
                $dataval['distributionFee'] = 0;
//                var_dump("不满足全场包邮");
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
                        $dataval['distributionFee'] = $dataval['distributionFee'] + $fee;
                    }
                }else{

                }
            }
            $dataval['total'] = $total + $dataval['distributionFee'];
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
            $dataval['list'] = array();
            $dataval['num'] = 0;
            $data = $cartModel->ModelGetListByUserId($userid);
            if(!empty($data)){
                $dataval['list'] = $data['list'];
                $dataval['num'] = $data['num'];
            }

            //是否实名认证
            $dataval['isValidate'] = 0;
            $realNameModel = get_load_model('realName');
            $realNameInfo = $realNameModel->ModelInfoByUserId($userid);
            if(!empty($realNameInfo)){
                $dataval['isValidate'] = 1;
            }
            if (!empty($dataval)) {
                set_return_value(RESULT_SUCCESS, $dataval);
            } else {
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }
}