<?php
/**
 * Created by PhpStorm.
 * 运费方式表
 * User: huiyong.yu
 * Date: 2018/6/20
 * Time: 11:32
 */
class CarryModeModel{
    private $filepath = 'carry_mode';
    private $fields = 'id,fare_id,first_piece,first_weight,first_amount,second_piece,second_weight,second_amount,carry_way,create_time,update_time,status';

    public function ModelInfoByFareId($fareId){
        $dataval = array();
        $where = " AND fare_id = {$fareId} AND status = 1 ";
        $row = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($row)) {
            return $dataval;
        }
        $dataval['id'] = intval($row['id']);
        $dataval['fareId'] = intval($row['fare_id']);
        $dataval['firstPiece'] = intval($row['first_piece']);
        $dataval['firstWeight'] = doubleval($row['first_weight']);
        $dataval['firstAmount'] = doubleval($row['first_amount']);
        $dataval['secondPiece'] = intval($row['second_piece']);
        $dataval['secondWeight'] = doubleval($row['second_weight']);
        $dataval['secondAmount'] = doubleval($row['second_amount']);
        return $dataval;
    }
}