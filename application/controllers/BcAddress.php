<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/10
 * Time: 16:30
 */
class BcAddress{

    public function add($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;  //目标用户id
        $province = inject_check(isset($data['province']) ? $data['province'] : '');
        $city = inject_check(isset($data['city']) ? $data['city'] : '');
        $area = inject_check(isset($data['area']) ? $data['area'] : '');
        $address = inject_check(isset($data['address']) ? $data['address'] : '');
        $receiverName = inject_check(isset($data['receiverName']) ? $data['receiverName'] : '');
        $receiverPhone = inject_check(isset($data['receiverPhone']) ? $data['receiverPhone'] : '');
        $code = inject_check(isset($data['code']) ? $data['code'] : '');
        $defaultAddress = isset($data['defaultAddress']) ? intval($data['defaultAddress']) : 0;
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0 || empty($province) || empty($city) || empty($area) || empty($address) || empty($receiverName) || empty($receiverPhone)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if( !preg_match("/1[34578]{1}\d{9}$/",$receiverPhone) ) {
            set_return_value(PHONE_ERROR, '');
            return false;
        }
        $addressmodel = get_load_model('address');
        $ret = $addressmodel->ModelAdd($id,$province,$city,$area,$address,$receiverName,$receiverPhone,$defaultAddress,$code);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function update($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $id = isset($data['id']) ? intval($data['id']) : 0;   //地址ID
        $province = inject_check(isset($data['province']) ? $data['province'] : '');
        $city = inject_check(isset($data['city']) ? $data['city'] : '');
        $area = inject_check(isset($data['area']) ? $data['area'] : '');
        $address = inject_check(isset($data['address']) ? $data['address'] : '');
        $receiverName = inject_check(isset($data['receiverName']) ? $data['receiverName'] : '');
        $receiverPhone = inject_check(isset($data['receiverPhone']) ? $data['receiverPhone'] : '');
        $code = inject_check(isset($data['code']) ? $data['code'] : '');
        if ($userid == 0 || $type < 3) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if ($id == 0 || empty($province) || empty($city) || empty($area) || empty($address) || empty($receiverName) || empty($receiverPhone)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        if (!empty($receiverPhone)) {
            if( !preg_match("/1[34578]{1}\d{9}$/",$receiverPhone) ) {
                set_return_value(PHONE_ERROR, '');
                return false;
            }
        }
        $addressmodel = get_load_model('address');
        $ret = $addressmodel->BcModelUpdate($id,$province,$city,$area,$address,$receiverName,$receiverPhone,$code);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }
}