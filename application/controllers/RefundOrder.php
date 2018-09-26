<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/8
 * Time: 14:20
 */
class RefundOrder{

    public function getList($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;    //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $max = isset($data['max']) ? intval($data['max']) : 10;
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $model = get_load_model('refundOrder');
        $dataval = $model->ModelList($offset, $max, $userid);
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
        $refundSn = inject_check(isset($data['refundSn']) ? $data['refundSn']: '');  //订单的编号ordersn
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($refundSn)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $model = get_load_model('refundOrder');
        if (!$model->ModelOwner($refundSn, $userid)) {
            set_return_value(ORDER_NO_PERMISSION, '');
            return false;
        }
        $dataval = $model->ModelGetInfoByRefundSn($refundSn);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}