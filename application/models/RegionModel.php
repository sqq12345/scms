<?php
/**
 * Created by PhpStorm.
 * 发货地
 * User: huiyong.yu
 * Date: 2018/7/3
 * Time: 15:46
 */
class RegionModel{

    private $filepath = 'region';
    private $fields = 'id, name, create_time, update_time, status';

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
        }
        return $dataval;
    }

}