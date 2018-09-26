<?php
/**
 * Created by PhpStorm.
 * 运费模板
 * User: huiyong.yu
 * Date: 2018/6/20
 * Time: 11:30
 */
class FareTemplateModel{
    private $filepath = 'fare_template';
    private $fields = 'id,name,dispatch_region,dispatch_time,is_incl_postage,valuation_model,create_time,update_time,status';


    public function ModelInfo($id){
        $dataval = array();
        $where = " AND id = {$id} AND status = 1 ";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['name'] = $row['name'];
        $dataval['dispatchRegion'] = $row['dispatch_region'];
        $dataval['dispatchTime'] = $row['dispatch_time'];
        $dataval['isInclPostage'] = intval($row['is_incl_postage']);
        $dataval['valuationModel'] = intval($row['valuation_model']);
        return $dataval;
    }

    public function BcModelList(){
        $dataval = array();
        $where = "AND status = 1 ";
        $list = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,'id asc');
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