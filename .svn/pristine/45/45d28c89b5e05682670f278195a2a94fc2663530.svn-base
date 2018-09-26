<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/13
 * Time: 14:19
 */
class OrderModel{
    private $filepath = 'product_order';
    private $fields = 'id, order_sn, user_id, distribution_fee, province, city, area, address, code,receiver_name, receiver_phone, remark, order_status,user_visible, create_time, update_time, status';


    public function ModelOwner($orderSn, $userid) {
        $where = " AND order_sn = '{$orderSn}' AND user_id = {$userid}";
        $count = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        return $count > 0;
    }

    public function ModelAdd($data) {
        $orderSn = 'CO'.date('YmdHis') . rand(10000, 99999);

        $setarray = array();
        $setarray[] = " order_sn = '{$orderSn}'";
        $setarray[] = " user_id = {$data['userId']}";
        $setarray[] = " distribution_fee = {$data['distributionFee']}";
        $setarray[] = " order_status = 'waitforpay'";
        $setarray[] = " status = 1";
        $setarray[] = " create_time = now()";
        $setarray[] = " update_time = now()";
        if(isset($data['address']) && !empty($data['address'])){
            $setarray[] = " province = '{$data['address']['province']}'";
            $setarray[] = " city = '{$data['address']['city']}'";
            $setarray[] = " area = '{$data['address']['area']}'";
            $setarray[] = " address = '{$data['address']['address']}'";
            $setarray[] = " code = '{$data['address']['code']}'";
            $setarray[] = " receiver_name = '{$data['address']['receiverName']}'";
            $setarray[] = " receiver_phone = '{$data['address']['receiverPhone']}'";
        }
        $insertId = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $setarray);
        if ($insertId > 0) {
            $orderItems = get_load_model('orderItems');
            $skuModel = get_load_model('reProductSku');
            $cartModel = get_load_model('cart');
            foreach ($data['items'] as $val) {
                $orderItemsData = array(
                    'orderSn'       => $orderSn,
                    'productId'     => $val['pid'],
                    'skuId'         => $val['skuId'],
                    'pType'         => $val['pType'],
                    'productName'  => $val['productName'],
                    'model'         => $val['model'],
                    'attr'         => $val['attr'],
                    'productIcon'  => $val['productIcon'],
                    'productPrice' => $val['productPrice'],
                    'costPrice'    => $val['costPrice'],
                    'num'           => $val['num'],
                    'region'       => $val['region'],
                );
                $orderItems->ModelAdd($orderItemsData);
                //锁定指定商品库存
                if($val['isReduceStock'] > 0){
                    $skuModel->ModelUpdateStock($val['skuId'],$val['num'],2);
                    $skuModel->UpdateSales($val['skuId'],$val['num']);
                }
                //清除购物车
                $cartModel->ModelDelete($val['id']);
            }
            return $orderSn;
        } else {
            return false;
        }
    }

    public function ModelInfo($orderSn) {
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}'";
        $order = "id desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
        if (empty($row)) {
            return $dataval;
        }
        $orderItemsModel = get_load_model('orderItems');
        $dataval['id'] = intval($row['id']);
        $dataval['orderSn'] = $row['order_sn'];
        $dataval['orderStatus'] = $row['order_status'];
        $dataval['address'] = array();
        if(!empty($row['receiver_name'])){
            $dataval['address']['province'] = $row['province'];
            $dataval['address']['city'] = $row['city'];
            $dataval['address']['area'] = $row['area'];
            $dataval['address']['address'] = $row['address'];
            $dataval['address']['receiverName'] = $row['receiver_name'];
            $dataval['address']['receiverPhone'] = $row['receiver_phone'];
            $dataval['address']['code'] = $row['code'];
        }
        $dataval['items'] = $orderItemsModel->ModelList($row['order_sn']);
        $price = 0;
        if(!empty($dataval['items'])){
            foreach ($dataval['items'] as $k => $v) {
                $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
            }
        }
        $dataval['distributionFee'] = doubleval($row['distribution_fee']);
        $dataval['price'] = doubleval(bcadd($price,$row['distribution_fee'],2));
        return $dataval;
    }

    public function ModelGetInfoByOrderSn($orderSn){
        $dataval = array();
        $where = " and order_sn = '{$orderSn}'";
        $order = "id desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
        if (empty($row)) {
            return $dataval;
        }
        $orderItemsModel = get_load_model('orderItems');
        $dataval['orderSn'] = $row['order_sn'];
        $dataval['orderStatus'] = $row['order_status'];
        $dataval['items'] = $orderItemsModel->ModelListByOrderSn($row['order_sn']);
        $price = 0;
        if(!empty($dataval['items'])){
            $productModel = get_load_model('reProduct');
            foreach ($dataval['items'] as $k => $v) {
                $info = $productModel->ModelMiniInfo($v['pid']);
                $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
                $dataval['items'][$k]['total'] = doubleval(bcmul($v['productPrice'],$v['num'],2));
                $dataval['items'][$k]['isReduceStock '] = $info['isReduceStock'];
            }
        }
        $dataval['distributionFee'] = doubleval($row['distribution_fee']);
        $dataval['total'] = doubleval(bcadd($price,$row['distribution_fee'],2));
        $address = array();
        $address['receiverName'] = $row['receiver_name'];
        $address['receiverPhone'] = $row['receiver_phone'];
        $address['province'] = $row['province'];
        $address['city'] = $row['city'];
        $address['area'] = $row['area'];
        $address['address'] = $row['address'];
        $address['code'] = $row['code'];
        $dataval['address'] = $address;
        $dataval['remark'] = $row['remark'];

        $orderLogModel = get_load_model('orderLog');
        $list = $orderLogModel->ModelList($orderSn);
        $dataval['history'] = $list;
        return $dataval;
    }

    public function ModelList($offset, $max, $userid, $status, $region){
        $dataval = array();
        $where = " AND status = 1 AND user_id = {$userid} AND user_visible = 1";
        if(!empty($status)){
            $where.= " AND order_status = '{$status}'";
        }
        $orderby = " id DESC";
        $limit = " LIMIT {$offset}, {$max}";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        $orderItemsModel = get_load_model('orderItems');
        foreach ($list as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['orderSn'] = $val['order_sn'];
            $dataval[$key]['orderStatus'] = $val['order_status'];
            $items = $orderItemsModel->ModelList($val['order_sn'], $region);
            $price = 0;
            if(!empty($items)){
                foreach ($items as $k => $v) {
                    $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
                }
            }
            if(sizeof($items) > 1){
                $dataval[$key]['items'] = array_slice($items,0,1);
                $num = sizeof($items) - 1;
                $pro = array();
                $pro['id'] = "";
                $pro['productId'] = "";
                $pro['skuId'] = "";
                $pro['pType'] = "";
                $pro['productName'] = "还有其他".$num."件商品";
                $pro['brandName'] = "";
                $pro['model'] = "";
                $pro['attr'] = "";
                $pro['productIcon'] = "";
                $pro['productPrice'] = "";
                $pro['costPrice'] = "";
                $pro['num'] = "";
                array_push($dataval[$key]['items'],$pro);
                $dataval[$key]['price'] = doubleval(bcadd($price,$val['distribution_fee'],2));
                $dataval[$key]['createTime'] = $val['create_time'];
            }elseif (sizeof($items) == 1) {
                $dataval[$key]['items'] = $items;
                $dataval[$key]['price'] = doubleval(bcadd($price,$val['distribution_fee'],2));
                $dataval[$key]['createTime'] = $val['create_time'];
            }else{
                unset($dataval[$key]);
            }
        }
        return $dataval;
    }

    public function ModelCancel($orderSn,$remark,$userid){
        $pdo = mydqlpdo();
        $where = " AND order_sn = '{$orderSn}' AND user_id = {$userid} AND order_status = 'waitforpay' AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'cancel' ";
        $set[] = " remark = '{$remark}' ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        $sql = "SELECT * FROM order_items WHERE order_sn = '{$orderSn}' AND status = 1";
        $res = $pdo->query($sql);
        $orderitems = $res->fetchAll();
        if(!empty($orderitems)){
            foreach ($orderitems as $k => $v){
                $sql = "SELECT * FROM re_product WHERE id = {$v['product_id']}";
                $res = $pdo->query($sql);
                $row = $res->fetch();
                if($row['is_reduce_stock'] > 0){
                    if($v['sku_id'] > 0){
                        $sql = "UPDATE re_product_sku SET stock = stock + {$v['num']} WHERE id = {$v['sku_id']}";
                        $ret = $pdo->exec($sql);
                    }
                }
                if($v['product_type'] == 3){
                    $sql = "UPDATE product_second_kill SET num = num + {$v['num']} where skuid = {$v['sku_id']} and starttime < now() and endtime > now()";
                    $ret = $pdo->exec($sql);
                }
            }
        }
        return $ret;
    }

    public function ModelRefund($orderSn, $userid){
        $where = " AND order_sn = '{$orderSn}' AND user_id = {$userid} AND order_status = 'pendingDelivery' AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'refunding' ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function ModelUpdate($set, $orderSn){
        $where = "AND order_sn = '{$orderSn}' ";
        $orderrow = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($orderrow)) {
            return false;
        }
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelCancel($orderSn,$remark){
        $where = " AND order_sn = '{$orderSn}' AND status = 1 AND order_status = 'waitforpay'";
        $orderrow = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($orderrow)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'cancel' ";
        $set[] = " remark = '{$remark}' ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelList($orderSn,$orderStatus,$phone,$startTime,$endTime,$offset,$max){
        $dataval = array();
        $where = " AND status = 1";
        if(!empty($orderSn)){
            $where.= " AND order_sn LIKE '%{$orderSn}%'";
        }
        if(!empty($orderStatus)){
            $where.= " AND order_status = '{$orderStatus}'";
        }
        if(!empty($phone)){
            $customerModel = get_load_model('customer');
            $customerList = $customerModel->BcModelIdListByPhone($phone);
            $customerList = json_encode($customerList);
            $customerList = str_replace("[","(",$customerList);
            $customerList = str_replace("]",")",$customerList);
            $where.= " AND user_id in ".$customerList;
        }
        if(!empty($startTime)){
            $where.= " AND create_time >= '{$startTime}'";
        }
        if(!empty($endTime)){
            $where.= " AND create_time <= '{$endTime}'";
        }
        $orderby = " id DESC";
        $limit = " LIMIT {$offset}, {$max}";
        $orderlist = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($orderlist)) {
            return $dataval;
        }
        $orderItemsModel = get_load_model('orderItems');
        $customerModel = get_load_model('customer');
        foreach ($orderlist as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['userId'] = intval($val['user_id']);
            $customer = $customerModel->ModelInfo($val['user_id']);
            $dataval[$key]['phone'] = $customer['username'];
            $dataval[$key]['orderSn'] = $val['order_sn'];
            $dataval[$key]['orderStatus'] = "";
            switch ($val['order_status']){
                case "waitforpay":
                $dataval[$key]['orderStatus'] = "待支付";
                break;
                case "pendingDelivery":
                $dataval[$key]['orderStatus'] = "待发货";
                break;
                case "delivered":
                $dataval[$key]['orderStatus'] = "已发货";
                break;
                case "complete":
                $dataval[$key]['orderStatus'] = "已完成";
                break;
                case "cancel":
                $dataval[$key]['orderStatus'] = "已取消";
                break;
                case "refunding":
                $dataval[$key]['orderStatus'] = "申请退款";
                break;
                case "refundSuccess":
                $dataval[$key]['orderStatus'] = "退款成功";
                break;
                case "refundError":
                $dataval[$key]['orderStatus'] = "退款失败";
                break;
                default:
                $dataval[$key]['orderStatus'] = "";
            }
            $items = $orderItemsModel->BcModelList($val['order_sn']);
            $price = 0;
            if(!empty($items)){
                foreach ($items as $k => $v) {
                    $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
                }
            }
            $dataval[$key]['items'] = $items;
            $dataval[$key]['price'] = doubleval(bcadd($price,$val['distribution_fee'],2));
            $dataval[$key]['address'] = $val['receiver_name'].' '.$val['receiver_phone'].' '.$val['province'].$val['city'].$val['area'].$val['address'];
        }
        $data = array();
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        $data['total'] = intval($total);
        $data['list'] = $dataval;
        return $data;
    }

    public function BcModelInfo($orderSn){
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}'";
        $order = "id desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
        if (empty($row)) {
            return $dataval;
        }

        //订单基本信息
        $orderItemsModel = get_load_model('orderItems');
        $dataval['id'] = intval($row['id']);
        $dataval['orderSn'] = $row['order_sn'];
        $dataval['orderStatus'] = $row['order_status'];
        $dataval['createTime'] = $row['create_time'];
        $dataval['distributionFee'] = doubleval($row['distribution_fee']);

        //支付方式
        $payInfoModel = get_load_model('payInfo');
        $payInfo = $payInfoModel->BcModelInfoByOrderSn($orderSn);
        $dataval['payType'] = '';
        if(!empty($payInfo)){
            if($payInfo['pay_type'] == 'alipay'){
                $dataval['payType'] = '支付宝';
            }elseif ($payInfo['pay_type'] == 'wxpay'){
                $dataval['payType'] = '公众号支付';
            }elseif ($payInfo['pay_type'] == 'h5wxpay'){
                $dataval['payType'] = 'H5微信支付';
            }elseif ($payInfo['pay_type'] == 'qrcodepay'){
                $dataval['payType'] = '微信扫码支付';
            }else{

            }
        }

        //用户信息
        $customerModel = get_load_model('customer');
        $customer = $customerModel->ModelInfo($row['user_id']);
        $dataval['nickname'] = $customer['nickname'];
        $dataval['phone'] = $customer['username'];
        $dataval['email'] = $customer['email'];

        //商品信息
        $dataval['items'] = $orderItemsModel->BcModelList($row['order_sn']);
        if(!empty($dataval['items'])){
            $refundOrderItemsModel = get_load_model('refundOrderItems');
            foreach ($dataval['items'] as $k => $v) {
                $dataval['items'][$k]['total'] = doubleval(bcmul($v['productPrice'],$v['num'],2));
                $dataval['items'][$k]['refundNum'] =  $refundOrderItemsModel->BcModelGetSumByOrderSnAndPidAndSkuId($orderSn,$v['productId'],$v['skuId']);
            }
        }

        //用户地址信息
        $address = array();
        $address['receiverName'] = $row['receiver_name'];
        $address['receiverPhone'] = $row['receiver_phone'];
        $address['province'] = $row['province'];
        $address['city'] = $row['city'];
        $address['area'] = $row['area'];
        $address['address'] = $row['address'];
        $address['code'] = $row['code'];
        $dataval['address'] = $address;

        //物流信息
        $deliveryModel = get_load_model('orderDelivery');
        $orderDelivery = $deliveryModel->ModelInfo($orderSn);
        $dataval['company'] = "";
        $dataval['deliverySn'] = "";
//        $companyModel = get_load_model('company');
//        $dataval['companyList'] = $companyModel->ModelGetListByCode();
        $dataval['deliveryList'] = array();
        if(!empty($orderDelivery)){
            $dataval['deliverySn'] = $orderDelivery['deliverySn'];
            $dataval['company'] = $orderDelivery['company'];
//            $dataval['companyList'] = $companyModel->ModelGetListByCode($orderDelivery['code']);
            get_load_libraries('Mailtracking');
            $MT = new Mailtracking();
            $MT->setMailId($orderDelivery['deliverySn'], $orderDelivery['code']);
            if (!isset($_SERVER['HTTP_USER_AGENT'])
                || empty($_SERVER['HTTP_USER_AGENT'])
            ) {
                $_SERVER['HTTP_USER_AGENT']
            = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0;
            .NET CLR 2.0.50727; InfoPath.2; AskTbPTV/5.17.0.25589; Alexa Toolbar)';
        }
        $MT->curlPost();
        $info = $MT->getMailInfo();

        $info = json_decode($info, true);
        if ($info['status'] == 200) {
            $dataval['deliveryList'] = $info['data'];
        }
    }

        //订单历史
    $orderLogModel = get_load_model('orderLog');
    $list = $orderLogModel->ModelList($orderSn);
    $dataval['history'] = $list;
    return $dataval;
}

public function BcModelEditInfo($orderSn){
    $dataval = array();
    $where = " AND order_sn = '{$orderSn}'";
    $order = "id desc";
    $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
    if (empty($row)) {
        return $dataval;
    }

        //订单基本信息
    $orderItemsModel = get_load_model('orderItems');
    $dataval['id'] = intval($row['id']);
    $dataval['orderSn'] = $row['order_sn'];
    $dataval['orderStatus'] = $row['order_status'];
    $dataval['createTime'] = $row['create_time'];

        //用户信息
    $customerModel = get_load_model('customer');
    $customer = $customerModel->ModelInfo($row['user_id']);
    $dataval['nickname'] = $customer['nickname'];
    $dataval['phone'] = $customer['username'];
    $dataval['email'] = $customer['email'];

        //支付方式
    $payInfoModel = get_load_model('payInfo');
    $payInfo = $payInfoModel->BcModelInfoByOrderSn($orderSn);
    $dataval['payType'] = '';
    if(!empty($payInfo)){
        if($payInfo['pay_type'] == 'alipay'){
            $dataval['payType'] = '支付宝';
        }elseif ($payInfo['pay_type'] == 'wxpay'){
            $dataval['payType'] = '公众号支付';
        }elseif ($payInfo['pay_type'] == 'h5wxpay'){
            $dataval['payType'] = 'H5微信支付';
        }elseif ($payInfo['pay_type'] == 'qrcodepay'){
            $dataval['payType'] = '微信扫码支付';
        }else{

        }
    }

        //商品信息
    $dataval['items'] = $orderItemsModel->BcModelList($row['order_sn']);
    $price = 0;
    if(!empty($dataval['items'])){
        foreach ($dataval['items'] as $k => $v) {
            $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
            $dataval['items'][$k]['total'] = doubleval(bcmul($v['productPrice'],$v['num'],2));
        }
    }

        //商品总额，运费，订单总额
    $dataval['productTotal'] = doubleval($price);
    $dataval['distributionFee'] = doubleval($row['distribution_fee']);
    $dataval['orderTotal'] = doubleval(bcadd($price,$row['distribution_fee'],2));

        //用户地址信息
    $address = array();
    $address['receiverName'] = $row['receiver_name'];
    $address['receiverPhone'] = $row['receiver_phone'];
    $address['province'] = $row['province'];
    $address['city'] = $row['city'];
    $address['area'] = $row['area'];
    $address['address'] = $row['address'];
    $address['code'] = $row['code'];
    $dataval['address'] = $address;

    return $dataval;
}

public function BcModelMiNiInfo($orderSn){
    $dataval = array();
    $where = " AND order_sn = '{$orderSn}'";
    $order = "id desc";
    $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
    if (empty($row)) {
        return $dataval;
    }
    return $row;
}

public function BcModelUpdateAddress($set, $orderSn){
    $where = "AND order_sn = '{$orderSn}' AND order_status = 'pendingDelivery' ";
    $orderrow = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
    if (empty($orderrow)) {
        return false;
    }
    $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
    return $ret;
}

public function BcModelRefund($orderSn){
    $where = " AND order_sn = '{$orderSn}' AND order_status = 'pendingDelivery' AND status = 1";
    $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
    if (empty($row)) {
        return false;
    }
    $set = array();
    $set[] = " order_status = 'refunding' ";
    $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
    return $ret;
}

public function ModelGetSumByTime($userId,$skuId,$startTime,$endTime){
    $where = "AND user_id = {$userId} AND sku_id = {$skuId} AND product_order.order_sn = order_items.order_sn AND order_status in ('waitforpay','pendingDelivery','delivered','complete') AND 
    product_order.create_time BETWEEN '{$startTime}' AND '{$endTime}'";
    $sum = $GLOBALS['DB']->getSelectSum(ECHO_AQL_SWITCH,'num',array('product_order','order_items'),$where);
    return intval($sum);
}

public function BcModelStatusToDelivered($orderSn){
    $dataval = array();
    $where = " AND order_sn = '{$orderSn}' AND order_status = 'pendingDelivery'";
    $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
    if (empty($row)) {
        return $dataval;
    }
    $set = array();
    $set[] = " order_status = 'delivered' ";
    $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
    return $ret;
}

public function BcModelInfoByDelivered($orderSn){
    $dataval = array();
    $where = " AND order_sn = '{$orderSn}' AND order_status in ('delivered','complete') ";
    $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
    if (empty($row)) {
        return $dataval;
    }
    return $row;
}

public function BcModelInfoPendingDelivery($orderSn){
    $dataval = array();
    $where = " AND order_sn = '{$orderSn}' AND order_status = 'pendingDelivery'";
    $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
    if (empty($row)) {
        return $dataval;
    }
    return $row;
}

public function BcModelInfoToRefund($orderSn){
    $dataval = array();
    $where = " AND order_sn = '{$orderSn}'";
    $order = "id desc";
    $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
    if (empty($row)) {
        return $dataval;
    }

        //订单基本信息
    $orderItemsModel = get_load_model('orderItems');

    $items = $orderItemsModel->BcModelList($row['order_sn']);
    $price = 0;
    if(!empty($items)){
        foreach ($items as $k => $v) {
            $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
        }
    }
        //商品总额，运费，订单总额
    $dataval['productTotal'] = doubleval($price);
    $dataval['distributionFee'] = doubleval($row['distribution_fee']);
    $dataval['orderTotal'] = doubleval(bcadd($price,$row['distribution_fee'],2));

    return $dataval;
}

public function ModelDelete($orderSn,$userId){
    $where = " AND order_sn = '{$orderSn}' AND user_id = {$userId} AND order_status in ('waitforpay','cancel','complete','refundSuccess') AND status = 1";
    $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
    if (empty($row)) {
        return false;
    }
    $set = array();
    $set[] = " user_visible = 0 ";
    $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
    return $ret;
}

public function ModelCanRefundByOrderSn($orderSn,$userid){
    $dataval = array();
    $where = " AND order_sn = '{$orderSn}' AND user_id = {$userid} AND order_status = 'pendingDelivery' AND status = 1";
    $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
    if (empty($row)) {
        return $dataval;
    }
    return $row;
}

public function ModelCountByUserIdAndStatus($userId,$orderStatus){
    $where = " AND user_id = {$userId} AND status = 1 AND user_visible = 1";
    if(!empty($orderStatus)){
        $where.= " AND order_status = '{$orderStatus}'";
    }
    $count = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
    return intval($count);
}

public function ModelCanToPay($orderSn, $userid) {
    $dataval = array();
    $where = " AND order_sn = '{$orderSn}' AND user_id = {$userid} AND order_status = 'waitforpay'";
    $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
    if(empty($row)){
        return $dataval;
    }
    return $row;
}

public function ModelGetBaseInfoByOrderSn($orderSn){
    $dataval = array();
    $where = " and order_sn = '{$orderSn}'";
    $order = "id desc";
    $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
    if (empty($row)) {
        return $dataval;
    }
    $orderItemsModel = get_load_model('orderItems');
    $dataval['orderSn'] = $row['order_sn'];
    $dataval['orderStatus'] = $row['order_status'];
    $dataval['createTime'] = $row['create_time'];
    $dataval['items'] = $orderItemsModel->ModelListBaseByOrderSn($row['order_sn']);
    $price = 0;
    if(!empty($dataval['items'])){
        foreach ($dataval['items'] as $k => $v) {
            $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
            $dataval['items'][$k]['total'] = doubleval(bcmul($v['productPrice'],$v['num'],2));
        }
    }
    $dataval['total'] = doubleval(bcadd($price,$row['distribution_fee'],2));

    return $dataval;
}
}