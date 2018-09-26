<?php
/**
 * Created by PhpStorm.
 * 物流公司
 * User: huiyong.yu
 * Date: 2018/5/24
 * Time: 10:34
 */
class CompanyModel{

    private $filepath = 'delivery_company';
    private $fields = 'id, name, code, status, create_time, update_time';

    public function ModelList(){
        $dataval = array();
        $where = "AND status = 1";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where);
        if (empty($list)) {
            return $dataval;
        }
        foreach ($list as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['name'] = $val['name'];
            $dataval[$key]['code'] = $val['code'];
        }
        return $dataval;
    }

    public function BcModelInfoByCode($code){
        $dataval = array();
        $where = " AND code = '{$code}' AND status = 1";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if(empty($row)){
            return $dataval;
        }
        return $row;
    }

    public function ModelGetListByCode($code = ''){
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
            if(!empty($code) && $code == $val['code']){
                $dataval[$key]['selected'] = 1;
            }
        }
        return $dataval;
    }
}