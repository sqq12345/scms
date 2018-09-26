<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/17
 * Time: 15:28
 */
class BespeakOrder{

    public function getList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        $status = inject_check(isset($data['status']) ? $data['status'] : '');    //订单的状态 waitforpay,pendingOrder,takingOrder,pendingDelivery,pengdingBuy,waitconfirm,noGoods,delivered,complete,cancel,allReject,overtime,refunding,refundSuccess,refundError
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $model = get_load_model('bespeakOrder');
        $dataval = $model->ModelList($offset, $max, $userid, $status);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']): 0;    //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn']: '');  //订单的编号ordersn
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('bespeakOrder');
        if (!$orderModel->ModelOwner($orderSn, $userid)) {
            set_return_value(ORDER_NO_PERMISSION, '');
            return false;
        }
        $dataval = $orderModel->ModelGetInfoByOrderSn($orderSn);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function cancel($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn']: '');
        $remark = inject_check(isset($data['remark']) ? $data['remark']: '');
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakOrder');
        if (!$model->ModelOwner($orderSn, $userid)) {
            set_return_value(ORDER_NO_PERMISSION, '');
            return false;
        }
        $ret = $model->ModelCancel($orderSn,$remark,$userid);
        if ($ret) {
            $orderLogModel = get_load_model('orderLog');
            $orderLogModel->ModelAdd($orderSn,$userid,$type,'waitforpay','cancel','订单取消');
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function refund($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn']: '');// pendingOrder,takingOrder,pengdingBuy,allReject,overtime
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('bespeakOrder');
        $order = $orderModel->ModelCanRefundByOrderSn($orderSn,$userid);
        if(empty($order)){
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
        $itemsModel = get_load_model('bespeakOrderItems');
        $items = $itemsModel->ModelList($orderSn);
        if(empty($items)){
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
        if(sizeof($items) > 0){
            $list = array();
            foreach ($items as $val){
                $pro = array();
                $pro['productId'] = $val['productId'];
                $pro['skuId'] = $val['skuId'];
                $pro['productName'] = $val['productName'];
                $pro['model'] = $val['model'];
                $pro['attr'] = $val['attr'];
                $pro['productIcon'] = $val['productIcon'];
                $pro['productPrice'] = $val['productPrice'];
                $pro['num'] = $val['num'];
                array_push($list,$pro);
            }
            $postData = array();
            $postData['userId'] = $userid;
            $postData['orderSn'] = $orderSn;
            $postData['remark'] = '';
            $postData['createBy'] = 0;
            $postData['items'] = $list;
            $refundModel = get_load_model('refundOrder');
            $refundSn = $refundModel->ModelAdd($postData);
            if ($refundSn) {
                $ret = $orderModel->ModelRefund($orderSn, $userid);
                if($ret){
                    $orderLogModel = get_load_model('orderLog');
                    $orderLogModel->ModelAdd($refundSn,$userid,$type,$order['order_status'],'refunding','退款申请提交');
                    set_return_value(RESULT_SUCCESS, $dataval);
                }else {
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            } else {
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getDeliveryMessages($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']): 0;    //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn']: '');  //订单的编号ordersn
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('bespeakOrder');
        if (!$orderModel->ModelOwner($orderSn, $userid)) {
            set_return_value(ORDER_NO_PERMISSION, '');
            return false;
        }
        $deliveryModel = get_load_model('orderDelivery');
        $orderDelivery = $deliveryModel->ModelInfo($orderSn);
        if(empty($orderDelivery)){
            set_return_value(ORDER_DELIVERY_ERROR, '');
            return false;
        }
        get_load_libraries('delivery');
        $delivery = new delivery();
        $return = $delivery->getDeliveryMessage($orderDelivery['deliverySn']);
        if($return['code'] == 0 && $return['msg'] == 'success'){
            $dataval = $return['data']['traces'];
        }
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function update($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');  //订单的编号orderId
        $addressId = intval(isset($data['addressId']) ? $data['addressId'] : 0);  //地址的id
        $scareBuyTime = isset($data['scareBuyTime']) ? $data['scareBuyTime'] : ''; //抢购时间
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn) || $addressId == 0 || empty($scareBuyTime)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $addressModel = get_load_model('address');
        $address = $addressModel->ModelInfo($userid,$addressId);
        if(empty($address)){
            set_return_value(ADDRESS_NULL, '');
            return false;
        }
        $orderModel = get_load_model('bespeakOrder');
        if (!$orderModel->ModelOwner($orderSn, $userid)) {
            set_return_value(ORDER_NO_PERMISSION, '');
            return false;
        }
        $set = array();
        $set[] = "receiver_name = '{$address['receiverName']}'";
        $set[] = "receiver_phone = '{$address['receiverPhone']}'";
        $set[] = "province = '{$address['province']}'";
        $set[] = "city = '{$address['city']}'";
        $set[] = "area = '{$address['area']}'";
        $set[] = "address = '{$address['address']}'";
        $set[] = "code = '{$address['code']}'";
        $set[] = "scare_buy_time = '{$scareBuyTime}'";
        $ret = $orderModel->ModelUpdate($set,$orderSn);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function delay($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn']: '');
        $days = isset($data['days']) ? intval($data['days']) : 2; //延期时间  天
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn) || $days == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('bespeakOrder');
        if (!$model->ModelOwner($orderSn, $userid)) {
            set_return_value(ORDER_NO_PERMISSION, '');
            return false;
        }
        $order = $model->ModelInfoByFastTimeout($orderSn);
        if(empty($order)){
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
        $scareBuyTime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",strtotime($order['scareBuyTime'])).'+ '.$days.' days'));
        $ret = $model->ModelDelay($orderSn,$scareBuyTime,$userid);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function delete($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']): 0;    //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn']: '');  //订单的编号ordersn
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('bespeakOrder');
        if (!$orderModel->ModelOwner($orderSn, $userid)) {
            set_return_value(ORDER_NO_PERMISSION, '');
            return false;
        }
        $ret = $orderModel->ModelDelete($orderSn,$userid);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getDelivery($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']): 0;    //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn']: '');  //订单的编号ordersn
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('bespeakOrder');
        if (!$orderModel->ModelOwner($orderSn, $userid)) {
            set_return_value(ORDER_NO_PERMISSION, '');
            return false;
        }
        //订单基本信息
        $dataval = $orderModel->ModelGetBaseInfoByOrderSn($orderSn);
        $deliveryModel = get_load_model('orderDelivery');
        $orderDelivery = $deliveryModel->ModelInfo($orderSn);
        $dataval['list'] = array();
        if(!empty($orderDelivery)){
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
            if ($info['status'] != 200) {
                set_return_value(RESULT_SUCCESS, $dataval);
                return false;
            }
            $dataval['list'] = $info['data'];
        }
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}