<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/7
 * Time: 13:32
 */
class RefundOrderModel{

    private $filepath = 'refund_order';
    private $fields = 'id,refund_sn,order_sn,user_id,price,order_status,remark,reason,user_visible,create_by,create_time,update_by,update_time,status ';

    public function BcModelAdd($data){
        $refundSn = 'RE'.date('YmdHis') . rand(10000, 99999);
        $setarray = array();
        $setarray[] = " refund_sn = '{$refundSn}'";
        $setarray[] = " order_sn = '{$data['orderSn']}'";
        $setarray[] = " user_id = {$data['userId']}";
        $setarray[] = " order_status = 'refunding'";
        $setarray[] = " remark = '{$data['remark']}'";
        $setarray[] = " create_by = {$data['createBy']}";
        $setarray[] = " update_by = {$data['createBy']}";
        $setarray[] = " status = 1";
        $setarray[] = " create_time = now()";
        $setarray[] = " update_time = now()";

        $insertId = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $setarray);
        if ($insertId > 0) {
            $orderItems = get_load_model('refundOrderItems');
            $orderItemsData = array(
                'refundSn'       => $refundSn,
                'orderSn'       => $data['orderSn'],
                'productId'     => $data['pro']['productId'],
                'skuId'         => $data['pro']['skuId'],
                'productType'  => $data['pro']['productType'],
                'productName'  => $data['pro']['productName'],
                'model'         => $data['pro']['model'],
                'attr'         => $data['pro']['attr'],
                'productIcon'  => $data['pro']['productIcon'],
                'productPrice' => $data['pro']['productPrice'],
                'num'           => $data['pro']['num'],
            );
            $orderItems->ModelAdd($orderItemsData);
            return $refundSn;
        } else {
            return false;
        }
    }

    public function BcModelList($refundSn,$orderSn,$orderStatus,$phone,$offset,$max){
        $dataval = array();
        $where = " AND status = 1";
        if(!empty($refundSn)){
            $where.= " AND refund_sn LIKE '%{$refundSn}%'";
        }
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
        $orderby = " id DESC";
        $limit = " LIMIT {$offset}, {$max}";
        $orderlist = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($orderlist)) {
            return $dataval;
        }
        $orderItemsModel = get_load_model('refundOrderItems');
        $customerModel = get_load_model('customer');
        foreach ($orderlist as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['userId'] = intval($val['user_id']);
            $customer = $customerModel->ModelInfo($val['user_id']);
            $dataval[$key]['nickname'] = $customer['nickname'];
            $dataval[$key]['refundSn'] = $val['refund_sn'];
            $dataval[$key]['orderSn'] = $val['order_sn'];
            $dataval[$key]['orderStatus'] = "";
            switch ($val['order_status']){
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
            $dataval[$key]['createTime'] = $val['create_time'];
            $dataval[$key]['updateTime'] = $val['update_time'];
            $items = $orderItemsModel->BcModelList($val['refund_sn']);
            $price = 0;
            if(!empty($items)){
                foreach ($items as $k => $v) {
                    $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
                }
            }
            $dataval[$key]['items'] = $items;
            $dataval[$key]['price'] = doubleval($price);
        }
        $data = array();
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        $data['total'] = intval($total);
        $data['list'] = $dataval;
        return $data;
    }

    public function BcModelInfo($refundSn){
        $dataval = array();
        $where = " AND refund_sn = '{$refundSn}'";
        $order = "id desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
        if (empty($row)) {
            return $dataval;
        }

        //订单基本信息
        $orderItemsModel = get_load_model('refundOrderItems');
        $dataval['id'] = intval($row['id']);
        $dataval['refundSn'] = $row['refund_sn'];
        $dataval['orderSn'] = $row['order_sn'];
        //用户信息
        $customerModel = get_load_model('customer');
        $customer = $customerModel->ModelInfo($row['user_id']);
        $dataval['nickname'] = $customer['nickname'];
        $dataval['orderStatus'] = $row['order_status'];
        $dataval['remark'] = $row['remark'];

        //支付方式
        $payInfoModel = get_load_model('payInfo');
        $payInfo = $payInfoModel->BcModelInfoByOrderSnAndPayed($row['order_sn']);
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
        $dataval['items'] = $orderItemsModel->BcModelList($row['refund_sn']);
        if(!empty($dataval['items'])){
            foreach ($dataval['items'] as $k => $v) {
                $dataval['items'][$k]['total'] = doubleval(bcmul($v['productPrice'],$v['num'],2));
            }
        }
        //订单总价，订单快递费，已退款
        $dataval['productTotal'] = 0;
        $dataval['distributionFee'] = 0;
        $dataval['orderTotal'] = 0;
        $dataval['hasRefund'] = $this->BcModelGetRefundPrice($row['order_sn']);
        $info = array();
        if(strstr($row['order_sn'],'CO')){
            $orderModel = get_load_model('order');
            $info = $orderModel->BcModelInfoToRefund($row['order_sn']);
        }elseif (strstr($row['order_sn'],'YY')){
            $bespeakOrderModel = get_load_model('bespeakOrder');
            $info = $bespeakOrderModel->BcModelInfoToRefund($row['order_sn']);
        }else{

        }
        if(!empty($info)){
            $dataval['productTotal'] = doubleval($info['productTotal']);
            $dataval['distributionFee'] = doubleval($info['distributionFee']);
            $dataval['orderTotal'] = doubleval($info['orderTotal']);
        }
        return $dataval;
    }

    public function BcModelReject($refundSn,$reason,$updateBy){
        $where = " AND refund_sn = '{$refundSn}' AND status = 1 AND order_status = 'refunding'";
        $orderrow = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($orderrow)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'refundError' ";
        $set[] = " reason = '{$reason}' ";
        $set[] = " update_by = {$updateBy}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelRefundingInfoByRefundSn($refundSn){
        $dataval = array();
        $where = " AND refund_sn = '{$refundSn}' AND order_status = 'refunding' AND status = 1";
        $order = "id desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
        if (empty($row)) {
            return $dataval;
        }
        return $row;
    }

    public function ModelList($offset,$max,$userId){
        $dataval = array();
        $where = " AND status = 1 AND user_id = {$userId} AND user_visible = 1";
        $orderby = " id DESC";
        $limit = " LIMIT {$offset}, {$max}";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        $orderItemsModel = get_load_model('refundOrderItems');
        foreach ($list as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['refundSn'] = $val['refund_sn'];
            $dataval[$key]['orderSn'] = $val['order_sn'];
            $dataval[$key]['orderStatus'] = $val['order_status'];
            $items = $orderItemsModel->ModelList($val['refund_sn']);
            $dataval[$key]['items'] = $items;
            $price = 0;
            if(!empty($dataval[$key]['items'])){
                foreach ($dataval[$key]['items'] as $k => $v) {
                    $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
                }
            }
            $dataval[$key]['price'] = doubleval($price);
        }
        return $dataval;
    }

    public function ModelOwner($refundSn, $userid) {
        $where = " AND refund_sn = '{$refundSn}' AND user_id = {$userid}";
        $count = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        return $count > 0;
    }

    public function ModelGetInfoByRefundSn($refundSn){
        $dataval = array();
        $where = " AND refund_sn = '{$refundSn}' ";
        $order = "id desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
        if (empty($row)) {
            return $dataval;
        }

        //订单基本信息
        $orderItemsModel = get_load_model('refundOrderItems');
        $dataval['refundSn'] = $row['refund_sn'];
        $dataval['orderSn'] = $row['order_sn'];
        $dataval['orderStatus'] = $row['order_status'];
        $dataval['remark'] = $row['remark'];
        $dataval['reason'] = $row['reason'];
        $dataval['createTime'] = $row['create_time'];

        //商品信息
        $dataval['items'] = $orderItemsModel->ModelList($refundSn);
        $price = 0;
        if(!empty($dataval['items'])){
            foreach ($dataval['items'] as $k => $v) {
                $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
            }
        }
        $dataval['total'] = doubleval($price);
        //订单历史
        $orderLogModel = get_load_model('orderLog');
        $list = $orderLogModel->ModelList($refundSn);
        $dataval['history'] = $list;
        return $dataval;
    }

    public function ModelAdd($data){
        $refundSn = 'RE'.date('YmdHis') . rand(10000, 99999);
        $setarray = array();
        $setarray[] = " refund_sn = '{$refundSn}'";
        $setarray[] = " order_sn = '{$data['orderSn']}'";
        $setarray[] = " user_id = {$data['userId']}";
        $setarray[] = " order_status = 'refunding'";
        $setarray[] = " remark = '{$data['remark']}'";
        $setarray[] = " create_by = {$data['createBy']}";
        $setarray[] = " update_by = {$data['createBy']}";
        $setarray[] = " status = 1";
        $setarray[] = " create_time = now()";
        $setarray[] = " update_time = now()";

        $insertId = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $setarray);
        if ($insertId > 0) {
            $orderItems = get_load_model('refundOrderItems');
            if(!empty($data['items']) && sizeof($data['items']) > 0){
                foreach ($data['items'] as $val){
                    $orderItemsData = array(
                        'refundSn'       => $refundSn,
                        'orderSn'       => $data['orderSn'],
                        'productId'     => $val['productId'],
                        'skuId'         => $val['skuId'],
                        'productType'  => $val['productType'],
                        'productName'  => $val['productName'],
                        'model'         => $val['model'],
                        'attr'          => $val['attr'],
                        'productIcon'  => $val['productIcon'],
                        'productPrice' => $val['productPrice'],
                        'num'           => $val['num'],
                    );
                    $orderItems->ModelAdd($orderItemsData);
                }
            }
            return $refundSn;
        } else {
            return false;
        }
    }

    public function BcModelRefundSuccess($refundSn,$price = 0){
        $where = " AND refund_sn = '{$refundSn}' AND status = 1 AND order_status = 'refunding'";
        $orderrow = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($orderrow)) {
            return false;
        }
        $set = array();
        $set[] = " order_status = 'refundSuccess' ";
        $set[] = " price = {$price} ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelGetRefundPrice($orderSn){
        $sum = 0;
        $where = " AND status = 1 AND order_sn = '{$orderSn}' AND order_status = 'refundSuccess'";
        $orderby = " id DESC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return doubleval($sum);
        }
        foreach ($list as $k => $v) {
            $sum = bcadd($v['price'],$sum,2);
        }
        return doubleval($sum);
    }
}