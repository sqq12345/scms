<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/27
 * Time: 14:39
 */
class ReBespeakProductModel{
    private $filepath = 're_bespeak_product';
    private $fields = 'id, name, info,category_ids,brand_id,shortage_display,is_reduce_stock,distribution_fee,hot_level,good_icon,good_img,
        create_by,create_time,update_by,update_time,status';

    public function BcModelAdd($name,$info,$category_ids,$brand_id,$shortage_display,$is_reduce_stock,$distribution_fee,$hot_level,$good_icon,$good_img,$createBy){
        $set = array();
        $set[] = "name = '{$name}'";
        $set[] = "info = '{$info}'";
        $set[] = "category_ids = '{$category_ids}'";
        $set[] = "brand_id = {$brand_id}";
        $set[] = "shortage_display = '{$shortage_display}'";
        $set[] = "is_reduce_stock = {$is_reduce_stock}";
        $set[] = "distribution_fee = {$distribution_fee}";
        $set[] = "hot_level = {$hot_level}";
        $set[] = "good_icon = '{$good_icon}'";
        $set[] = "good_img = '{$good_img}'";
        $set[] = "create_by = {$createBy}";
        $set[] = "update_by = 0";
        $set[] = "create_time = now()";
        $set[] = "status = 0";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $set);
        return $ret;
    }

    public function BcModelMiniInfo($id){
        $dataval = array();
        $where = " AND id = {$id}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['name'] = $row['name'];
        $dataval['info'] = $row['info'];
        $dataval['isReduceStock'] = intval($row['is_reduce_stock']);
        $dataval['distributionFee'] = doubleval($row['distribution_fee']);
        $dataval['goodIcon'] = $row['good_icon'];
        $dataval['status'] = intval($row['status']);
        return $dataval;
    }

    public function BcModelUpdateInfo($id,$name,$info,$list,$brandId,$shortageDisplay,$isReduceStock,$distributionFee,$hotLevel,$goodIcon,$goodImg,$updateBy){
        $set = array();
        $set[] = "name = '{$name}'";
        $set[] = "info = '{$info}'";
        $set[] = "category_ids = '{$list}'";
        $set[] = "brand_id = {$brandId}";
        $set[] = "shortage_display = '{$shortageDisplay}'";
        $set[] = "is_reduce_stock = {$isReduceStock}";
        $set[] = "distribution_fee = {$distributionFee}";
        $set[] = "hot_level = {$hotLevel}";
        $set[] = "good_icon = '{$goodIcon}'";
        $set[] = "good_img = '{$goodImg}'";
        $set[] = "update_by = {$updateBy}";
        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelUpdate($set, $id) {
        $where = "AND id = {$id} ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelInfo($id){
        $dataval = array();
        $where = " AND id = {$id}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['name'] = $row['name'];
        $dataval['info'] = $row['info'];
        $dataval['brandId'] = intval($row['brand_id']);
        $dataval['categoryIds'] = json_decode('['.substr($row['category_ids'],1,-1).']',true);
        $dataval['isReduceStock'] = intval($row['is_reduce_stock']);
        $dataval['hotLevel'] = intval($row['hot_level']);
        $dataval['distributionFee'] = doubleval($row['distribution_fee']);
        $dataval['shortageDisplay'] = $row['shortage_display'];
        $dataval['goodIcon'] = $row['good_icon'];
        $dataval['goodImg'] = json_decode($row['good_img'],true);
        $dataval['status'] = intval($row['status']);
        return $dataval;
    }

    public function BcModelList($name,$brandId,$categoryId,$status,$offset,$max){
        $dataval = array();
        $where = "";
        if($brandId > 0){
            $where.= " AND brand_id = {$brandId}";
        }
        if($status >= 0){
            $where.= " AND status = {$status}";
        }
        if(!empty($name)){
            $where.= " AND name LIKE '%{$name}%'";
        }
        if($categoryId > 0){
            $where.= " AND category_ids LIKE '%,{$categoryId},%'";
        }
        $limit = " LIMIT {$offset}, {$max}";
        $orderby = "id asc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        $brandModel = get_load_model('brand');

        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
            $dataval[$key]['goodIcon'] = $val['good_icon'];
            $dataval[$key]['brandName'] = '';
            $brand = $brandModel->BcModelInfo($val['brand_id']);
            if(!empty($brand)){
                $dataval[$key]['brandName'] = $brand['name'];
            }
            $dataval[$key]['status'] = intval($val['status']);
        }
        $data = array();
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        $data['total'] = intval($total);
        $data['list'] = $dataval;
        return $data;
    }

    public function BcModelListByName($name){
        $dataval = array();
        $where = " AND status = 1";
        if(!empty($name)){
            $where.= " AND name LIKE '%{$name}%'";
        }
        $orderby = "hot_level asc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }

        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
        }
        return $dataval;
    }

    public function ModelListByCidOrBidOrName($cid = 0,$bid = 0,$name,$offset,$max){
        $dataval = array();
        $where = " AND status = 1 ";
        if($cid > 0 && $bid == 0){
            $where.= " AND category_ids LIKE '%,{$cid},%'";
        }elseif ($cid == 0 && $bid > 0){
            $where.= " AND brand_id = {$bid}";
        }
        if(!empty($name)){
            $where.= " AND name LIKE '%{$name}%'";
        }
        $limit = " LIMIT {$offset}, {$max}";
        $orderby = "hot_level asc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        $skuModel = get_load_model('reBespeakProductSku');
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
            $dataval[$key]['goodIcon'] = $val['good_icon'];
            $dataval[$key]['price'] = doubleval($skuModel->ModelGetMinPriceByPid($val['id']));
            $dataval[$key]['dutyFreePrice'] = doubleval($skuModel->ModelGetMaxDutyFreePriceByPid($val['id']));
        }
        return $dataval;
    }

    public function ModelMiniInfo($id){
        $dataval = array();
        $where = " AND id = {$id} AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['name'] = $row['name'];
        $dataval['stock'] = intval($row['stock']);
        $dataval['isReduceStock'] = intval($row['is_reduce_stock']);
        $dataval['distributionFee'] = doubleval($row['distribution_fee']);
        $dataval['goodIcon'] = $row['good_icon'];
        return $dataval;
    }
}