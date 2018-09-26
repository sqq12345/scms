<?php
/**
 * Created by PhpStorm.
 * 会费信息
 * User: huiyong.yu
 * Date: 2018/5/21
 * Time: 15:06
 */
class DuesConfigModel{

    private $filepath = 'dues_config';
    private $fields = 'id, name, days, price, create_time, update_time, status ';

    public function ModelInfoById($id){
        $dataval = array();
        $where = " AND id = {$id}";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }

    public function ModelList(){
        $dataval = array();
        $where = "AND status = 1 ";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
            $dataval[$key]['days'] = intval($val['days']);
            $dataval[$key]['price'] = doubleval($val['price']);
        }
        return $dataval;
    }
}