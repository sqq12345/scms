<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 17:03
 */
class BrandModel{
    private $filepath = 'brand';
    private $filepath1 = 'category';
    private $fields = 'id, name,img, type, priority, status';

    public function ModelList($type, $landtype){
        if($type == 3){
            $where = " AND status = 1 AND type = {$landtype}";
            $orderby = " priority asc";
            $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby,$limit);
            if($list){
                $data = $list;
            }
        }elseif ($type == 1 || $type == 2) {
            $sql = "SELECT b.* from category a LEFT JOIN brand b on a.`name` = b.`name` where a.`status` = 1 and b.type = {$landtype} order by b.priority asc";
            $list = $GLOBALS['DB']->myquery($sql);
            if($list){
                $data = $list;
            }
        }
        return $data;
    }
    
    public function BcModelAdd($name,$img,$type,$priority){
        $ret = -1;
        $where = "AND name = '{$name}' AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        if($row > 0){
            return $ret;
        }else{
            $set = array();
            $set[] = "name = '{$name}'";
            $set[] = "img = '{$img}'";
            $set[] = "type = {$type}";
            $set[] = "priority = {$priority}";
            $set[] = "status = 1";
            $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH,2,$this->filepath,$set);
            return $ret;
        }
    }

    public function BcModelInfo($id){
        $dataval = array();
        $where = " AND id = {$id} AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['name'] = $row['name'];
        $dataval['img'] = $row['img'];
        $dataval['type'] = intval($row['type']);
        $dataval['priority'] = intval($row['priority']);
        $dataval['status'] = intval($row['status']);
        return $dataval;
    }

    public function BcModelUpdate($id,$name,$img,$type,$priority) {
        $ret = 0;
        $where = "AND name = '{$name}' AND id != {$id}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        if($row > 0){
            return $ret;
        }
        $set = array();
        $set[] = "name = '{$name}'";
        $set[] = "img = '{$img}'";
        $set[] = "type = {$type}";
        $set[] = "priority = {$priority}";
        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelList($name,$offset,$max){
        $dataval = array();
        $where = " AND status = 1";
        if(!empty($name)){
            $where.= " AND name LIKE '%{$name}%'";
        }
        $limit = " LIMIT {$offset}, {$max}";
        $orderby = "priority Asc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
            $dataval[$key]['img'] = $val['img'];
            $dataval[$key]['type'] = intval($val['type']);
            $dataval[$key]['priority'] = intval($val['priority']);
            $dataval[$key]['status'] = intval($val['status']);
        }
        $data = array();
        $total = $GLOBALS['DB']->getSelectCount(ECHO_AQL_SWITCH,"*",$this->filepath,$where);
        $data['total'] = intval($total);
        $data['list'] = $dataval;
        return $data;
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

    public function BcModelGetList(){
        $dataval = array();
        $where = " AND status = 1";
        $orderby = "priority asc";
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

    public function BcModelUpdateStatus($id,$status){
        $setarray[] =  "status = {$status}";
        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    public function BcModelGetListByName($name){
        $dataval = array();
        $where = " AND status = 1";
        if(!empty($name)){
            $where.= " AND name LIKE '%{$name}%'";
        }
        $orderby = "priority asc";
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

    public function ModelAllList(){
        $dataval = array();
        $where = " AND status = 1 ";
        $orderby = " priority asc";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
        }
        return $dataval;
    }
}