<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 14:04
 */
class AreaModel{
    private $filepath = 'area';
    private $fields = 'id, code, name, citycode';

    public function ModeGetList($citycode){
        $dataval = array();
        $where = " AND citycode = '{$citycode}'";
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

    public function ModelGetListByPcode($citycode,$name){
        $dataval = array();
        $where = " AND citycode = '{$citycode}'";
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
}