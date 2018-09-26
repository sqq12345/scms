<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/16
 * Time: 10:42
 */
class Order{

    public function getList($data){
        echo 11;die;
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        $status = inject_check(isset($data['status']) ? $data['status'] : '');    //订单的状态 waitforpay,pendingDelivery,delivered,complete,cancel,refunding,refundSuccess,refundError
        //区分秒发和直邮、1：秒发。2：直邮
        $region = inject_check(isset($data['region']) ? $data['region'] : '');
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $ordermodel = get_load_model('order');
        $dataval = $ordermodel->ModelList($offset, $max, $userid, $status, $region);
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
        $orderModel = get_load_model('order');
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
        $model = get_load_model('order');
        $ret = $model->ModelCancel($orderSn,$remark,$userid);
        if ($ret) {
            $orderLogModel = get_load_model('orderLog');
            $orderLogModel->ModelAdd($orderSn,$userid,$type,'waitforpay','cancel','订单取消');
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function refund2($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn']: '');
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('order');
        $ret = $model->ModelRefund($orderSn, $userid);
        if ($ret) {
            $orderLogModel = get_load_model('orderLog');
            $orderLogModel->ModelAdd($orderSn,$userid,$type,'pendingDelivery','refunding','退款申请提交');
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getDeliveryMessages($data){
        //todo
    }

    public function update($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');  //订单的编号orderId
        $addressId = intval(isset($data['addressId']) ? $data['addressId'] : 0);  //地址的id
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn) || $addressId == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $addressModel = get_load_model('address');
        $address = $addressModel->ModelInfo($userid,$addressId);
        if(empty($address)){
            set_return_value(ADDRESS_NULL, '');
            return false;
        }
        $orderModel = get_load_model('order');
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
        $ret = $orderModel->ModelUpdate($set,$orderSn);
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
        $orderModel = get_load_model('order');
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

    public function refund($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn']: '');
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('order');
        $order = $orderModel->ModelCanRefundByOrderSn($orderSn,$userid);
        if (empty($order)) {
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
        $itemsModel = get_load_model('orderItems');
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

                    //新增退款申请通知消息记录
                    $wxMessageModel = get_load_model('wxMessage');
                    //todo
                    $url = 'https://www.shichamaishou.com/orderDetail?type=refund&isNotice=1&orderSn='.$refundSn;
                    $miniprogram = array(

                    );
                    $first = '您已申请退款，等待商家确认退款信息。';
                    $orderProductPrice = 0;
                    foreach ($list as $v){
                        $orderProductPrice = bcadd(bcmul($v['productPrice'],$v['num'],2),$orderProductPrice,2);
                    }
                    $orderProductPrice = '¥'.$orderProductPrice;
                    $postData = array(
                        "first"=>array(
                            "value"=> $first,
                            "color"=>"#173177"
                        ),
                        "orderProductPrice"=>array(
                            "value"=> $orderProductPrice,
                            "color"=>"#173177"
                        ),
                        "orderProductName"=>array(
                            "value"=> $items[0]['productName'].'...',
                            "color"=>"#173177"
                        ),
                        "orderName"=>array(
                            "value"=> $orderSn,
                            "color"=>"#173177"
                        ),
                        "remark"=>array(
                            "value"=> '如有问题请直接在微信留言，将第一时间为您服务！',
                            "color"=>"#173177"
                        ),
                    );
                    $wxMessageModel->ModelAdd($userid,WX_MESSAGE_MODEL_9,$url,serialize($miniprogram),serialize($postData));
                    set_return_value(RESULT_SUCCESS, $dataval);
                }else {
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            } else {
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }else{
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function test($data){
        $dataval = array();
//        get_load_libraries('alipay');
//        $pay = new alipay();
//        $postData = array(
//            "title" 	 => "测试",
//            "body"       => "购买测试商品",
//            "orderNo"    => "CXD2018050710202333411",
//            "product_code"  => "QUICK_WAP_WAY",
//            "fee"        => 0.01,
//            "timeout_express"=>"1m"
//        );
////        $pay->doPay($postData);
//        $result = $pay->doPay($postData);
//        var_dump($result);
//        $dataval['id'] = 1;
//        $dataval['info'] = $result;
//        set_return_value(RESULT_SUCCESS, $dataval);
//        $postData = array(
//          "orderNo"=>"CO2018052110321898013",
//            "WIDrefund_amount"=>0.01,
//            "WIDrefund_reason"=>"测试",
//        );
//        $result = $pay->doRefund($postData);
//        var_dump($result);
//        $dataval['info'] = $result;
//        set_return_value(RESULT_SUCCESS, $dataval);

        //微信支付
//        var_dump(11);
//        get_load_libraries('wxpay');
//        var_dump(22);
//        $pay = new wxpay();
//        $result = $pay->doH5Pay();

 //       date_default_timezone_set('PRC');
//	var_dump(11111);
 //       $time = date("YmdHis");
  //      $time2 = date("YmdHis", time() + 600);
 //       var_dump($time);
 //       var_dump($time2);
 get_load_controller('wxChat');
        $wxChat = new WxChat();
        $post_data = array(
            "first"=>array(
              "value"=>"恭喜你购买成功！" ,
                "color"=>"#173177"
            ),
	    "OrderSn"=>array(
              "value"=>"2423536563633623" ,
                "color"=>"#173177"
            ),
	    "OrderStatus"=>array(
              "value"=>"已收货" ,
                "color"=>"#173177"
            ),
        );
        $res = $wxChat->wxSetSend('oQLbq0Wofl2hPCoWMvhvrF4IKVuU','3gCsqQ0JJTE-atoyw8s-UOxju-thFgNVGxwHPjv_ic4','https://www.shichamaishou.com',$post_data);
        var_dump($res);
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
        $orderModel = get_load_model('order');
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

    public function getSplitOrderList($data){
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
        $orderModel = get_load_model('order');
        if (!$orderModel->ModelOwner($orderSn, $userid)) {
            set_return_value(ORDER_NO_PERMISSION, '');
            return false;
        }
        $splitOrderModel = get_load_model('splitOrder');
        $list = $splitOrderModel->ModelListByOrderSn($orderSn);
        if(!empty($list)){
            $dataval['orderSn'] = $orderSn;
            $dataval['list'] = $list;
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

}