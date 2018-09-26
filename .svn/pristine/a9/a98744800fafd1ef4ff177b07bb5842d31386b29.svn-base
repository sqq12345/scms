<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/16
 * Time: 11:19
 */
class OrderLogModel{
    private $filepath = 'order_log';
    private $fields = 'id,order_sn,user_id,user_type,from_status,to_status,remark,create_time,update_time,status';

    public function ModelAdd($orderSn,$userId,$userType,$fromStatus,$toStatus,$remark) {
        $set = array();
        $set[] = " order_sn = '{$orderSn}'";
        $set[] = " user_id = {$userId}";
        $set[] = " user_type = {$userType}";
        $set[] = " from_status = '{$fromStatus}'";
        $set[] = " to_status = '{$toStatus}'";
        $set[] = " remark = '{$remark}'";
        $set[] = " status = 1";
        $set[] = " create_time = now()";
        $set[] = " update_time = now()";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $set);
        return $ret;
    }

    public function ModelList($orderSn){
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}' AND status = 1";
        $orderby = "id ASC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        $customerModel = get_load_model('customer');
        $purchaseModel = get_load_model('purchase');
        foreach ($list as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['userType'] = '';
            if($val['user_type'] == 1){
                $dataval[$key]['userType'] = '用户';
                $customer = $customerModel->ModelInfo($val['user_id']);
                $dataval[$key]['username'] = $customer['username'];
            }elseif ($val['user_type'] == 2){
                $dataval[$key]['userType'] = '买手';
                $purchase = $purchaseModel->BcModelInfo($val['user_id']);
                $dataval[$key]['username'] = $purchase['username'];
            }elseif ($val['user_type'] == 3){
                $dataval[$key]['userType'] = '运营';
                $purchase = $purchaseModel->BcModelInfo($val['user_id']);
                $dataval[$key]['username'] = $purchase['username'];
            }elseif ($val['user_type'] == 4){
                $dataval[$key]['userType'] = '管理员';
                $purchase = $purchaseModel->BcModelInfo($val['user_id']);
                $dataval[$key]['username'] = $purchase['username'];
            }
            $dataval[$key]['orderStatus'] = '';
            if($val['to_status'] == 'waitforpay'){
                $dataval[$key]['orderStatus'] = '待支付';
            }elseif ($val['to_status'] == 'pendingDelivery'){
                $dataval[$key]['orderStatus'] = '待发货';
            }elseif ($val['to_status'] == 'delivered'){
                $dataval[$key]['orderStatus'] = '待发货';
            }elseif ($val['to_status'] == 'complete'){
                $dataval[$key]['orderStatus'] = '已完成';
            }elseif ($val['to_status'] == 'cancel'){
                $dataval[$key]['orderStatus'] = '已取消';
            }elseif ($val['to_status'] == 'refunding'){
                $dataval[$key]['orderStatus'] = '退款中';
            }elseif ($val['to_status'] == 'refundSuccess'){
                $dataval[$key]['orderStatus'] = '退款成功';
            }elseif ($val['to_status'] == 'refundError'){
                $dataval[$key]['orderStatus'] = '退款失败';
            }
            $dataval[$key]['remark'] = $val['remark'];
            $dataval[$key]['createTime'] = $val['create_time'];
        }
        return $dataval;
    }

    public function BcModelInfo($orderSn,$toStatus){
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}' AND to_status = '{$toStatus}' AND status = 1";
        $orderby = " id desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $orderby);
        if (empty($row)) {
            return $dataval;
        }
        return $row;
    }
}