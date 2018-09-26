<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/7
 * Time: 14:46
 */
class BcRefundOrder{

    public function getList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $page = isset($_POST['page']) ? $_POST['page'] : 0;
        $limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
        $refundSn = inject_check(isset($data['refundSn']) ? $data['refundSn'] : '');
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');
        $orderStatus = inject_check(isset($data['orderStatus']) ? $data['orderStatus'] : ''); //refunding,refundSuccess,refundError
        $phone = inject_check(isset($data['phone']) ? $data['phone'] : '');   //客户手机号
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $offset = 0;
        if($page > 0){
            $offset = $limit * ($page - 1);
        }
        $model = get_load_model('refundOrder');
        $dataval = $model->BcModelList($refundSn,$orderSn,$orderStatus,$phone,$offset,$limit);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval['list'],$dataval['total']);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $refundSn = inject_check(isset($data['refundSn']) ? $data['refundSn'] : '');  //退款订单refundSn
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($refundSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('refundOrder');
        $dataval = $orderModel->BcModelInfo($refundSn);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function reject($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $refundSn = inject_check(isset($data['refundSn']) ? $data['refundSn']: '');
        $reason = inject_check(isset($data['reason']) ? $data['reason']: ''); //驳回原因
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($refundSn) || empty($reason)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('refundOrder');
        $info = $model->BcModelRefundingInfoByRefundSn($refundSn);
        if(empty($info)){
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
        $ret = $model->BcModelReject($refundSn,$reason,$userid);
        if ($ret) {
            $orderLogModel = get_load_model('orderLog');
            $logInfo = $orderLogModel->BcModelInfo($refundSn,'refunding');
            if(!empty($logInfo)){
                if(strstr($info['order_sn'],'CO')){
                    if($logInfo['user_type'] == 1){
                        $orderModel = get_load_model('order');
                        $set = array();
                        $set[] = "order_status = 'pendingDelivery'";
                        $orderModel->ModelUpdate($set, $info['order_sn']);
                    }
                }elseif (strstr($info['order_sn'],'YY')){
                    $bespeakModel = get_load_model('bespeakOrder');
                    if(!empty($logInfo['from_status'])){
                        $set = array();
                        $set[] = "order_status = '{$logInfo['from_status']}'";
                        $bespeakModel->ModelUpdate($set, $info['order_sn']);
                    }
                }
            }
            $orderLogModel->ModelAdd($refundSn,$userid,$type,'refunding','refundError','退款驳回');
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'refund',$refundSn,33);
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function validate($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $refundSn = inject_check(isset($data['refundSn']) ? $data['refundSn']: '');
        $price = isset($data['price']) ? doubleval($data['price']) : 0;  //退款金额
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($refundSn) || $price == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('refundOrder');
        $row = $model->BcModelRefundingInfoByRefundSn($refundSn);
        if(empty($row)){
            set_return_value(REFUND_ORDER_NULL_ERROR, '');
            return false;
        }
        $info = array();
        if(strstr($row['order_sn'],'CO')){
            $orderModel = get_load_model('order');
            $info = $orderModel->BcModelInfoToRefund($row['order_sn']);
        }elseif (strstr($row['order_sn'],'YY')){
            $bespeakOrderModel = get_load_model('bespeakOrder');
            $info = $bespeakOrderModel->BcModelInfoToRefund($row['order_sn']);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
            return false;
        }
        if(empty($info)){
            set_return_value(ORDER_MESSAGE_NULL, $dataval);
            return false;
        }
        $orderItemsModel = get_load_model('refundOrderItems');
        $items= $orderItemsModel->BcModelList($row['refund_sn']);
        if(empty($items)){
            set_return_value(REFUND_ORDER_NULL_ERROR, '');
            return false;
        }
        $needPrice = 0;  //需要退款金额
        foreach ($items as $k => $v) {
            $needPrice = bcadd(bcmul($v['productPrice'],$v['num'],2),$needPrice,2);
        }

        $hasRefund = $model->BcModelGetRefundPrice($row['order_sn']);  //已经退款金额
        if($price > ($needPrice + $info['distributionFee'])){
            set_return_value(REFUND_PRICE_ERROR, '');
            return false;
        }
        if($price + $hasRefund > $info['orderTotal']){
            set_return_value(REFUND_PRICE_ERROR, '');
            return false;
        }
//        if(($price + $hasRefund) >= $info['productTotal'] && ($price + $hasRefund) <= $info['orderTotal']){
//            set_return_value(REFUND_DISTRIBUTION_FEE, '');
//            return false;
//        }
        $payInfoModel = get_load_model('payInfo');
        $payInfo = $payInfoModel->BcModelInfoByOrderSnAndPayed($row['order_sn']);
        if(empty($payInfo)){
            set_return_value(ORDER_MESSAGE_NULL, $dataval);
            return false;
        }
        if($payInfo['pay_type'] == 'alipay'){
            //调用支付宝退款接口
            get_load_libraries('alipay');
            $pay = new alipay();
            $payData = array(
                "orderNo"    => $row['order_sn'],
                "WIDrefund_reason"  => "订单退款",
                "WIDrefund_amount"        => $price,
                "WIDout_request_no"=>$refundSn
            );
            $result = $pay->doRefund($payData);
            $result = (array)$result;
            if($result['code'] != 10000){
                set_return_value(DEFEATED_ERROR, $dataval);
                return fasle;
            }
        }else{
            $orderSn = '';
            if($payInfo['pay_type'] == 'h5wxpay'){
                $orderSn = 'H5'.$row['order_sn'];
            }elseif ($payInfo['pay_type'] == 'wxpay'){
                $orderSn = 'WS'.$row['order_sn'];
            }elseif ($payInfo['pay_type'] == 'qrcodepay'){
                $orderSn = 'QR'.$row['order_sn'];
            }elseif ($payInfo['pay_type'] == 'xcxpay'){
                $orderSn = 'WS'.$row['order_sn'];
            }else{
                set_return_value(DEFEATED_ERROR, $dataval);
                return fasle;
            }
            get_load_libraries('wxpay');
            $pay = new wxpay();
            $payData = array(
                "out_trade_no"    => $orderSn,
                "total_fee"        => $info['orderTotal']*100,
                "refund_fee"        => $price*100,
                "out_refund_no" =>$refundSn,
            );
            $result = $pay->doRefund($payData);
            if($result['result_code'] != 'SUCCESS' || $result['return_code'] != 'SUCCESS'){
                set_return_value(DEFEATED_ERROR, $dataval);
                return fasle;
            }
        }
        //退款成功
        $ret = $model->BcModelRefundSuccess($refundSn,$price);
        if($ret){
            $orderLogModel = get_load_model('orderLog');
            $logInfo = $orderLogModel->BcModelInfo($refundSn,'refunding');
            if(!empty($logInfo)){
                if(strstr($row['order_sn'],'CO')){
                    if($logInfo['user_type'] == 1){
                        $orderModel = get_load_model('order');
                        $set = array();
                        $set[] = "order_status = 'refundSuccess'";
                        $orderModel->ModelUpdate($set, $row['order_sn']);
                        $sliptOrderModel = get_load_model('splitOrder');
                        $update = array();
                        $update[] = "order_status = 'cancel'";
                        $sliptOrderModel->ModelUpdate($update, $row['order_sn']);
                    }else{
                        $itemsModel = get_load_model('orderItems');
                        $refundOrderItemsModel = get_load_model('refundOrderItems');
                        $arr1 = $itemsModel->ModelGetSkuArray($row['order_sn']);
                        $arr2 = $refundOrderItemsModel->BcModelGetSkuArray($row['order_sn']);
                        if(!array_diff($arr1,$arr2)){
                            $set = array();
                            $set[] = "order_status = 'cancel'";
                            $model->ModelUpdate($set, $row['order_sn']);
                        }
                        $sliptOrderItemsModel = get_load_model('splitOrderItems');
                        $sliptOrderItemsModel->BcModelUpdateRefundNum($row['order_sn'],$row['user_id'],$items[0]['skuId'],$items[0]['num']);
                    }
                }elseif (strstr($row['order_sn'],'YY')){
                    $bespeakModel = get_load_model('bespeakOrder');
                    $set = array();
                    $set[] = "order_status = 'refundSuccess'";
                    $bespeakModel->ModelUpdate($set, $row['order_sn']);
                }
            }
            $orderLogModel->ModelAdd($refundSn,$userid,$type,'refunding','refundSuccess','退款成功');
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'refund',$refundSn,34);

            //更新用户月消费金额
            $validateTime = date("Y-m",time());
            if(strstr($payInfo['pay_time'],$validateTime)){
                $limitAmountModel = get_load_model('limitAmount');
                $limitAmountModel->ModelUpdate($payInfo['user_id'], $needPrice,0);
            }

            //新增退款成功消息通知记录
            $wxMessageModel = get_load_model('wxMessage');
            //todo
            $url = 'https://www.shichamaishou.com/orderDetail?type=refund&isNotice=1&orderSn='.$refundSn;
            $miniprogram = array(

            );
            $first = '您的订单已经完成退款，¥'.$price.'已经退回您的付款账户，请留意查收。';
            $orderProductPrice = '¥'.$price;
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
                    "value"=> $refundSn,
                    "color"=>"#173177"
                ),
            );
            $wxMessageModel->ModelAdd($row['user_id'],WX_MESSAGE_MODEL_2,$url,serialize($miniprogram),serialize($postData));
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }

    }

}