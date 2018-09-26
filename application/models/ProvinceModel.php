<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 14:03
 */
class ProvinceModel{
    private $filepath = 'province';
    private $fields = 'id, code, name';

    public function ModeGetList(){
        $dataval = array();
        $where = " ";
        $orderby = " id ASC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if(empty($list)){
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['code'] = $val['code'];
            $dataval[$key]['name'] = $val['name'];
        }
        return $dataval;
    }

    public function ModelGetListByName($name){
        $dataval = array();
        $where = "";
        $orderby = " id ASC";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where, $orderby);
        if(empty($list)){
            return $dataval;
        }
        foreach ($list as $key=>$val){
            $dataval[$key]['code'] = $val['code'];
            $dataval[$key]['name'] = $val['name'];
            $dataval[$key]['selected'] = 0;
            if($name == $val['name']){
                $dataval[$key]['selected'] = 1;
            }

        }
        return $dataval;
    }

    public function ModelGetInfoByName($name){
        $dataval = array();
        $where = " AND name = '{$name}'";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }
}