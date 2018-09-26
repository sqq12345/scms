<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/6/5
 * Time: 14:05
 */
class Validate{

    public function add($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0; //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $name = inject_check(isset($data['name']) ? $data['name'] : '');
        $idCard = Inject_Check(isset($data['idCard']) ? $data['idCard'] : '');   //身份证号码
        $positiveImg = Inject_Check(isset($data['positiveImg']) ? $data['positiveImg'] : '');   //身份证正面照
        $oppositeImg = Inject_Check(isset($data['oppositeImg']) ? $data['oppositeImg'] : '');   //身份证反面照
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($name) || empty($idCard)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
//        if(!preg_match("/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/",$idCard)){
//            set_return_value(ID_ERROR, '');
//            return false;
//        }
        //查询该用户的当天请求次数
        $limitValidateModel = get_load_model('limitValidate');
        $ip = get_ip();
        $limitValidateInfo = $limitValidateModel->ModelInfoByUserIdAndIpAndName($userid,$name,$ip);
        if(!empty($limitValidateInfo)){
            if($limitValidateInfo['times'] > 2){
                //超过当天请求次数
                //todo
//                var_dump("超过当天请求次数");
                set_return_value(ID_VALIDATE_TIMES_ERROR, '');
                return false;
            }else{
                //更新请求次数  +1
//                var_dump("更新请求次数  +1");
                $limitValidateModel->ModelAddTimes($limitValidateInfo['id']);
            }
        }else{
            //新增记录
//            var_dump("新增记录");
            $limitValidateModel->ModelInsert($userid,$name,$ip);
        }
//        var_dump("调用身份证认证API");
 //       exit;
        //调用身份证认证API
        get_load_libraries('idValidate');
        $idValidate  = new idValidate();
        $return = $idValidate->doValidate($name,$idCard);
        $return = json_decode($return,true);
        if($return['respCode'] == '0000'){
            $realNameModel = get_load_model('realName');
            $info = $realNameModel->ModelInfoByUserIdAndNameAndIdCard($userid,$name,$idCard);
            if(!empty($info)){
                set_return_value(RESULT_SUCCESS, $dataval);
                return false;
            }
            $ret = $realNameModel->ModelAdd($userid,$name,$idCard,$positiveImg,$oppositeImg);
            if($ret){
                set_return_value(RESULT_SUCCESS, $dataval);
            }else{
                set_return_value(ID_VALIDATE_ERROR, $dataval);
            }
        }else{
            set_return_value(ID_VALIDATE_ERROR, $dataval);
        }
    }
}