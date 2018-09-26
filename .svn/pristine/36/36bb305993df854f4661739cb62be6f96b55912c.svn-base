<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/19
 * Time: 10:20
 */
class AssignOrderModel{
    //waitforAssign,buying,noGoods,alreadyPurchased
    private $filepath = 'assign_order';
    private $fields = 'id,buyer_id,order_sn,status,create_by,create_time,update_by,update_time ';

    public function ModelAdd($buyerId,$orderSn,$createBy){
        $ret = 0;
        $where = " AND order_sn = '{$orderSn}' AND status in ('buying','alreadyPurchased')";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        if($row > 0){
            $setArray = array();
            $setArray[] = "status = 'waitforAssign'";
            $setArray[] = "update_by = '{$createBy}'";
            $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setArray, $where);
        }
        $where = " AND order_sn = '{$orderSn}' AND buyer_id = {$buyerId}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        if($row > 0){
            $setArray = array();
            $setArray[] = "status = 'buying'";
            $setArray[] = "update_by = '{$createBy}'";
            $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setArray, $where);
        }else{
            $set = array();
            $set[] = "buyer_id = {$buyerId}";
            $set[] = "status = 'buying'";
            $set[] = "order_sn = '{$orderSn}'";
            $set[] = "create_by = {$createBy}";
            $set[] =  "create_time = now()";
            $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $set);
        }
        return $ret;
    }

    public function ModelFindByBidAndOrderSn($buyerId,$orderSn){
        $dataval = array();
        $where = " AND buyer_id = {$buyerId} AND order_sn = '{$orderSn}'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['buyerId'] = intval($row['buyer_id']);
        $dataval['status'] = $row['status'];
        $dataval['createBy'] = intval($row['create_by']);
        $dataval['createTime'] = $row['create_time'];
        $dataval['updateTime'] = $row['update_time'];
        return $dataval;
    }

    public function ModelUpdateToNoGoods($id,$buyerId){
        $where = " AND id = {$id} AND buyer_id = {$buyerId} AND status = 'buying'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return false;
        }
        $setArray = array();
        $setArray[] = "status = 'noGoods'";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setArray, $where);
        if($ret){
            $purchaseModel = get_load_model('purchase');
            $buyerIds = $purchaseModel->BcModelGetBuyerIds();
            $ids = $this->ModelListByOrderSn($row['order_sn']);
            $diffArray = array_diff($buyerIds,$ids);
            if(empty($diffArray)){
                //全部买手未买到，订单状态改为全部驳回
                $bespeakOrderModel = get_load_model('bespeakOrder');
                $ret = $bespeakOrderModel->ModelPengdingBuyToAllReject($row['order_sn']);
            }else{
                $key = array_rand($diffArray);
                $purchaseId = intval($diffArray[$key]);
                $set = array();
                $set[] = "buyer_id = {$purchaseId}";
                $set[] = "status = 'buying'";
                $set[] = "order_sn = '{$row['order_sn']}'";
                $set[] = "create_by = 0";
                $set[] =  "create_time = now()";
                $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $set);
            }
        }
        return $ret;

    }

    public function ModelListByOrderSn($orderSn){
        $dataval = array();
        $where = " AND order_sn = '{$orderSn}'";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $val){
            array_push($dataval,intval($val['buyer_id']));
        }
        return $dataval;
    }

    public function ModelUpdateToPurchased($id,$buyerId){
        $where = " AND id = {$id} AND buyer_id = {$buyerId} AND status = 'buying'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return false;
        }
        $setArray = array();
        $setArray[] = "status = 'alreadyPurchased'";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setArray, $where);
        if($ret){
            $bespeakOrderModel = get_load_model('bespeakOrder');
            $ret = $bespeakOrderModel->ModelPengdingBuyToWaitConfirm($row['order_sn']);
        }
        return $ret;
    }

    public function ModelList($buyerId,$offset,$max){
        $dataval = array();
        $where = " AND assign_order.status = 'buying' AND buyer_id = {$buyerId} AND order_status = 'pengdingBuy' AND assign_order.order_sn = bespeak_order.order_sn";
        $limit = " LIMIT {$offset}, {$max}";
        $orderby = "assign_order.id asc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, array('assign_order','bespeak_order'), 'assign_order.id,assign_order.order_sn,scare_buy_time', $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        $itemModel = get_load_model('bespeakOrderItems');
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $item = $itemModel->ModelInfoByOrderSn($val['order_sn']);
            $dataval[$key]['name'] = $item['product_name'];
            $dataval[$key]['model'] = $item['model'];
            $dataval[$key]['attr'] = $item['attr'];
//            $dataval[$key]['productIcon'] = $item['product_icon'];
//            $dataval[$key]['productPrice'] = doubleval($item['product_price']);
            $dataval[$key]['num'] = intval($item['num']);
            date_default_timezone_set('PRC');//使用PHP的date函数获取时间之前，先将时区设置为北京时区
            $nowTime = date('Y-m-d H:i:s');//获取当前时间
            $second = strtotime($val['scare_buy_time']) - strtotime($nowTime);
            $returnTime = $this->timeToString($second);
            $dataval[$key]['restTime'] = '剩余'.$returnTime;
        }

        return $dataval;
    }

    private function timeToString($second){
        $day = floor($second/(3600*24));
        $second = $second%(3600*24);//除去整天之后剩余的时间
        $hour = floor($second/3600);
        $second = $second%3600;//除去整小时之后剩余的时间
        $minute = floor($second/60);
        if($day > 0){
            return $day.'天'.$hour.'小时'.$minute.'分';
        }elseif ($hour > 0){
            return  $hour.'小时'.$minute.'分';
        }elseif ($minute > 0){
            return $minute.'分';
        }else{
            return '';
        }
    }
}