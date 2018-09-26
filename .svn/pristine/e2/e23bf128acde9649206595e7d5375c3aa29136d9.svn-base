<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/28
 * Time: 13:27
 */
class ReSpecialProductModel{

    private $filepath = 're_special_product';
    private $fields = 'id,type,pid,name,sku_id,price,priority,start_time,end_time,create_by,create_time,update_by,update_time';

    public function BcModelAdd($type,$pid,$name,$skuId,$price,$priority,$startTime,$endTime,$createBy){
        $set = array();
        $set[] = "type = {$type}";
        $set[] = "pid = {$pid}";
        $set[] = "name = '{$name}'";
        $set[] = "sku_id = {$skuId}";
        $set[] = "price = {$price}";
        $set[] = "priority = {$priority}";
        $set[] = "start_time = '{$startTime}'";
        $set[] = "end_time = '{$endTime}'";
        $set[] = "create_by = {$createBy}";
        $set[] = "create_time = now()";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH,2,$this->filepath,$set);
        return $ret;
    }

    public function BcModelUpdate($set, $id) {
        $where = "AND id = {$id} ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelList($name,$offset,$max){
        $dataval = array();
        date_default_timezone_set('PRC');
        $time = date('Y-m-d H:i:s');
        $where = " AND end_time >= '{$time}'";
        if(!empty($name)){
            $where.= " AND name LIKE '%{$name}%'";
        }
        $limit = " LIMIT {$offset}, {$max}";
        $orderby = "priority asc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        $skuModel = get_load_model('reProductSku');

        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['type'] = intval($val['type']);
            $dataval[$key]['pid'] = intval($val['pid']);
            $dataval[$key]['name'] = $val['name'];
            $dataval[$key]['skuId'] = intval($val['sku_id']);
            $dataval[$key]['price'] = doubleval($val['price']);
            $skuInfo = $skuModel->BcModelInfoById($val['sku_id']);
            $dataval[$key]['attr'] = '';
            $dataval[$key]['originalPrice'] = 0;
            $dataval[$key]['priority'] = intval($val['priority']);
            if(!empty($skuInfo)){
                $dataval[$key]['attr'] = $skuInfo['attr'];
                $dataval[$key]['originalPrice'] = $skuInfo['price'];
            }
            $dataval[$key]['status'] = '';
            if(strtotime($val['start_time']) > strtotime($time)){
                $dataval[$key]['status'] = '待生效';
            }else{
                $dataval[$key]['status'] = '已生效';
            }
        }
        $data = array();
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        $data['total'] = intval($total);
        $data['list'] = $dataval;
        return $data;
    }

    public function BcModelFindBySkuIdAndTime($type,$skuId){
        $dataval = array();
        date_default_timezone_set('PRC');
        $time = date('Y-m-d H:i:s');
        $where = " AND type = {$type} AND sku_id = {$skuId} AND end_time >= '{$time}'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        return $row;
    }

    public function ModelFindBySkuIdAndTime($skuId){
        $dataval = array();
        date_default_timezone_set('PRC');
        $time = date('Y-m-d H:i:s');
        $where = " AND sku_id = {$skuId} AND start_time <= '{$time}' AND end_time >= '{$time}'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        return $row;
    }

    public function BcModelInfo($id){
        $dataval = array();
        $where = " AND id = {$id}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        $skuModel = get_load_model('reProductSku');
        $dataval['id'] = intval($row['id']);
        $dataval['type'] = intval($row['type']);
        $dataval['name'] = $row['name'];
        $dataval['attr'] = '';
        $dataval['price'] = doubleval($row['price']);
        $dataval['originalPrice'] = 0;
        $skuInfo = $skuModel->BcModelInfoById($row['sku_id']);
        if(!empty($skuInfo)){
            $dataval['attr'] = $skuInfo['attr'];
            $dataval['originalPrice'] = $skuInfo['price'];
        }
        $dataval['priority'] = intval($row['priority']);
        $dataval['startTime'] = $row['start_time'];
        $dataval['endTime'] = $row['end_time'];
        return $dataval;
    }

    public function ModelFindByPidAndTime($pid){
        $dataval = array();
        date_default_timezone_set('PRC');
        $time = date('Y-m-d H:i:s');
        $where = " AND pid = {$pid} AND start_time <= '{$time}' AND end_time >= '{$time}'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where,'price asc');
        if (empty($row)) {
            return $dataval;
        }
        return $row;
    }

    public function ModelList($offset, $max){
        $dataval = array();
        date_default_timezone_set('PRC');
        $time = date('Y-m-d H:i:s');
        $where = " AND start_time <= '{$time}' AND end_time >= '$time' ";
        $limit = " LIMIT {$offset}, {$max}";
        $orderby = "priority asc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby,$limit);
        if (empty($list)) {
            return $dataval;
        }
        $productModel = get_load_model('reProduct');
        $skuModel = get_load_model('reProductSku');
        $brandModel = get_load_model('brand');
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['pid']);
            $dataval[$key]['pid'] = intval($val['pid']);
            $dataval[$key]['skuId'] = intval($val['sku_id']);
            $dataval[$key]['name'] = $val['name'];
            $dataval[$key]['goodIcon'] = '';
            $dataval[$key]['price'] = doubleval($val['price']);
            $dataval[$key]['original_Price'] = 0;
            $dataval[$key]['model'] = '';
            $dataval[$key]['attr'] = '';
            $dataval[$key]['stock'] = 0;
            $product = $productModel->ModelMiniInfo($val['pid']);
            if(!empty($product)){
                $brand = $brandModel->BcModelInfo($product['brandId']);
                if(!empty($brand)){
                    $dataval[$key]['name'] = $brand['name'].' '.$val['name'];
                }
            }
            $sku = $skuModel->ModelInfoById($val['sku_id']);
            if(!empty($sku)){
                $dataval[$key]['original_Price'] = doubleval($sku['price']);
                $dataval[$key]['model'] = $sku['model'];
                $dataval[$key]['attr'] = $sku['attr'];
                $dataval[$key]['goodIcon'] = $sku['img'];
                $dataval[$key]['stock'] = intval($sku['stock']);
            }
        }
        return $dataval;
    }

    //秒杀
    public function getKillList($daydate, $max){
        $start = date('Y-m-d H:i:s',strtotime("{$daydate}"));
        $end = date('Y-m-d H:i:s',strtotime("{$daydate} +1day"));
        $sql = "SELECT a.*,b.price,b.attr,b.img,c.name,d.name as goodsname from product_second_kill a LEFT JOIN re_product_sku b on a.skuid = b.id LEFT JOIN re_product c on b.pid = c.id LEFT JOIN brand d on c.brand_id = d.id WHERE a.starttime >= '{$start}' and a.starttime <= '{$end}'";
        if($max){
            $sql .= "limit 0,{$max}";
        }
        $list = $GLOBALS['DB']->myquery($sql);
        return $list;
    }

    //根据SKUID和日期查找秒杀
    public function ModelSkillBySkuIdAndTime($skuId){
        $dataval = array();
        date_default_timezone_set('PRC');
        $time = date('Y-m-d H:i:s');
        $where = " AND skuid = {$skuId} AND starttime <= '{$time}' AND endtime >= '{$time}'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, 'product_second_kill', 'id,goodsid,skuid,type,price_limit,num,percent', $where);
        if (empty($row)) {
            return $dataval;
        }
        return $row;
    }

    //增加秒杀
    public function addSecondKill($ptype, $goodsid, $skuid, $price_limit, $num, $starttime, $endtime){
        $set = array();
        $set[] = "type = {$ptype}";
        $set[] = "goodsid = {$goodsid}";
        $set[] = "skuid = '{$skuid}'";
        $set[] = "price_limit = {$price_limit}";
        $set[] = "num = {$num}";
        $set[] = "starttime = {$starttime}";
        $set[] = "endtime = '{$endtime}'";
        $set[] = "createtime = now()";
        $set[] = "percent = 100";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH,2,'product_second_kill',$set);
        return $ret;
    }

    //删除秒杀
    public function delSecondKill($id){
        $where = " and id = {$id}";
        $ret = $GLOBALS['DB']->Set_Delete(ECHO_AQL_SWITCH,1,'product_second_kill',$where);
        if($ret){
            return $ret;
        }else{
            return false;
        }
    }

    //调整百分比
    public function updatePercent($perid, $percent){
        $where = "AND id = {$perid}";
        $set[] = "percent = '{$percent}'";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, 'product_second_kill', $set, $where);
        return $ret;
    }

    //更新秒杀库存
    public function ModelSkillUpdate($id, $num, $play = ''){
        $pdo = mydqlpdo();
        if($play == 1){
            $sql = "UPDATE product_second_kill SET num = num + {$num} where id = {$id}";
        }elseif($play == 2){
            $sql = "UPDATE product_second_kill SET num = num - {$num} where id = {$id}";
        }
        $ret = $pdo->exec($sql);
        return $ret;
    }
}