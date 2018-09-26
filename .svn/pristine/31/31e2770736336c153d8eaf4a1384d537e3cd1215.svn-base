<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/10
 * Time: 14:59
 */
class TemplateModel{
    private $filepath = 'template';
    private $fields = 'id, name,status';

    public function BcModelAdd($name){
        $ret = 0;
        $where = "AND name = '{$name}'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        if($row > 0){
            return $ret;
        }else{
            $set = array();
            $set[] = "name = '{$name}'";
            $set[] = "status = 1";
            $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH,2,$this->filepath,$set);
            return $ret;
        }
    }

    public function BcModelList($offset,$max){
        $dataval = array();
        $where = "";
        $orderby = "id ASC";
        $limit = " LIMIT {$offset}, {$max}";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby,$limit);
        if (empty($list)) {
            return $dataval;
        }
        $model = get_load_model('templateElement');
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
            $dataval[$key]['status'] = intval($val['status']);
            $elementList = $model->BcModelListByTid($val['id']);
            $dataval[$key]['list'] = $elementList;
        }
        $data = array();
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        $data['total'] = intval($total);
        $data['list'] = $dataval;
        return $data;
    }

    public function BcModelAllList(){
        $dataval = array();
        $where = " AND status = 1";
        $orderby = "id ASC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        $model = get_load_model('templateElement');
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
            $elementList = $model->BcModelListByTid($val['id']);
            $dataval[$key]['list'] = $elementList;
        }
        return $dataval;
    }

    public function BcModelDelete($id){
        $setarray = array();
        $setarray[] =  "status = 0";
        $where = " AND id = {$id} AND status = 1";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    public function BcModelUpdate($id,$name){
        $ret = 0;
        $where = "AND name = '{$name}' AND id != {$id} AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        if($row > 0){
            return $ret;
        }
        $setarray = array();
        if (!empty($name)) $setarray[] = "name = '{$name}'";

        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        return $ret;
    }

    public function BcModelUpdateStatus($id,$status){
        $setarray = array();
        $setarray[] =  "status = {$status}";
        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    public function BcModelInfoByName($name){
        $dataval = array();
        $where = "AND name = '{$name}' ";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }
}