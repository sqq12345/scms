<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 16:55
 */
class CategoryModel{
    private $filepath = 'category';
    private $fields = 'id,parent_id,parent_name,name,summary,img,is_show,priority,status';

    public function ModelList(){
        $dataval = array();
        $where = " AND status = 1 AND parent_id = 0 AND is_show = 1";
        $orderby = " priority ASC";
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

    public function BcModelInfo($id){
        $dataval = array();
        $where = " AND id = {$id}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['parentId'] = intval($row['parent_id']);
        $dataval['parentName'] = $row['parent_name'];
        $dataval['name'] = $row['name'];
        $dataval['summary'] = $row['summary'];
        $dataval['img'] = $row['img'];
        $dataval['isShow'] = intval($row['is_show']);
        $dataval['priority'] = intval($row['priority']);
        $dataval['status'] = intval($row['status']);
        return $dataval;
    }

    public function BcModelAdd($name,$summary,$parentId,$parentName,$img,$isShow,$priority,$status){
        $ret = -1;
        $where = "AND name = '{$name}'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        if($row > 0){
            return $ret;
        }else{
            $set = array();
            $set[] = "parent_id = {$parentId}";
            $set[] = "parent_name = '{$parentName}'";
            $set[] = "name = '{$name}'";
            $set[] = "summary = '{$summary}'";
            $set[] = "img = '{$img}'";
            $set[] = "is_show = {$isShow}";
            $set[] = "priority = {$priority}";
            $set[] = "status = {$status}";
            $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH,2,$this->filepath,$set);
            return $ret;
        }
    }

    public function BcModelUpdate($id,$name,$summary,$parentId,$parentName,$img,$isShow,$priority,$status) {
        $ret = -1;
        $where = "AND name = '{$name}' AND id != {$id}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        if($row > 0){
            return $ret;
        }
        $set = array();
        $set[] = "parent_id = {$parentId}";
        $set[] = "parent_name = '{$parentName}'";
        $set[] = "name = '{$name}'";
        $set[] = "summary = '{$summary}'";
        $set[] = "img = '{$img}'";
        $set[] = "is_show = {$isShow}";
        $set[] = "priority = {$priority}";
        $set[] = "status = {$status}";
        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        return $ret;
    }

    public function BcModelList($name,$status,$offset,$max){
        $dataval = array();
        $where = '';
        if($status >= 0){
            $where.= " AND status = {$status}";
        }
        if(!empty($name)){
            $where.= " AND name LIKE '%{$name}%'";
        }
        $limit = " LIMIT {$offset}, {$max}";
        $orderby = "priority ASC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby, $limit);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
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

    public function BcModelDropList(){
        $dataval = array();
        $where = " AND status = 1 AND parent_id = 0";
        $orderby = "priority ASC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
            $dataval[$key]['list'] = $this->BcModelListByPid($val['id']);
        }
        return $dataval;
    }

    public function BcModelListByPid($pid){
        $dataval = array();
        $where = " AND status = 1 AND parent_id = {$pid}";
        $orderby = "priority ASC";
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

    public function BcModelParentList(){
        $dataval = array();
        $where = " AND status = 1 AND parent_id = 0";
        $orderby = "priority ASC";
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

    public function BcModelGetAllList(){
        $dataval = array();
        $where = " AND status = 1";
        $orderby = "priority ASC";
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
}