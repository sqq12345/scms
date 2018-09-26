<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/11
 * Time: 14:21
 */
class ProductSkuModel{
    private $filepath = 'product_sku';
    private $fields = 'id, pid, model1,attr1,model2,attr2, stock, price, create_time, update_time, status';

    public function BcModelAdd($pid,$model1,$attr1,$model2,$attr2,$stock,$price){
        $set = array();
        $set[] = "pid = {$pid}";
        $set[] = "model1 = '{$model1}'";
        $set[] = "attr1 = '{$attr1}'";
        $set[] = "model2 = '{$model2}'";
        $set[] = "attr2 = '{$attr2}'";
        $set[] = "stock = {$stock}";
        $set[] = "price = {$price}";
        $set[] = "create_time = now()";
        $set[] = "status = 1";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $set);
        return $ret;
    }

    public function BcModelListByPid($pid){
        $dataval = array();
        $where = " AND status = 1 AND pid = {$pid}";
        $orderby = "id ASC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['pid'] = intval($val['pid']);
            $dataval[$key]['model1'] = $val['model1'];
            $dataval[$key]['attr1'] = $val['attr1'];
            $dataval[$key]['model2'] = $val['model2'];
            $dataval[$key]['attr2'] = $val['attr2'];
            $dataval[$key]['stock'] = intval($val['stock']);
            $dataval[$key]['price'] = doubleval($val['price']);
        }
        return $dataval;
    }

    public function BcModelDelete($pid){
        $setarray = array();
        $setarray[] =  "status = 0";
        $where = " AND pid = {$pid} AND status = 1";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    public function ModelSkuListByPid($pid){
        $dataval = array();
        $where = " AND status = 1 AND pid = {$pid}";
        $orderby = "id ASC";
        $rows = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where,$orderby);
        if(empty($rows)){
            return $dataval;
        }
        $model1 = $rows['model1'];
        $model2 = $rows['model2'];
        $sku = array();
        if(!empty($model1)){
            $where = " AND status = 1 AND pid = {$pid} AND model1 = '{$model1}'";
            $orderby = "id ASC";
            $rows = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
            if(!empty($rows)){
                $sku['model1']['name'] = $model1;
                $list = array();
                foreach ($rows as $val){
                    if(!in_array($val['attr1'],$list)){
                        array_push($list,$val['attr1']);
                    }
                }
                $sku['model1']['attrs'] = $list;
            }
        }
        if(!empty($model2)){
            $where = " AND status = 1 AND pid = {$pid} AND model2 = '{$model2}'";
            $orderby = "id ASC";
            $rows = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
            if(!empty($rows)){
                $sku['model2']['name'] = $model2;
                $list = array();
                foreach ($rows as $val){
                    if(!in_array($val['attr2'],$list)){
                        array_push($list,$val['attr2']);
                    }
                }
                $sku['model2']['attrs'] = $list;
            }
        }
        $dataval['sku']= $sku;
        $where = " AND status = 1 AND pid = {$pid}";
        $orderby = "id ASC";
        $rows = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
        $list = array();
        if(!empty($rows)){
            foreach ($rows as $key=>$val){
                $list[$key]['id'] = intval($val['id']);
                $list[$key]['model1'] = $val['model1'];
                $list[$key]['attr1'] = $val['attr1'];
                $list[$key]['model2'] = $val['model2'];
                $list[$key]['attr2'] = $val['attr2'];
                $list[$key]['stock'] = intval($val['stock']);
                $list[$key]['price'] = doubleval($val['price']);
            }
        }
        $dataval['list'] = $list;
        return $dataval;
    }

    public function ModelSkuByPid($pid){
        $where = "AND status = 1 AND pid = {$pid}";
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        return $total;
    }

    public function ModelSkuByPidAndAttr($pid,$attr1,$attr2){
        $dataval = array();
        $where = " AND pid = {$pid} AND status = 1";
        if(!empty($attr1)){
            $where.= " AND attr1 = '{$attr1}'";
        }
        if(!empty($attr2)){
            $where.= " AND attr2 = '{$attr2}'";
        }
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
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
        $dataval['attr1'] = $row['attr1'];
        $dataval['attr2'] = $row['attr2'];
        $dataval['stock'] = intval($row['stock']);
        $dataval['price'] = doubleval($row['price']);
        return $dataval;
    }

    /**
     * 根据id,num,operation 来更新库存；
     * @param $id ：商品的id
     * @param $num : 变更数量
     * @param $operation ：运算符（1:加法，2：减法）
     */
    public function ModelUpdateStock($id,$num,$operation){
        $where = " AND id = {$id} AND status = 1";
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

    public function ModelTemplateListByPid($pid){
        $dataval = array();
        $where = " AND status = 1 AND pid = {$pid}";
        $orderby = "id ASC";
        $rows = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where,$orderby);
        if(empty($rows)){
            return $dataval;
        }
        $model1 = $rows['model1'];
        $model2 = $rows['model2'];
        $templateModel = get_load_model('template');
        if(!empty($model1)){
            $where = " AND status = 1 AND pid = {$pid} AND model1 = '{$model1}'";
            $orderby = "id ASC";
            $rows = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
            if(!empty($rows)){
                $info = $templateModel->BcModelInfoByName($model1);
                $dataval['model1']['id'] = intval($info['id']);
                $dataval['model1']['name'] = $model1;
                $list = array();
                foreach ($rows as $val){
                    if(!in_array($val['attr1'],$list)){
                        array_push($list,array(
                            'name'=>$val['attr1'],
                            'status'=>1
                        ));
                    }
                }
                $dataval['model1']['attrs'] = $list;
            }
        }
        if(!empty($model2)){
            $where = " AND status = 1 AND pid = {$pid} AND model2 = '{$model2}'";
            $orderby = "id ASC";
            $rows = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
            if(!empty($rows)){
                $info = $templateModel->BcModelInfoByName($model2);
                $dataval['model2']['id'] = intval($info['id']);
                $dataval['model2']['name'] = $model2;
                $list = array();
                foreach ($rows as $val){
                    if(!in_array($val['attr2'],$list)){
                        array_push($list,array(
                            'name'=>$val['attr2'],
                            'status'=>1
                        ));
                    }
                }
                $dataval['model2']['attrs'] = $list;
            }
        }
        return $dataval;
    }
}