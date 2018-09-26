<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 14:36
 */
class AddressModel{
    private $filepath = 'address';
    private $fields = 'id, user_id, province, city, area, address, receiver_name, receiver_phone,code, default_address, create_time, update_time, status';


    public function ModelAdd($user_id,$province,$city,$area,$address,$receiver_name,$receiver_phone,$default_address,$code) {
        $setarray = array();
        $setarray[] = "user_id = {$user_id}";
        $setarray[] = "province = '{$province}'";
        $setarray[] = "city = '{$city}'";
        $setarray[] = "area = '{$area}'";
        $setarray[] = "address = '{$address}'";
        $setarray[] = "receiver_name = '{$receiver_name}'";
        $setarray[] = "receiver_phone = '{$receiver_phone}'";
        $setarray[] = "code = '{$code}'";
        $setarray[] = "default_address = {$default_address}";
        $setarray[] = "create_time = now()";
        $setarray[] = "status = 1";

        //若添加新的默认地址，以前的默认地址取消默认
        if ($default_address == 1) {
            $where = " AND user_id = {$user_id} AND default_address = 1 ";
            $searchret = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
            if($searchret > 0){
                $set = array();
                $set[] = "default_address = 0";
                $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
            }
        }
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray);
        return $ret;
    }

    public function ModelDelete($userid,$id) {
        $setarray = array();
        $setarray[] =  "status = 0";
        $where = " AND id = {$id} AND user_id = {$userid}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        if($ret !== false){
            return true;
        }
        return false;
    }

    public function ModelList($user_id) {
        $dataval = array();
        $where = " AND user_id = {$user_id} AND status = 1";
        $orderby = " default_address desc,id desc";
        $addresslist = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
        if (empty($addresslist)) {
            return $dataval;
        }
        foreach ($addresslist as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['province'] = $val['province'];
            $dataval[$key]['city'] = $val['city'];
            $dataval[$key]['area'] = $val['area'];
            $dataval[$key]['address'] = $val['address'];
            $dataval[$key]['receiverName'] = $val['receiver_name'];
            $dataval[$key]['receiverPhone'] = $val['receiver_phone'];
            $dataval[$key]['code'] = $val['code'];
            $dataval[$key]['defaultAddress'] = intval($val['default_address']);
        }
        return $dataval;
    }

    public function ModelInfo($userid,$id) {
        $dataval = array();
        $where = " AND id = {$id} AND user_id = {$userid}";
        $addressrow = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($addressrow)) {
            return $dataval;
        }
        $dataval['id'] = intval($addressrow['id']);
        $dataval['userId'] = intval($addressrow['user_id']);
        $dataval['province'] = $addressrow['province'];
        $dataval['city'] = $addressrow['city'];
        $dataval['area'] = $addressrow['area'];
        $dataval['address'] = $addressrow['address'];
        $dataval['receiverName'] = $addressrow['receiver_name'];
        $dataval['receiverPhone'] = $addressrow['receiver_phone'];
        $dataval['code'] = $addressrow['code'];
        $dataval['defaultAddress'] = intval($addressrow['default_address']);
        return $dataval;
    }

    public function ModelDefaultAddress($user_id) {
        $dataval = array();
        $where = " AND user_id = {$user_id} AND  default_address = 1";
        $addressrow = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($addressrow)) {
            return $dataval;
        }
        $dataval['id'] = intval($addressrow['id']);
        $dataval['province'] = $addressrow['province'];
        $dataval['city'] = $addressrow['city'];
        $dataval['area'] = $addressrow['area'];
        $dataval['address'] = $addressrow['address'];
        $dataval['receiverName'] = $addressrow['receiver_name'];
        $dataval['receiverPhone'] = $addressrow['receiver_phone'];
        $dataval['code'] = $addressrow['code'];
        return $dataval;
    }

    public function ModelUpdate($id,$user_id,$province,$city,$area,$address,$receiver_name,$receiver_phone,$default_address,$code) {
        $setarray = array();
        if (!empty($province)) $setarray[] = "province = '{$province}'";
        if (!empty($city)) $setarray[] = "city = '{$city}'";
        if (!empty($area)) $setarray[] = "area = '{$area}'";
        if (!empty($address)) $setarray[] = "address = '{$address}'";
        if (!empty($receiver_name)) $setarray[] = "receiver_name = '{$receiver_name}'";
        if (!empty($receiver_phone)) $setarray[] = "receiver_phone = '{$receiver_phone}'";
        if (!empty($code)) $setarray[] = "code = '{$code}'";

        //以前的默认地址取消默认
        if ($default_address == 1) {
            $where = " AND user_id = {$user_id} AND default_address = 1 ";
            $searchret = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
            if($searchret && $searchret['id'] != $id){
                $set = array();
                $set[] = "default_address = 0";
                $result = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
                $setarray[] = "default_address = '1'";
            }else{
                $setarray[] = "default_address = '{$default_address}'";
            }
        }else{
            $setarray[] = "default_address = '{$default_address}'";
        }
        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        return $ret;
    }

    public function BcModelList($user_id){
        $dataval = array();
        $where = " AND user_id = {$user_id} AND status = 1";
        $orderby = ' default_address desc';
        $addresslist = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 0, $this->filepath, $this->fields, $where,$orderby);
        if (empty($addresslist)) {
            return $dataval;
        }
        $provinceModel = get_load_model('province');
        $cityModel = get_load_model('city');
        $areaModel = get_load_model('area');
        foreach ($addresslist as $key => $val) {
            $dataval[$key]['id'] = intval($val['id']);
            $dataval[$key]['userId'] = intval($val['user_id']);
            $dataval[$key]['provinceList'] = $provinceModel->ModelGetListByName($val['province']);
            $province = $provinceModel->ModelGetInfoByName($val['province']);
            $dataval[$key]['cityList'] = $cityModel->ModelGetListByPcode($province['code'],$val['city']);
            $city = $cityModel->ModelGetInfoByName($val['city']);
            $dataval[$key]['areaList'] = $areaModel->ModelGetListByPcode($city['code'],$val['area']);
            $dataval[$key]['address'] = $val['address'];
            $dataval[$key]['receiverName'] = $val['receiver_name'];
            $dataval[$key]['receiverPhone'] = $val['receiver_phone'];
            $dataval[$key]['code'] = $val['code'];
            $dataval[$key]['defaultAddress'] = intval($val['default_address']);
        }
        return $dataval;
    }

    public function BcModelUpdate($id,$province,$city,$area,$address,$receiver_name,$receiver_phone,$code){
        $setarray = array();
        if (!empty($province)) $setarray[] = "province = '{$province}'";
        if (!empty($city)) $setarray[] = "city = '{$city}'";
        if (!empty($district)) $setarray[] = "area = '{$area}'";
        if (!empty($address)) $setarray[] = "address = '{$address}'";
        if (!empty($receiver_name)) $setarray[] = "receiver_name = '{$receiver_name}'";
        if (!empty($receiver_phone)) $setarray[] = "receiver_phone = '{$receiver_phone}'";
        if (!empty($code)) $setarray[] = "code = '{$code}'";

        $where = " AND id = {$id}";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $setarray, $where);
        return $ret;
    }

    public function ModelDefaultInfo($user_id) {
        $dataval = array();
        $where = " AND user_id = {$user_id} AND  default_address = 1";
        $addressrow = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        if (empty($addressrow)) {
            return $dataval;
        }
        $address = array();
        $address['id'] = intval($addressrow['id']);
        $address['province'] = $addressrow['province'];
        $address['city'] = $addressrow['city'];
        $address['area'] = $addressrow['area'];
        $address['address'] = $addressrow['address'];
        $address['receiverName'] = $addressrow['receiver_name'];
        $address['receiverPhone'] = $addressrow['receiver_phone'];
        $address['code'] = $addressrow['code'];
        $dataval['address'] = $address;
        return $dataval;
    }
}