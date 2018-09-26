<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/11
 * Time: 11:01
 */
class ProductModel{
    private $filepath = 'product';
    private $fields = 'id, name, info, price,duty_free_price,min_purchase_num,month_limit_num,category_id,brand_id,
        shortage_display,commodity_model,ref,stock,is_reduce_stock,distribution_fee,hot_level,good_status,good_icon,good_img,create_time,update_time,status';

    public function BcModelAdd($name, $info, $price,$duty_free_price,$min_purchase_num,$month_limit_num,$category_id,$brand_id,
                               $shortage_display,$commodity_model,$ref,$is_reduce_stock,$distribution_fee,$hot_level,$good_status,$good_icon,$good_img){
        $set = array();
        $set[] = "name = '{$name}'";
        $set[] = "info = '{$info}'";
        $set[] = "price = {$price}";
        $set[] = "duty_free_price = {$duty_free_price}";
        $set[] = "min_purchase_num = {$min_purchase_num}";
        $set[] = "month_limit_num = {$month_limit_num}";
        $set[] = "category_id = {$category_id}";
        $set[] = "brand_id = {$brand_id}";
        $set[] = "shortage_display = '{$shortage_display}'";
        $set[] = "commodity_model = '{$commodity_model}'";
        $set[] = "ref = '{$ref}'";
        $set[] = "stock = 0";
        $set[] = "is_reduce_stock = {$is_reduce_stock}";
        $set[] = "distribution_fee = {$distribution_fee}";
        $set[] = "hot_level = {$hot_level}";
        $set[] = "good_status = {$good_status}";
        $set[] = "good_icon = '{$good_icon}'";
        $set[] = "good_img = '{$good_img}'";
        $set[] = "create_time = now()";
        $set[] = "status = 1";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $set);
        return $ret;
    }

    public function BcModelInfo($id){
        $dataval = array();
        $where = " AND id = {$id} AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['name'] = $row['name'];
        $dataval['info'] = $row['info'];
        $dataval['categoryId'] = intval($row['category_id']);
        $dataval['brandId'] = intval($row['brand_id']);
        $dataval['minPurchaseNum'] = intval($row['min_purchase_num']);
        $dataval['monthLimitNum'] = intval($row['month_limit_num']);
        $dataval['stock'] = intval($row['stock']);
        $dataval['isReduceStock'] = intval($row['is_reduce_stock']);
        $dataval['hotLevel'] = intval($row['hot_level']);
        $dataval['price'] = doubleval($row['price']);
        $dataval['dutyFreePrice'] = doubleval($row['duty_free_price']);
        $dataval['distributionFee'] = doubleval($row['distribution_fee']);
        $dataval['shortageDisplay'] = $row['shortage_display'];
        $dataval['commodityModel'] = $row['commodity_model'];
        $dataval['ref'] = $row['ref'];
        $dataval['goodStatus'] = intval($row['good_status']);
        $dataval['goodIcon'] = $row['good_icon'];
        $dataval['goodImg'] = $row['good_img'];
        return $dataval;
    }

    public function BcModelUpdate($set, $id) {
        $where = "AND id = {$id} ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelUpdateInfo($id,$name, $info, $price,$duty_free_price,$min_purchase_num,$month_limit_num,$category_id,$brand_id,
                               $shortage_display,$commodity_model,$ref,$is_reduce_stock,$distribution_fee,$hot_level,$good_status,$good_icon,$good_img){
        $set = array();
        $set[] = "name = '{$name}'";
        $set[] = "info = '{$info}'";
        $set[] = "price = {$price}";
        $set[] = "duty_free_price = {$duty_free_price}";
        $set[] = "min_purchase_num = {$min_purchase_num}";
        $set[] = "month_limit_num = {$month_limit_num}";
        $set[] = "category_id = {$category_id}";
        $set[] = "brand_id = {$brand_id}";
        $set[] = "shortage_display = '{$shortage_display}'";
        $set[] = "commodity_model = '{$commodity_model}'";
        $set[] = "ref = '{$ref}'";
        $set[] = "stock = 0";
        $set[] = "is_reduce_stock = {$is_reduce_stock}";
        $set[] = "distribution_fee = {$distribution_fee}";
        $set[] = "hot_level = {$hot_level}";
        $set[] = "good_status = {$good_status}";
        $set[] = "good_icon = '{$good_icon}'";
        $set[] = "good_img = '{$good_img}'";
        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelDelete($ids){
        $ids = json_encode($ids);
        $ids = str_replace("[","(",$ids);
        $ids = str_replace("]",")",$ids);
        $setarray = array();
        $setarray[] =  "status = 0";
        $where = " AND id in {$ids}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    public function BcModelList($name,$commodityModel,$priceMin,$priceMax,$categoryId,$status,$goodStatus,$offset,$max){
        $dataval = array();
        $where = " AND status = {$status}";
        if($goodStatus >= 0){
            $where.= " AND good_status = {$goodStatus}";
        }
        if(!empty($name)){
            $where.= " AND name LIKE '%{$name}%'";
        }
        if(!empty($commodityModel)){
            $where.= " AND commodity_model LIKE '%{$commodityModel}%'";
        }
        if($priceMin > 0 && $priceMax == 0){
            $where.= " AND price >= {$priceMin}";
        }elseif ($priceMin == 0 && $priceMax > 0){
            $where.= " AND price <= {$priceMax}";
        }elseif ($priceMin > 0 && $priceMax > 0){
            $where.= " AND ( price BETWEEN {$priceMin} AND {$priceMax} ) ";
        }
        if($categoryId > 0){
            $where.= " AND category_id = {$categoryId}";
        }
        $limit = " LIMIT {$offset}, {$max}";
        $orderby = "hot_level desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
            $dataval[$key]['goodIcon'] = $val['good_icon'];
            $dataval[$key]['commodityModel'] = $val['commodity_model'];
            $dataval[$key]['price'] = doubleval($val['price']);
            $dataval[$key]['stock'] = intval($val['stock']);
            $dataval[$key]['goodStatus'] = intval($val['good_status']);
        }
        $data = array();
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        $data['total'] = intval($total);
        $data['list'] = $dataval;
        return $data;
    }

    public function ModelListByCid($cid = 0,$offset,$max){
        $dataval = array();
        $where = " AND status = 1 AND good_status = 1";
        if($cid > 0){
            $where.= " AND category_id = {$cid}";
        }
        $limit = " LIMIT {$offset}, {$max}";
        $orderby = "hot_level desc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        $skuModel = get_load_model('productSku');
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
            $dataval[$key]['goodIcon'] = $val['good_icon'];
            $dataval[$key]['commodityModel'] = $val['commodity_model'];
            $dataval[$key]['price'] = doubleval($val['price']);
            $total = $skuModel->ModelSkuByPid($val['id']);
            if($total > 0){
                $dataval[$key]['haveSku'] = 1;
            }else{
                $dataval[$key]['haveSku'] = 0;
            }
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
        $dataval['minPurchaseNum'] = intval($row['min_purchase_num']);
        $dataval['monthLimitNum'] = intval($row['month_limit_num']);
        $dataval['stock'] = intval($row['stock']);
        $dataval['isReduceStock'] = intval($row['is_reduce_stock']);
        $dataval['price'] = doubleval($row['price']);
        $dataval['dutyFreePrice'] = doubleval($row['duty_free_price']);
        $dataval['distributionFee'] = doubleval($row['distribution_fee']);
        $dataval['commodityModel'] = $row['commodity_model'];
        $dataval['goodIcon'] = $row['good_icon'];
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
}