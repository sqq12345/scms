<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/6/27
 * Time: 10:45
 */
class SplitOrderModel{
    private $filepath = 'split_order';
    private $fields = 'id, order_sn,split_order_sn, user_id, dispatch_region,distribution_fee,province, city, area, address, code,receiver_name, receiver_phone, order_status, create_time, update_time, status';


    public function BcModelList($orderSn,$splitOrderSn,$orderStatus,$phone,$region,$offset,$max){
        $dataval = array();
        $where = " AND status = 1";
        if(!empty($orderSn)){
            $where.= " AND order_sn LIKE '%{$orderSn}%'";
        }
        if(!empty($splitOrderSn)){
            $where.= " AND split_order_sn LIKE '%{$splitOrderSn}%'";
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
        if(!empty($region)){
            $where.= " AND dispatch_region = '{$region}'";
        }
        $orderby = " id DESC";
        $limit = " LIMIT {$offset}, {$max}";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        $orderItemsModel = get_load_model('splitOrderItems');
        $customerModel = get_load_model('customer');
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['userId'] = intval($val['user_id']);
            $customer = $customerModel->ModelInfo($val['user_id']);
            $dataval[$key]['phone'] = $customer['username'];
            $dataval[$key]['orderSn'] = $val['order_sn'];
            $dataval[$key]['splitOrderSn'] = $val['split_order_sn'];
            $dataval[$key]['dispatchRegion'] = $val['dispatch_region'];
            $dataval[$key]['orderStatus'] = "";
            switch ($val['order_status']){
                case "pendingDelivery":
                    $dataval[$key]['orderStatus'] = "待发货";
                    break;
                case "delivered":
                    $dataval[$key]['orderStatus'] = "已发货";
                    break;
                case "complete":
                    $dataval[$key]['orderStatus'] = "已完成";
                    break;
                case "refused":
                    $dataval[$key]['orderStatus'] = "拒签";
                    break;
                case "cancel":
                    $dataval[$key]['orderStatus'] = "已取消";
                    break;
                default:
                    $dataval[$key]['orderStatus'] = "";
            }
            $items = $orderItemsModel->BcModelList($val['split_order_sn']);
            $price = 0;
            if(!empty($items)){
                foreach ($items as $k => $v) {
                    $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
                }
            }
            $dataval[$key]['items'] = $items;
            $dataval[$key]['price'] = doubleval(bcadd($price,$val['distribution_fee'],2));
            $dataval[$key]['address'] = $val['receiver_name'].' '.$val['receiver_phone'].' '.$val['province'].$val['city'].$val['area'].$val['address'];
        }
        $data = array();
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        $data['total'] = intval($total);
        $data['list'] = $dataval;
        return $data;
    }

    public function BcModelInfo($splitOrderSn){
        $dataval = array();
        $where = " AND split_order_sn = '{$splitOrderSn}'";
        $order = "id desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
        if (empty($row)) {
            return $dataval;
        }

        //订单基本信息
        $orderItemsModel = get_load_model('splitOrderItems');
        $dataval['id'] = intval($row['id']);
        $dataval['orderSn'] = $row['order_sn'];
        $dataval['splitOrderSn'] = $row['split_order_sn'];
        $dataval['orderStatus'] = $row['order_status'];
        switch ($row['order_status']){
            case "pendingDelivery":
                $dataval['orderStatus'] = "待发货";
                break;
            case "delivered":
                $dataval['orderStatus'] = "已发货";
                break;
            case "complete":
                $dataval['orderStatus'] = "已完成";
                break;
            case "refused":
                $dataval['orderStatus'] = "拒签";
                break;
            case "cancel":
                $dataval['orderStatus'] = "已取消";
                break;
            default:
                $dataval['orderStatus'] = $row['order_status'];
        }
        $dataval['distributionFee'] = doubleval($row['distribution_fee']);

        //用户信息
        $customerModel = get_load_model('customer');
        $customer = $customerModel->ModelInfo($row['user_id']);
        $dataval['nickname'] = $customer['nickname'];
        $dataval['phone'] = $customer['username'];
        $dataval['email'] = $customer['email'];

        //商品信息
        $dataval['items'] = $orderItemsModel->BcModelList($row['split_order_sn']);

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
        $orderDelivery = $deliveryModel->ModelInfo($splitOrderSn);
        $dataval['company'] = "";
        $dataval['deliverySn'] = "";
        $dataval['deliveryList'] = array();
        if(!empty($orderDelivery)){
            $dataval['deliverySn'] = $orderDelivery['deliverySn'];
            $dataval['company'] = $orderDelivery['company'];
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
        return $dataval;
    }

    public function BcModelStatusToDelivered($splitOrderSn){
        $dataval = array();
        $where = " AND split_order_sn = '{$splitOrderSn}' AND order_status = 'pendingDelivery' AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        $set = array();
        $set[] = " order_status = 'delivered' ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelMiNiInfo($splitOrderSn){
        $dataval = array();
        $where = " AND split_order_sn = '{$splitOrderSn}'";
        $order = "id desc";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where, $order);
        if (empty($row)) {
            return $dataval;
        }
        return $row;
    }

    public function BcModelInfoByDelivered($splitOrderSn){
        $dataval = array();
        $where = " AND split_order_sn = '{$splitOrderSn}' AND order_status in ('delivered','complete') ";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        return $row;
    }

    public function ModelListByOrderSn($orderSn){
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}' AND status = 1";
        $orderby = " id asc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if(empty($list)){
            return $dataval;
        }
        $itemsModel = get_load_model('splitOrderItems');
        $deliveryModel = get_load_model('orderDelivery');
        foreach ($list as $key=>$val){
            $items = $itemsModel->BcModelList($val['split_order_sn']);
            $price = 0;
            if(!empty($items)){
                foreach ($items as $k => $v) {
                    $price = bcadd(bcmul($v['productPrice'],$v['num'],2),$price,2);
                }
            }
            $dataval[$key]['splitOrderSn'] = $val['split_order_sn'];
            $dataval[$key]['orderStatus'] = $val['order_status'];
//            switch ($val['order_status']){
//                case "pendingDelivery":
//                    $dataval[$key]['orderStatus'] = "待发货";
//                    break;
//                case "delivered":
//                    $dataval[$key]['orderStatus'] = "已发货";
//                    break;
//                case "complete":
//                    $dataval[$key]['orderStatus'] = "已完成";
//                    break;
//                case "refused":
//                    $dataval[$key]['orderStatus'] = "拒签";
//                    break;
//                case "cancel":
//                    $dataval[$key]['orderStatus'] = "已取消";
//                    break;
//                default:
//                    $dataval[$key]['orderStatus'] = $val['order_status'];
//            }
            $dataval[$key]['price'] = doubleval(bcadd($price,$val['distribution_fee'],2));
            $dataval[$key]['distributionFee'] = doubleval($val['distribution_fee']);
            if(sizeof($items) > 1){
                $dataval[$key]['items'] = array_slice($items,0,1);
                $num = sizeof($items) - 1;
                $pro = array();
                $pro['id'] = "";
                $pro['productId'] = "";
                $pro['skuId'] = "";
                $pro['pType'] = "";
                $pro['productName'] = "还有其他".$num."件商品";
                $pro['brandName'] = "";
                $pro['model'] = "";
                $pro['attr'] = "";
                $pro['productIcon'] = "";
                $pro['productPrice'] = "";
                $pro['costPrice'] = "";
                $pro['num'] = "";
                array_push($dataval[$key]['items'],$pro);
            }else{
                $dataval[$key]['items'] = $items;
            }
            $dataval[$key]['list'] = array();
            if($val['order_status'] == 'delivered' || $val['order_status'] == 'complete'){
                $orderDelivery = $deliveryModel->ModelInfo($val['split_order_sn']);
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
                    if ($info['status'] == 200) {
                        $dataval[$key]['list'] = $info['data'];
                    }
                }
            }
        }
        return $dataval;
    }

    public function ModelUpdate($set, $orderSn){
        $where = "AND order_sn = '{$orderSn}' ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }
}