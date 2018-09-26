<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/10
 * Time: 17:38
 */
class TemplateElementModel{

    private $filepath = 'template_element';
    private $fields = 'id,tid,name,status';

    public function BcModelAdd($tid,$name){
        $ret = 0;
        $where = "AND name = '{$name}' AND tid = {$tid} AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        if($row > 0){
            return $ret;
        }
        $set = array();
        $set[] = "tid = {$tid}";
        $set[] = "name = '{$name}'";
        $set[] = "status = 1";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH,2,$this->filepath,$set);
        return $ret;
    }

    public function BcModelListByTid($tid){
        $dataval = array();
        $where = " AND tid = {$tid}";
        $orderby = " id ASC";
        $addresslist = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
        if (empty($addresslist)) {
            return $dataval;
        }
        foreach ($addresslist as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
        }
        return $dataval;
    }

    public function BcModelDelete($tid){
        $where = " AND tid = {$tid} AND status = 1";
        $ret = $GLOBALS['DB']->Set_Delete(ECHO_AQL_SWITCH, 1, $this->filepath, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    public function BcModelUpdateStatus($tid,$status){
        $setarray = array();
        $setarray[] =  "status = {$status}";
        $where = " AND tid = {$tid}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    public function BcModelListById($tid){
        $dataval = array();
        $where = " AND tid = {$tid} AND status = 1";
        $orderby = " id ASC";
        $addresslist = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
        if (empty($addresslist)) {
            return $dataval;
        }
        foreach ($addresslist as $key => $val) {
            if(!in_array($val['name'],$dataval)){
                array_push($dataval,$val['name']);
            }
        }
        return $dataval;
    }
}