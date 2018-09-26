<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/16
 * Time: 11:51
 */
class BcOrder{

    public function getList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $page = isset($_POST['page']) ? $_POST['page'] : 0;
        $limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');
        $orderStatus = inject_check(isset($data['orderStatus']) ? $data['orderStatus'] : '');
        $phone = inject_check(isset($data['phone']) ? $data['phone'] : '');
        $startTime = inject_check(isset($data['startTime']) ? $data['startTime'] : '');
        $endTime = inject_check(isset($data['endTime']) ? $data['endTime'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $offset = 0;
        if($page > 0){
            $offset = $limit * ($page - 1);
        }
        $model = get_load_model('order');
        $dataval = $model->BcModelList($orderSn,$orderStatus,$phone,$startTime,$endTime,$offset,$limit);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval['list'],$dataval['total']);
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
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn) || empty($remark)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('order');
        $ret = $model->BcModelCancel($orderSn,$remark);
        if ($ret) {
            $orderLogModel = get_load_model('orderLog');
            $orderLogModel->ModelAdd($orderSn,$userid,$type,'waitforpay','cancel','订单取消');
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'order',$orderSn,25);
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('order');
        $dataval = $orderModel->BcModelInfo($orderSn);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getEditInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('order');
        $dataval = $orderModel->BcModelEditInfo($orderSn);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function update($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');
        $province = inject_check(isset($data['province']) ? $data['province'] : '');
        $city = inject_check(isset($data['city']) ? $data['city'] : '');
        $area = inject_check(isset($data['area']) ? $data['area'] : '');
        $address = inject_check(isset($data['address']) ? $data['address'] : '');
        $receiverName = inject_check(isset($data['receiverName']) ? $data['receiverName'] : '');
        $receiverPhone = inject_check(isset($data['receiverPhone']) ? $data['receiverPhone'] : '');
        $code = inject_check(isset($data['code']) ? $data['code'] : '');
        $remark = inject_check(isset($data['remark']) ? $data['remark'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('order');
        if(!empty($receiverName)) $set[] = "receiver_name = '{$receiverName}'";
        if(!empty($receiverPhone)) $set[] = "receiver_phone = '{$receiverPhone}'";
        if(!empty($province)) $set[] = "province = '{$province}'";
        if(!empty($city)) $set[] = "city = '{$city}'";
        if(!empty($area)) $set[] = "area = '{$area}'";
        if(!empty($address)) $set[] = "address = '{$address}'";
        if(!empty($code)) $set[] = "code = '{$code}'";
        if(!empty($remark)) $set[] = "remark = '{$remark}'";
        $ret = $orderModel->ModelUpdate($set,$orderSn);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function updateAddress($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');
        $province = inject_check(isset($data['province']) ? $data['province'] : '');
        $city = inject_check(isset($data['city']) ? $data['city'] : '');
        $area = inject_check(isset($data['area']) ? $data['area'] : '');
        $address = inject_check(isset($data['address']) ? $data['address'] : '');
        $receiverName = inject_check(isset($data['receiverName']) ? $data['receiverName'] : '');
        $receiverPhone = inject_check(isset($data['receiverPhone']) ? $data['receiverPhone'] : '');
        $code = inject_check(isset($data['code']) ? $data['code'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('order');
        $order = $model->BcModelInfoPendingDelivery($orderSn);
        if(empty($order)){
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
        if(!empty($receiverName)) $set[] = "receiver_name = '{$receiverName}'";
        if(!empty($receiverPhone)) $set[] = "receiver_phone = '{$receiverPhone}'";
        if(!empty($province)) $set[] = "province = '{$province}'";
        if(!empty($city)) $set[] = "city = '{$city}'";
        if(!empty($area)) $set[] = "area = '{$area}'";
        if(!empty($address)) $set[] = "address = '{$address}'";
        if(!empty($code)) $set[] = "code = '{$code}'";
        if(empty($set)){
            set_return_value(DEFEATED_ERROR, $dataval);
            return false;
        }
        $ret = $model->BcModelUpdateAddress($set, $orderSn);
        if ($ret) {
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'order',$orderSn,26);
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function delivery($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSns = isset($data['orderSns']) ? $data['orderSns'] : '';  //订单号集合
        $code = isset($data['code']) ? $data['code'] : 'yangbaoguo';  //物流公司
        $deliverySn = isset($data['deliverySn']) ? $data['deliverySn'] : '';  //订单号集合
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSns) || empty($deliverySn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $companyModel = get_load_model('company');
        $info = $companyModel->BcModelInfoByCode($code);
        if(empty($info)){
            set_return_value(COMPANY_NULL_ERROR, '');
            return false;
        }
        $orderModel = get_load_model('order');
        if(sizeof($orderSns) > 0){
            $orderDeliveryModel = get_load_model('orderDelivery');
            $ret = 0;
            $orderLogModel = get_load_model('orderLog');
            foreach ($orderSns as $val){
                if($orderModel->BcModelStatusToDelivered($val)){
                    $ret = $orderDeliveryModel->ModelAdd($val,$deliverySn,$info['name'],$code,$userid);
                    if($ret){
                        $orderLogModel->ModelAdd($val,$userid,$type,'pendingDelivery','delivered','平台发货');
                    }
                }
            }
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function updateDelivery($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');
        $code = isset($data['code']) ? $data['code'] : 'yangbaoguo';  //物流公司
        $deliverySn = isset($data['deliverySn']) ? $data['deliverySn'] : '';  //订单号
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn) || empty($deliverySn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $companyModel = get_load_model('company');
        $info = $companyModel->BcModelInfoByCode($code);
        if(empty($info)){
            set_return_value(COMPANY_NULL_ERROR, '');
            return false;
        }
        $orderModel = get_load_model('order');
        $order = $orderModel->BcModelInfoByDelivered($orderSn);
        if(empty($order)){
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
        $orderDeliveryModel = get_load_model('orderDelivery');
        $ret = $orderDeliveryModel->ModelUpdate($orderSn,$deliverySn,$info['name'],$code,$userid);
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
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn']: '');// pendingOrder,takingOrder,pengdingBuy,allReject,overtime
        $pid = isset($data['pid']) ? intval($data['pid']) : 0;    //商品id
        $skuId = isset($data['skuId']) ? intval($data['skuId']) : 0;    //商品sku的id
        $num = isset($data['num']) ? intval($data['num']) : 0;    //退款商品num
        $remark = inject_check(isset($data['remark']) ? $data['remark']: '');    //退款原因
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn) || $pid == 0 || $skuId == 0 || $num == 0 || empty($remark)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('order');
        $order = $model->BcModelInfoPendingDelivery($orderSn);
        if(empty($order)){
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
        $itemsModel = get_load_model('orderItems');
        $item = $itemsModel->BcModelInfoByOrderSnAndPidAndSkuId($orderSn,$pid,$skuId);
        if(empty($item)){
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
        $refundOrderItemsModel = get_load_model('refundOrderItems');
        $sum = $refundOrderItemsModel->BcModelGetSumByOrderSnAndPidAndSkuId($orderSn,$pid,$skuId);
        $sum = $sum + $num;
        if($sum > $item['num']){
            set_return_value(REFUND_SKU_NUM_ERROR, '');
            return false;
        }
        $pro = array();
        $pro['productId'] = $pid;
        $pro['skuId'] = $skuId;
        $pro['productName'] = $item['product_name'];
        $pro['model'] = $item['model'];
        $pro['attr'] = $item['attr'];
        $pro['productIcon'] = $item['product_icon'];
        $pro['productPrice'] = $item['product_price'];
        $pro['num'] = $num;
        $postData = array();
        $postData['userId'] = $order['user_id'];
        $postData['orderSn'] = $orderSn;
        $postData['remark'] = $remark;
        $postData['createBy'] = $userid;
        $postData['pro'] = $pro;
        $refundModel = get_load_model('refundOrder');
        $ret = $refundModel->BcModelAdd($postData);
        if ($ret) {
            $orderLogModel = get_load_model('orderLog');
            $orderLogModel->ModelAdd($ret,$userid,$type,'','refunding','退款申请提交');
            $logModel = get_load_model('operationLog');
            $logModel->ModelAdd($userid,'order',$orderSn,27);
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function batchDelivery($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSns = isset($data['orderSns']) ? $data['orderSns'] : '';  //订单号集合
        $code = isset($data['code']) ? $data['code'] : 'yangbaoguo';  //物流公司
        $deliverySn = isset($data['deliverySn']) ? $data['deliverySn'] : '';  //订单号集合
        $update = isset($data['update']) ? intval($data['update']) : 0;    //是否返回物流信息
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSns) || empty($deliverySn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $companyModel = get_load_model('company');
        $info = $companyModel->BcModelInfoByCode($code);
        if(empty($info)){
            set_return_value(COMPANY_NULL_ERROR, '');
            return false;
        }
        if(sizeof($orderSns) > 0){
            $orderModel = get_load_model('order');
            $orderDeliveryModel = get_load_model('orderDelivery');
            $orderLogModel = get_load_model('orderLog');
            $logModel = get_load_model('operationLog');
            foreach ($orderSns as $val){
                $order = $orderModel->BcModelMiNiInfo($val);
                if(!empty($order)){
                    if($order['order_status'] == 'pendingDelivery'){
                        if($orderModel->BcModelStatusToDelivered($val)){
                            $ret = $orderDeliveryModel->ModelAdd($val,$deliverySn,$info['name'],$code,$userid);
                            if($ret){
                                $orderLogModel->ModelAdd($val,$userid,$type,'pendingDelivery','delivered','平台发货');
                                $logModel->ModelAdd($userid,'order',$val,28);
                            }
                        }
                    }elseif ($order['order_status'] == 'delivered' || $order['order_status'] == 'complete'){
                        $orderDeliveryModel->ModelUpdate($val,$deliverySn,$info['name'],$code,$userid);
                        $logModel->ModelAdd($userid,'order',$val,29);
                    }
                }
            }
            $dataval['deliveryList'] = array();
            if($update > 0){
                get_load_libraries('Mailtracking');
                $MT = new Mailtracking();
                $MT->setMailId($deliverySn, $info['code']);
                if (!isset($_SERVER['HTTP_USER_AGENT'])
                    || empty($_SERVER['HTTP_USER_AGENT'])
                ) {
                    $_SERVER['HTTP_USER_AGENT']
                        = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0;
                                            .NET CLR 2.0.50727; InfoPath.2; AskTbPTV/5.17.0.25589; Alexa Toolbar)';
                }
                $MT->curlPost();
                $returnInfo = $MT->getMailInfo();

                $returnInfo = json_decode($returnInfo, true);
                if ($returnInfo['status'] == 200) {
                    $dataval['deliveryList'] = $returnInfo['data'];
                }
            }
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getDelivery($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']): 0;    //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn']: '');  //订单的编号ordersn
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($orderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('order');
        $order = $orderModel->BcModelMiNiInfo($orderSn);
        if(empty($order)){
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
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
        set_return_value(RESULT_SUCCESS, $dataval);
    }
}