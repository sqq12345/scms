<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/27
 * Time: 14:47
 */
class ReBespeakProductSkuModel{
    private $filepath = 're_bespeak_product_sku';
    private $fields = 'id, pid, model,attr,img,detail_img, ref, stock, price, duty_free_price, cost_price, min_purchase_num, limit_num, limit_time, create_by, create_time, update_by, update_time, status';

    public function BcModelAdd($pid,$model,$attr,$img,$ref,$stock,$price,$dutyFreePrice,$costPrice,$minPurchaseNum,$limitNum,$limitTime,$status,$createBy,$datailImg = '',$vip_price, $tax, $weight, $express_free, $level, $commend, $sort, $sales){
        $set = array();
        $set[] = "pid = {$pid}";
        $set[] = "model = '{$model}'";
        $set[] = "attr = '{$attr}'";
        $set[] = "img = '{$img}'";
        $set[] = "detail_img = '{$datailImg}'";
        $set[] = "ref = '{$ref}'";
        $set[] = "stock = {$stock}";
        $set[] = "price = {$price}";
        $set[] = "duty_free_price = {$dutyFreePrice}";
        $set[] = "cost_price = {$costPrice}";
        $set[] = "min_purchase_num = {$minPurchaseNum}";
        $set[] = "limit_time = {$limitTime}";
        $set[] = "limit_num = {$limitNum}";
        $set[] = "create_by = {$createBy}";
        $set[] = "create_time = now()";
        $set[] = "update_by = 0";
        $set[] = "status = {$status}";

        //后加的字段
        $set[] = "vip_price = '{$vip_price}'";
        $set[] = "tax = '{$tax}'";
        $set[] = "weight = '{$weight}'";
        $set[] = "express_free = '{$express_free}'";
        $set[] = "level = '{$level}'";
        $set[] = "commend = '{$commend}'";
        $set[] = "sort = '{$sort}'";
        $set[] = "sales = '{$sales}'";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $set);
        return $ret;
    }

    public function BcModelUpdate($set, $id) {
        $where = "AND id = {$id} ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelUpdateSku($id,$model,$attr,$img,$ref,$stock,$price,$dutyFreePrice,$costPrice,$minPurchaseNum,$limitNum,$limitTime,$status,$userid,$detailImg = '',$vip_price, $tax, $weight, $express_free, $level, $commend, $sort, $sales){
        $set = array();
        $set[] = "attr = '{$attr}'";
        if(!empty($model)){
            $set[] = "model = '{$model}'";
        }
        if(!empty($img)){
            $set[] = "img = '{$img}'";
        }
        if(!empty($detailImg)){
            $set[] = "detail_img = '{$detailImg}'";
        }
        $set[] = "ref = '{$ref}'";
        $set[] = "stock = {$stock}";
        $set[] = "price = {$price}";
        $set[] = "duty_free_price = {$dutyFreePrice}";
        $set[] = "cost_price = {$costPrice}";
        $set[] = "min_purchase_num = {$minPurchaseNum}";
        $set[] = "limit_time = {$limitTime}";
        $set[] = "limit_num = {$limitNum}";
        $set[] = "update_by = {$userid}";
        $set[] = "status = {$status}";

        //后加的字段
        $set[] = "vip_price = '{$vip_price}'";
        $set[] = "tax = '{$tax}'";
        $set[] = "weight = '{$weight}'";
        $set[] = "express_free = '{$express_free}'";
        $set[] = "level = '{$level}'";
        $set[] = "commend = '{$commend}'";
        $set[] = "sort = '{$sort}'";
        $set[] = "sales = '{$sales}'";
        $where = "AND id = {$id} ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelListByPid($pid){
        $dataval = array();
        $where = " AND pid = {$pid} AND status != 2";
        $orderby = "id ASC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['pid'] = intval($val['pid']);
            $dataval[$key]['model'] = $val['model'];
            $dataval[$key]['attr'] = $val['attr'];
            $dataval[$key]['ref'] = $val['ref'];
            $dataval[$key]['img'] = $val['img'];
            $dataval[$key]['detailImg'] = $val['detail_img'];
            $dataval[$key]['stock'] = intval($val['stock']);
            $dataval[$key]['price'] = doubleval($val['price']);
            $dataval[$key]['dutyFreePrice'] = doubleval($val['duty_free_price']);
            $dataval[$key]['costPrice'] = doubleval($val['cost_price']);
            $dataval[$key]['minPurchaseNum'] = intval($val['min_purchase_num']);
            $dataval[$key]['limitTime'] = intval($val['limit_time']);
            $dataval[$key]['limitNum'] = intval($val['limit_num']);
            $dataval[$key]['status'] = intval($val['status']);
        }
        return $dataval;
    }

    /**
     * 根据id,num,operation 来更新库存；
     * @param $id ：商品的id
     * @param $num : 变更数量
     * @param $operation ：运算符（1:加法，2：减法）
     */
    public function ModelUpdateStock($id,$num,$operation){
        $where = " AND id = {$id} AND status = 1 ";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row) || $num <= 0){
            return false;
        }
        $dbstock = $row['stock'];
        $newstock = $dbstock;//设置默认值

        if($operation == 1){
            $newstock = $dbstock + $num;
        }
        elseif($operation == 2 && $dbstock >= $num){
            $newstock = $dbstock - $num;
        }else{
            return false;
        }
        $setarray = array();
        $setarray[] = " stock = {$newstock} ";
        $setarray[] = " update_time = now() ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        return $ret;
    }

    public function BcModelInfoByRef($ref){
        $dataval = array();
        $where = " AND ref = '{$ref}'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }

    public function BcModelInfoByIdAndRef($id,$ref){
        $dataval = array();
        $where = " AND ref = '{$ref}' AND id != {$id} AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }

    public function BcModelInfoById($id){
        $dataval = array();
        $where = " AND id = {$id} AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['pid'] = intval($row['pid']);
        $dataval['model'] = $row['model'];
        $dataval['attr'] = $row['attr'];
        $dataval['img'] = $row['img'];
        $dataval['ref'] = $row['ref'];
        $dataval['stock'] = intval($row['stock']);
        $dataval['price'] = doubleval($row['price']);
        $dataval['dutyFreePrice'] = doubleval($row['duty_free_price']);
        $dataval['costPrice'] = doubleval($row['cost_price']);
        $dataval['minPurchaseNum'] = intval($row['min_purchase_num']);
        $dataval['limitTime'] = intval($row['limit_time']);
        $dataval['limitNum'] = intval($row['limit_num']);
        return $dataval;
    }

    public function BcModelMiniListByPid($pid){
        $dataval = array();
        $where = " AND pid = {$pid} AND status = 1";
        $orderby = "id ASC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['attr'] = $val['attr'];
        }
        return $dataval;
    }

    public function ModelGetMinPriceByPid($pid){
        $where = " AND pid = {$pid} AND status = 1";
        $min = $GLOBALS['DB']->getSelectMin(ECHO_AQL_SWITCH, 'price',$this->filepath, $where);
        return $min;
    }

    public function ModelGetMaxDutyFreePriceByPid($pid){
        $where = " AND pid = {$pid} AND status = 1";
        $min = $GLOBALS['DB']->getSelectMax(ECHO_AQL_SWITCH, 'duty_free_price',$this->filepath, $where);
        return $min;
    }

    public function ModelSkuListByPid($pid){
        $dataval = array();
        $where = " AND status = 1 AND pid = {$pid}";
        $orderby = "attr ASC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
        if(empty($list)){
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['skuId'] = intval($val['id']);
            $dataval[$key]['pid'] = intval($val['pid']);
            $dataval[$key]['model'] = $val['model'];
            $dataval[$key]['img'] = $val['img'];
            $dataval[$key]['detailImg'] = $val['detail_img'];
            $dataval[$key]['attr'] = $val['attr'];
            $dataval[$key]['stock'] = intval($val['stock']);
            $dataval[$key]['price'] = doubleval($val['price']);
            $dataval[$key]['dutyFreePrice'] = doubleval($val['duty_free_price']);
        }
        return $dataval;
    }

    public function ModelInfoById($id){
        $dataval = array();
        $where = " AND id = {$id} AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['pid'] = intval($row['pid']);
        $dataval['model'] = $row['model'];
        $dataval['attr'] = $row['attr'];
        $dataval['img'] = $row['img'];
        $dataval['ref'] = $row['ref'];
        $dataval['stock'] = intval($row['stock']);
        $dataval['price'] = doubleval($row['price']);
        $dataval['dutyFreePrice'] = doubleval($row['duty_free_price']);
        $dataval['costPrice'] = doubleval($row['cost_price']);
        $dataval['minPurchaseNum'] = intval($row['min_purchase_num']);
        $dataval['limitTime'] = intval($row['limit_time']);
        $dataval['limitNum'] = intval($row['limit_num']);
        return $dataval;
    }

    public function ModelInfoByIdAndPid($id,$pid){
        $dataval = array();
        $where = " AND id = {$id} AND status = 1 AND pid = {$pid}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['pid'] = intval($row['pid']);
        $dataval['model'] = $row['model'];
        $dataval['attr'] = $row['attr'];
        $dataval['img'] = $row['img'];
        $dataval['ref'] = $row['ref'];
        $dataval['ref'] = $row['ref'];
        $dataval['stock'] = intval($row['stock']);
        $dataval['price'] = doubleval($row['price']);
        $dataval['dutyFreePrice'] = doubleval($row['duty_free_price']);
        $dataval['costPrice'] = doubleval($row['cost_price']);
        $dataval['minPurchaseNum'] = intval($row['min_purchase_num']);
        $dataval['limitTime'] = intval($row['limit_time']);
        $dataval['limitNum'] = intval($row['limit_num']);
        return $dataval;
    }
}