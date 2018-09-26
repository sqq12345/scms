<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 14:09
 */
class Address{

    public function getProvinces(){
        $dataval = array();
        $model = get_load_model('province');
        $dataval = $model->ModeGetList();
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getCitiesByProvince($data){
        $dataval = array();
        $provinceCode = Inject_Check(isset($data['provinceCode']) ? $data['provinceCode'] : '');
        if (empty($provinceCode)) {
            Set_Return_Value(WILL_FIELD_NULL, $dataval);
            return false;
        }
        $model = get_load_model('city');
        $dataval = $model->ModeGetList($provinceCode);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getAreasByCity($data){
        $dataval = array();
        $cityCode = Inject_Check(isset($data['cityCode']) ? $data['cityCode'] : '');
        if (empty($cityCode)) {
            Set_Return_Value(WILL_FIELD_NULL, $dataval);
            return false;
        }
        $model = get_load_model('area');
        $dataval = $model->ModeGetList($cityCode);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function add($data) {
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $province = inject_check(isset($data['province']) ? $data['province'] : '');
        $city = inject_check(isset($data['city']) ? $data['city'] : '');
        $area = inject_check(isset($data['area']) ? $data['area'] : '');
        $address = inject_check(isset($data['address']) ? $data['address'] : '');
        $receiverName = inject_check(isset($data['receiverName']) ? $data['receiverName'] : '');
        $receiverPhone = inject_check(isset($data['receiverPhone']) ? $data['receiverPhone'] : '');
        $code = inject_check(isset($data['code']) ? $data['code'] : '');
        $defaultAddress = isset($data['defaultAddress']) ? intval($data['defaultAddress']) : 0;
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($province) || empty($city) || empty($address) || empty($receiverName) || empty($receiverPhone)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if( !preg_match("/1[34578]{1}\d{9}$/",$receiverPhone) ) {
            set_return_value(PHONE_ERROR, '');
            return false;
        }
        $addressmodel = get_load_model('address');
        $ret = $addressmodel->ModelAdd($userid,$province,$city,$area,$address,$receiverName,$receiverPhone,$defaultAddress,$code);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, '');
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function delete($data) {
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0; //地址的id
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $addressmodel = get_load_model('address');
        $ret = $addressmodel->ModelDelete($userid,$id);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getAddressById($data) {
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $addressmodel = get_load_model('address');
        $dataval = $addressmodel->ModelList($userid);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getInfo($data) {
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $addressmodel = get_load_model('address');
        $dataval = $addressmodel->ModelInfo($userid,$id);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function getDefaultAddress($data) {
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $addressmodel = get_load_model('address');
        $dataval = $addressmodel->ModelDefaultInfo($userid);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }

    public function update($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;
        $province = inject_check(isset($data['province']) ? $data['province'] : '');
        $city = inject_check(isset($data['city']) ? $data['city'] : '');
        $area = inject_check(isset($data['area']) ? $data['area'] : '');
        $address = inject_check(isset($data['address']) ? $data['address'] : '');
        $receiverName = inject_check(isset($data['receiverName']) ? $data['receiverName'] : '');
        $receiverPhone = inject_check(isset($data['receiverPhone']) ? $data['receiverPhone'] : '');
        $code = inject_check(isset($data['code']) ? $data['code'] : '');
        $defaultAddress = isset($data['defaultAddress']) ? intval($data['defaultAddress']) : 0;
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0 || empty($province) || empty($city) || empty($address) || empty($receiverName) || empty($receiverPhone)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if (!empty($receiver_phone)) {
            if( !preg_match("/1[34578]{1}\d{9}$/",$receiverPhone) ) {
                set_return_value(PHONE_ERROR, '');
                return false;
            }
        }
        $addressmodel = get_load_model('address');
        $ret = $addressmodel->ModelUpdate($id,$userid,$province,$city,$area,$address,$receiverName,$receiverPhone,$defaultAddress,$code);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }
}