<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/6/28
 * Time: 13:38
 */
class BcSplitOrder{

    public function getList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $page = isset($_POST['page']) ? $_POST['page'] : 0;
        $limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
        $orderSn = inject_check(isset($data['orderSn']) ? $data['orderSn'] : '');
        $splitOrderSn = inject_check(isset($data['splitOrderSn']) ? $data['splitOrderSn'] : '');
        $orderStatus = inject_check(isset($data['orderStatus']) ? $data['orderStatus'] : '');
        $phone = inject_check(isset($data['phone']) ? $data['phone'] : '');
        $region = inject_check(isset($data['region']) ? $data['region'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $offset = 0;
        if($page > 0){
            $offset = $limit * ($page - 1);
        }
        $model = get_load_model('splitOrder');
        $dataval = $model->BcModelList($orderSn,$splitOrderSn,$orderStatus,$phone,$region,$offset,$limit);
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
        $splitOrderSn = inject_check(isset($data['splitOrderSn']) ? $data['splitOrderSn'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($splitOrderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('splitOrder');
        $dataval = $orderModel->BcModelInfo($splitOrderSn);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function delivery($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $splitOrderSn = isset($data['splitOrderSn']) ? $data['splitOrderSn'] : '';  //订单号集合
        $code = isset($data['code']) ? $data['code'] : 'yangbaoguo';  //物流公司
        $deliverySn = isset($data['deliverySn']) ? $data['deliverySn'] : '';  //订单号集合
        $update = isset($data['update']) ? intval($data['update']) : 0;    //是否返回物流信息
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($splitOrderSn) || empty($deliverySn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $companyModel = get_load_model('company');
        $info = $companyModel->BcModelInfoByCode($code);
        if(empty($info)){
            set_return_value(COMPANY_NULL_ERROR, '');
            return false;
        }
        $splitOrderModel = get_load_model('splitOrder');
        $splitOrder = $splitOrderModel->BcModelMiNiInfo($splitOrderSn);
        if(empty($splitOrder)){
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
        $orderDeliveryModel = get_load_model('orderDelivery');
        $ret = 0;
        if($splitOrder['order_status'] == 'pendingDelivery'){
            if($splitOrderModel->BcModelStatusToDelivered($splitOrderSn)){
                $ret = $orderDeliveryModel->ModelAdd($splitOrderSn,$deliverySn,$info['name'],$code,$userid);
                if($ret){
                    $orderModel = get_load_model('order');
                    $ret = $orderModel->BcModelStatusToDelivered($splitOrder['order_sn']);
                }
            }
        }elseif ($splitOrder['order_status'] == 'delivered' || $splitOrder['order_status'] == 'complete'){
            $ret = $orderDeliveryModel->ModelUpdate($splitOrderSn,$deliverySn,$info['name'],$code,$userid);
        }else{
            $ret = 0;
        }
        if($ret){
            $orderLogModel = get_load_model('orderLog');
            $logModel = get_load_model('operationLog');
            $orderLogModel->ModelAdd($splitOrder['order_sn'],$userid,$type,'pendingDelivery','delivered','平台发货');
            $logModel->ModelAdd($userid,'order',$splitOrder['order_sn'],28);
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
            //新增发货消息通知记录
            $first = "亲，宝贝已经启程了，好想快点来到你身边";
            $delivername = $info['name'];
            $ordername = $deliverySn;
            $remark = '如有问题请直接在微信留言，将第一时间为您服务！';
            $url = '';
            $miniprogram = array(

            );
            $postData = array(
                "first"=>array(
                    "value"=> $first,
                    "color"=>"#173177"
                ),
                "delivername"=>array(
                    "value"=> $delivername,
                    "color"=>"#173177"
                ),
                "ordername"=>array(
                    "value"=> $ordername,
                    "color"=>"#173177"
                ),
                "Remark"=>array(
                    "value"=> $remark,
                    "color"=>"#173177"
                ),
            );
            $url = 'https://www.shichamaishou.com/orderDetail?type=order&isNotice=1&orderSn='.$splitOrder['order_sn'];
            $wxMessageModel = get_load_model('wxMessage');
            $wxMessageModel->ModelAdd($splitOrder['user_id'],WX_MESSAGE_MODEL_7,$url,serialize($miniprogram),serialize($postData));
            set_return_value(RESULT_SUCCESS, $dataval);
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function updateDelivery($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户的Id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $splitOrderSn = inject_check(isset($data['splitOrderSn']) ? $data['splitOrderSn'] : '');
        $code = isset($data['code']) ? $data['code'] : 'yangbaoguo';  //物流公司
        $deliverySn = isset($data['deliverySn']) ? $data['deliverySn'] : '';  //订单号
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($splitOrderSn) || empty($deliverySn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $companyModel = get_load_model('company');
        $info = $companyModel->BcModelInfoByCode($code);
        if(empty($info)){
            set_return_value(COMPANY_NULL_ERROR, '');
            return false;
        }
        $orderModel = get_load_model('splitOrder');
        $order = $orderModel->BcModelInfoByDelivered($splitOrderSn);
        if(empty($order)){
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
        $orderDeliveryModel = get_load_model('orderDelivery');
        $ret = $orderDeliveryModel->ModelUpdate($splitOrderSn,$deliverySn,$info['name'],$code,$userid);
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
        $splitOrderSn = inject_check(isset($data['splitOrderSn']) ? $data['splitOrderSn']: '');  //订单的编号ordersn
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($splitOrderSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $orderModel = get_load_model('splitOrder');
        $order = $orderModel->BcModelMiNiInfo($splitOrderSn);
        if(empty($order)){
            set_return_value(ORDER_MESSAGE_NULL, '');
            return false;
        }
        $deliveryModel = get_load_model('orderDelivery');
        $orderDelivery = $deliveryModel->ModelInfo($splitOrderSn);
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