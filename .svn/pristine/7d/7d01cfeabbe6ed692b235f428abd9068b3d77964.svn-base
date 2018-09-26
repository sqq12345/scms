<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/17
 * Time: 15:40
 */
class BespeakOrderModel{

    private $filepath = 'bespeak_order';
    private $fields = 'id, order_sn, user_id, distribution_fee, province, city, area, address, code,receiver_name, receiver_phone, remark, order_status,scare_buy_time,user_visible, create_time, update_time, status';

    public function ModelOwner($orderSn, $userid) {
        $where = " AND order_sn = '{$orderSn}' AND user_id = {$userid}";
        $count = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        return $count > 0;
    }

    public function ModelAdd($data){
        $orderSn = 'YY'.date('YmdHis') . rand(10000, 99999);
        $setarray = array();
        $setarray[] = " order_sn = '{$orderSn}'";
        $setarray[] = " user_id = {$data['userId']}";
        $setarray[] = " distribution_fee = {$data['distributionFee']}";
        $setarray[] = " province = '{$data['address']['province']}'";
        $setarray[] = " city = '{$data['address']['city']}'";
        $setarray[] = " area = '{$data['address']['area']}'";
        $setarray[] = " code = '{$data['address']['code']}'";
        $setarray[] = " receiver_name = '{$data['address']['receiverName']}'";
        $setarray[] = " receiver_phone = '{$data['address']['receiverPhone']}'";
        $setarray[] = " scare_buy_time = '{$data['scareBuyTime']}'";
        $setarray[] = " order_status = 'waitforpay'";
        $setarray[] = " status = 1";
        $setarray[] = " create_time = now()";
        $setarray[] = " update_time = now()";

        $insertId = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $setarray);
        if ($insertId > 0) {
            $orderItems = get_load_model('bespeakOrderItems');
            $orderItemsData = array(
                'orderSn'       => $orderSn,
                'productId'     => $data['pro']['pid'],
                'skuId'         => $data['pro']['skuId'],
                'productName'  => $data['pro']['productName'],
                'model'         => $data['pro']['model'],
                'attr'         => $data['pro']['attr'],
                'productIcon'  => $data['pro']['productIcon'],
                'productPrice' => $data['pro']['productPrice'],
                'costPrice'   => $data['pro']['costPrice'],
                'num'           => $data['pro']['num'],
            );
            $orderItems->ModelAdd($orderItemsData);
            $skuModel = get_load_model('bespeakProductSku');
            $skuModel->UpdateSales($data['pro']['skuId'], $data['pro']['num']);
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
        $orderItemsModel = get_load_model('bespeakOrderItems');
        $dataval['id'] = intval($row['id']);
        $dataval['orderSn'] = $row['order_sn'];
        $dataval['orderStatus'] = $row['order_status'];
        $dataval['items'] = $orderItemsModel->ModelList($row['order_sn']);
        $price = 0;
        if(!empty($dataval['items'])){
            foreach ($dataval['items'] as $k => $v) {
                $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
            }
        }
        $dataval['distributionFee'] = doubleval($row['distribution_fee']);
        $dataval['price'] = bcadd($price,$row['distribution_fee'],2);
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
        $orderItemsModel = get_load_model('bespeakOrderItems');
        $dataval['orderSn'] = $row['order_sn'];
        $dataval['orderStatus'] = $row['order_status'];
        $dataval['items'] = $orderItemsModel->ModelList($row['order_sn']);
        $price = 0;
        if(!empty($dataval['items'])){
            foreach ($dataval['items'] as $k => $v) {
                $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
                $dataval['items'][$k]['total'] = doubleval(bcmul($v['productPrice'],$v['num'],2));
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
        $dataval['scareBuyTime'] = $row['scare_buy_time'];

        $orderLogModel = get_load_model('orderLog');
        $list = $orderLogModel->ModelList($orderSn);
        $dataval['history'] = $list;
        return $dataval;
    }

    public function ModelList($offset, $max, $userid, $status){
        $dataval = array();
        $where = " AND status = 1 AND user_id = {$userid} AND user_visible = 1";
        if(!empty($status)){
            if($status == 'pendingBuy'){
                $where.= " AND order_status in ('pendingOrder','takingOrder','pengdingBuy','allReject','overtime','noGoods')";
            }elseif ($status == 'pendingDelivery'){
                $where.= " AND order_status in ('pendingDelivery','waitconfirm')";
            }
            else{
                $where.= " AND order_status = '{$status}'";
            }
        }
        $orderby = " id DESC";
        $limit = " LIMIT {$offset}, {$max}";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        $pendingBuyArray = array("pendingOrder", "takingOrder", "pengdingBuy", "allReject", "overtime", "waitconfirm", "noGoods");
        $orderItemsModel = get_load_model('bespeakOrderItems');
        foreach ($list as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['orderSn'] = $val['order_sn'];
            $dataval[$key]['orderStatus'] = $val['order_status'];
            if(in_array($val['order_status'], $pendingBuyArray)){
                $dataval[$key]['orderStatus'] = 'pendingBuy';
            }
            $items = $orderItemsModel->ModelList($val['order_sn']);
            $price = 0;
            if(!empty($items)){
                foreach ($items as $k => $v) {
                    $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
                }
            }
            $dataval[$key]['items'] = $items;
            $dataval[$key]['price'] = doubleval(bcadd($price,$val['distribution_fee'],2));
            $dataval[$key]['createTime'] = $val['create_time'];
        }
        return $dataval;
    }

    public function ModelCancel($orderSn,$remark,$userid){
        $where = " AND order_sn = '{$orderSn}' AND user_id = {$userid} AND order_status = 'waitforpay' AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'cancel' ";
        $set[] = " remark = '{$remark}' ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function ModelRefund($orderSn, $userid){
        $where = " AND order_sn = '{$orderSn}' AND user_id = {$userid} AND order_status in ('pendingOrder','takingOrder','pengdingBuy','allReject','overtime','noGoods') AND status = 1";
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

    public function ModelInfoByFastTimeout($orderSn){
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}' AND order_status in ('pendingOrder','takingOrder','pengdingBuy','allReject') ";
        $order = "id desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
        if (empty($row)) {
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['orderSn'] = $row['order_sn'];
        $dataval['orderStatus'] = $row['order_status'];
        $dataval['scareBuyTime'] = $row['scareBuyTime'];
        return $dataval;
    }

    public function ModelDelay($orderSn,$scareBuyTime,$userid){
        $where = " AND order_sn = '{$orderSn}' AND user_id = {$userid} AND order_status in ('pendingOrder','takingOrder','pengdingBuy','waitconfirm','allReject') AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return false;
        }
        $set = array();
        $set[] = " scare_buy_time = '{$scareBuyTime}' ";
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

    public function BcModelList($orderSn,$orderStatus,$phone,$startTime,$endTime,$scareBuyStartTime,$scareBuyEndTime,$offset,$max){
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
        if(!empty($scareBuyStartTime)){
            $where.= " AND scare_buy_time >= '{$scareBuyStartTime}'";
        }
        if(!empty($scareBuyEndTime)){
            $where.= " AND scare_buy_time <= '{$scareBuyEndTime}'";
        }
        $orderby = " id DESC";
        $limit = " LIMIT {$offset}, {$max}";
        $orderlist = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($orderlist)) {
            return $dataval;
        }
        $orderItemsModel = get_load_model('bespeakOrderItems');
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
                case "pendingOrder":
                    $dataval[$key]['orderStatus'] = "待接单";
                    break;
                case "takingOrder":
                    $dataval[$key]['orderStatus'] = "已接单";
                    break;
                case "pendingDelivery":
                    $dataval[$key]['orderStatus'] = "待发货";
                    break;
                case "pengdingBuy":
                    $dataval[$key]['orderStatus'] = "抢购中";
                    break;
                case "waitconfirm":
                    $dataval[$key]['orderStatus'] = "待确认";
                    break;
                case "noGoods":
                    $dataval[$key]['orderStatus'] = "无货";
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
                case "allReject":
                    $dataval[$key]['orderStatus'] = "全部驳回";
                    break;
                case "overtime":
                    $dataval[$key]['orderStatus'] = "已超时";
                    break;
                case "refunding":
                    $dataval[$key]['orderStatus'] = "退款申请";
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

            $statusarray = array('pendingOrder','takingOrder','pengdingBuy','waitconfirm','allReject');
            if(in_array($val['order_status'],$statusarray) && !empty($val['scare_buy_time'])){
                //计算抢购剩余时间
                date_default_timezone_set('PRC');//使用PHP的date函数获取时间之前，先将时区设置为北京时区
                $nowTime = date('Y-m-d H:i:s');//获取当前时间
                $second = strtotime($val['scare_buy_time']) - strtotime($nowTime);
                $returnTime = $this->timeToString($second);
                $dataval[$key]['restTime'] = $returnTime;
            }else{
                $dataval[$key]['restTime'] = '';
            }
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
        $orderItemsModel = get_load_model('bespeakOrderItems');
        $dataval['id'] = intval($row['id']);
        $dataval['orderSn'] = $row['order_sn'];
        $dataval['orderStatus'] = $row['order_status'];
        $dataval['createTime'] = $row['create_time'];
        $dataval['distributionFee'] = doubleval($row['distribution_fee']);

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
            $refundOrderItemsModel = get_load_model('refundOrderItems');
            foreach ($dataval['items'] as $k => $v) {
                $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
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
        $orderItemsModel = get_load_model('bespeakOrderItems');
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
        $where = "AND order_sn = '{$orderSn}' AND order_status in ('pendingOrder','takingOrder','pendingDelivery','pengdingBuy','waitconfirm','noGoods','allReject','overtime') ";
        $orderrow = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($orderrow)) {
            return false;
        }
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelTakingOrder($orderSn){
        $where = " AND order_sn = '{$orderSn}' AND order_status = 'pendingOrder' AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'takingOrder'";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelInfoToAssignByOrderSn($orderSn){
        $where = " AND order_sn = '{$orderSn}' AND order_status in ('takingOrder','pengdingBuy','waitconfirm','allReject') AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return false;
        }
        return $row;
    }

    public function BcModelOrderTaking(){
        $where = " AND order_status = 'pendingOrder' AND status = 1";
        $set = array();
        $set[] = " order_status = 'takingOrder'";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelAssignOrderInfo($orderSn){
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        //订单基本信息  scare_buy_time
        //订单的状态 takingOrder,pengdingBuy
        $dataval['id'] = intval($row['id']);
        $dataval['orderSn'] = $row['order_sn'];
        $dataval['orderStatus'] = $row['order_status'];

        $statusarray = array("takingOrder","pengdingBuy","waitconfirm","noGoods","allReject","pendingOrder");
        if(in_array($row['order_status'],$statusarray) && !empty($row['scare_buy_time'])){
            //计算抢购剩余时间
            date_default_timezone_set('PRC');//使用PHP的date函数获取时间之前，先将时区设置为北京时区
            $nowTime = date('Y-m-d H:i:s');//获取当前时间
            $second = strtotime($row['scare_buy_time']) - strtotime($nowTime);
            $returnTime = $this->timeToString($second);
            $dataval['restTime'] = $returnTime;
        }else{
            $dataval['restTime'] = '';
        }

        //用户信息
        $customerModel = get_load_model('customer');
        $customer = $customerModel->ModelInfo($row['user_id']);
        $dataval['phone'] = $customer['username'];

        //订单包含的商品信息
        $orderItemsModel = get_load_model('bespeakOrderItems');
        $items = $orderItemsModel->BcModelList($orderSn);
        if(!empty($items)){
            foreach ($items as $k=>$v){
                $items[$k]['total'] = doubleval(bcmul($v['productPrice'],$v['num'],2));
            }
        }
        $dataval['items'] = $items;
        $purchaseModel = get_load_model('purchase');
        $buyerList = $purchaseModel->BcModelGetAllBuyers();
        if(!empty($buyerList)){
            $assignOrderModel = get_load_model('assignOrder');
            foreach ($buyerList as &$val){
                $assignOrder = $assignOrderModel->ModelFindByBidAndOrderSn($val['id'],$orderSn);
                if(!empty($assignOrder)){
                    $val['status'] = '';
                    if($assignOrder['status'] == 'waitforAssign'){
                        $val['status'] = '待分配';
                    }elseif ($assignOrder['status'] == 'buying'){
                        $val['status'] = '抢购中';
                    }elseif ($assignOrder['status'] == 'noGoods'){
                        $val['status'] = '无货';
                    }elseif ($assignOrder['status'] == 'alreadyPurchased '){
                        $val['status'] = '已购买';
                    }
                    $val['createTime'] = $assignOrder['createTime'];
                    $val['updateTime'] = $assignOrder['updateTime'];
                }else{
                    $val['status'] = '待分配';
                    $val['createTime'] = '';
                    $val['updateTime'] = '';
                }
            }
        }
        $dataval['buyerList'] = $buyerList;
        return $dataval;
    }

    private function timeToString($second){
        $day = floor($second/(3600*24));
        $second = $second%(3600*24);//除去整天之后剩余的时间
        $hour = floor($second/3600);
        $second = $second%3600;//除去整小时之后剩余的时间
        $minute = floor($second/60);
        if($day > 0){
            return $day.'天'.$hour.'小时'.$minute.'分';
        }elseif ($hour > 0){
            return  $hour.'小时'.$minute.'分';
        }elseif ($minute > 0){
            return $minute.'分';
        }else{
            return '';
        }
    }

    public function ModelGetCountBySkuIdAndStatus($userId,$skuId){
        $where = "AND user_id = {$userId} AND sku_id = {$skuId} AND bespeak_order.order_sn = bespeak_order_items.order_sn AND order_status
         in ('waitforpay','pendingOrder','takingOrder','pengdingBuy','waitconfirm','pendingDelivery','delivered')";
        $count = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",array('bespeak_order','bespeak_order_items'),$where);
        return intval($count);
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
        $where = " AND order_sn = '{$orderSn}' AND order_status = 'delivered'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        return $row;
    }

    public function BcModelReplaceOrder($orderSn){
        $where = " AND order_sn = '{$orderSn}' AND order_status = 'takingOrder' AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'pendingDelivery'";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelInfoCanRefund($orderSn){
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}' AND order_status in ('pendingOrder','takingOrder','pengdingBuy','noGoods','allReject')";
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
        $orderItemsModel = get_load_model('bespeakOrderItems');
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

    public function BcModelStatusToPengdingBuy($orderSn){
        $where = " AND order_sn = '{$orderSn}' AND status = 1 AND order_status in ('takingOrder','waitconfirm','allReject')";
        $orderrow = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($orderrow)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'pengdingBuy' ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelConfirmOrder($orderSn){
        $where = " AND order_sn = '{$orderSn}' AND order_status = 'waitconfirm' AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'pendingDelivery'";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function ModelPengdingBuyToAllReject($orderSn){
        $where = " AND order_sn = '{$orderSn}' AND status = 1 AND order_status = 'pengdingBuy'";
        $orderrow = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($orderrow)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'allReject' ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelConfirmNoGoods($orderSn){
        $where = " AND order_sn = '{$orderSn}' AND order_status = 'allReject' AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'noGoods'";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function ModelPengdingBuyToWaitConfirm($orderSn){
        $where = " AND order_sn = '{$orderSn}' AND status = 1 AND order_status = 'pengdingBuy'";
        $orderrow = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($orderrow)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'waitconfirm' ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function ModelCanRefundByOrderSn($orderSn,$userid){
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}' AND user_id = {$userid} AND order_status in ('pendingOrder','takingOrder','pengdingBuy','noGoods','allReject','overtime') AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        return $row;
    }

    public function ModelCountByUserIdAndStatus($userId,$orderStatus){
        $where = " AND user_id = {$userId} AND status = 1 AND user_visible = 1";
        if(!empty($orderStatus)){
            if($orderStatus == 'waitforpay'){
                $where.= " AND order_status = 'waitforpay' ";
            }elseif ($orderStatus == 'takingOrder'){
                $where.= " AND order_status in ('pendingOrder','takingOrder','pengdingBuy','waitconfirm','noGoods','allReject')";
            }elseif ($orderStatus == 'pendingDelivery'){
                $where.= " AND order_status = 'pendingDelivery'";
            }elseif ($orderStatus == 'delivered'){
                $where.= " AND order_status = 'delivered'";
            }
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
        $orderItemsModel = get_load_model('bespeakOrderItems');
        $dataval['orderSn'] = $row['order_sn'];
        $dataval['orderStatus'] = $row['order_status'];
        $dataval['createTime'] = $row['create_time'];
        $dataval['items'] = $orderItemsModel->ModelListByOrderSn($row['order_sn']);
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

    public function BcModelStatusToRefund($orderSn){
        $where = " AND order_sn = '{$orderSn}' AND order_status in ('pendingOrder','takingOrder','pengdingBuy','allReject','overtime','noGoods') AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'refunding' ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }
}