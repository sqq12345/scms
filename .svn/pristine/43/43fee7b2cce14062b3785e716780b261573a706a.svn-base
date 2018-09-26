<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/14
 * Time: 15:07
 */
class Statistics{

    public function getOrder($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $sType = isset($data['sType']) ? intval($data['sType']) : 0; // 0:全部  1:现货订单  2:预约订单
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if($sType == 0){
            $orderModel = get_load_model('order');
            $orderCountList = array();
            $orderCountList['waitforpay'] = $orderModel->ModelCountByUserIdAndStatus($userid,'waitforpay');
            $orderCountList['pendingDelivery'] = $orderModel->ModelCountByUserIdAndStatus($userid,'pendingDelivery');
            $orderCountList['delivered'] = $orderModel->ModelCountByUserIdAndStatus($userid,'delivered');
            $dataval['order'] = $orderCountList;

            $bespeakOrderModel = get_load_model('bespeakOrder');
            $bespeakOrderCountList = array();
            $bespeakOrderCountList['waitforpay'] = $bespeakOrderModel->ModelCountByUserIdAndStatus($userid,'waitforpay');
            $bespeakOrderCountList['takingOrder'] = $bespeakOrderModel->ModelCountByUserIdAndStatus($userid,'takingOrder');
            $bespeakOrderCountList['pendingDelivery'] = $bespeakOrderModel->ModelCountByUserIdAndStatus($userid,'pendingDelivery');
            $bespeakOrderCountList['delivered'] = $bespeakOrderModel->ModelCountByUserIdAndStatus($userid,'delivered');
            $dataval['bespeakOrder'] = $bespeakOrderCountList;
        }elseif ($sType == 1){
            $orderModel = get_load_model('order');
            $orderCountList = array();
            $orderCountList['waitforpay'] = $orderModel->ModelCountByUserIdAndStatus($userid,'waitforpay');
            $orderCountList['pendingDelivery'] = $orderModel->ModelCountByUserIdAndStatus($userid,'pendingDelivery');
            $orderCountList['delivered'] = $orderModel->ModelCountByUserIdAndStatus($userid,'delivered');
            $dataval['order'] = $orderCountList;
        }elseif ($sType == 2){
            $bespeakOrderModel = get_load_model('bespeakOrder');
            $bespeakOrderCountList = array();
            $bespeakOrderCountList['waitforpay'] = $bespeakOrderModel->ModelCountByUserIdAndStatus($userid,'waitforpay');
            $bespeakOrderCountList['takingOrder'] = $bespeakOrderModel->ModelCountByUserIdAndStatus($userid,'takingOrder');
            $bespeakOrderCountList['pendingDelivery'] = $bespeakOrderModel->ModelCountByUserIdAndStatus($userid,'pendingDelivery');
            $bespeakOrderCountList['delivered'] = $bespeakOrderModel->ModelCountByUserIdAndStatus($userid,'delivered');
            $dataval['bespeakOrder'] = $bespeakOrderCountList;
        }else{
            set_return_value(RESULT_ERROR_NULL, $dataval);
            return false;
        }
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}