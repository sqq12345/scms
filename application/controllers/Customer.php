<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 10:02
 */
class Customer{

    public function register($data){
        $dataval = array();
        $username = Inject_Check(isset($data['username']) ? $data['username'] : '');   //注册手机号
        $password = Inject_Check(isset($data['password']) ? $data['password'] : '');  //密码
        $captcha = Inject_Check(isset($data['captcha']) ? $data['captcha'] : '');   //验证码
        $idCard = Inject_Check(isset($data['idCard']) ? $data['idCard'] : '');   //身份证号码
        $positiveImg = Inject_Check(isset($data['positiveImg']) ? $data['positiveImg'] : '');   //身份证正面照
        $oppositeImg = Inject_Check(isset($data['oppositeImg']) ? $data['oppositeImg'] : '');   //身份证反面照
        if (empty($username) || empty($password) || empty($captcha)) {
            Set_Return_Value(WILL_FIELD_NULL, $dataval);
            return false;
        }
        if (!preg_match("/1[34578]{1}\d{9}$/", $username)) {
            set_return_value(PHONE_ERROR, '');
            return false;
        }
  //      if(!preg_match("/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/",$idCard)){
  //          set_return_value(ID_ERROR, '');
   //         return false;
  //      }
        $customerModel = get_load_model('customer');
        $customer = $customerModel->ModelLogin($username);
        if (!empty($customer)) {
            set_return_value(PHONE_REGISTER_ERROR, '');
            return false;
        }
        $captchamodel = get_load_model('captcha');
        $code = $captchamodel->ModelVerify($username);
        if (empty($code)) {
            Set_Return_Value(CODE_NULL_ERROR, $dataval);
            return false;
        }
        if ($captcha == $code['captcha']) {
            $captchamodel->Modelupdate($code['id']);
            $password = md5($password.PASSWORD_MD5);
            $configMode = get_load_model('config');
            $vipDays = VIP_DAYS;
            $configInfo = $configMode->BcModelInfo('vipDays');
            if(!empty($configInfo)){
                $vipDays = intval($configInfo['value']);
            }
//            $positiveImg = QINIU_HTTP.$positiveImg;
//            $oppositeImg = QINIU_HTTP.$oppositeImg;
            $ret = $customerModel->ModelAdd($username, $password,$idCard,$positiveImg,$oppositeImg,$vipDays);
            if ($ret) {
                $user = $customerModel->ModelLogin($username);
                $dataval = array(
                    "id" => intval($user["id"]),
                    "nickname" => $user["nickname"],
                    "img" => $user["img"],
                    "email" => $user["email"],
                    "days" => $vipDays,
                );
                //token机制
                $tokenmodel = get_load_model('token');
                $token = md5($user['id'].time());
                $tokenmodel->ModelAdd($user['id'],$token,1);
                $dataval['token'] = $token;

                //新增限购记录
                $limitAmountModel = get_load_model('limitAmount');
                $limitAmountModel->ModelInsert($user['id']);

                //添加注册成功消息通知记录
                $wxMessageModel = get_load_model('wxMessage');
                $miniprogram = array();
                //todo
                $url = "https://www.shichamaishou.com";
                $postData = array(
                    "first"=>array(
                        "value"=>"您好，您已成为时差买手网会员",
                        "color"=>"#173177"
                    ),
                );
                $wxMessageModel->ModelAdd($user['id'],WX_MESSAGE_MODEL_5,$url,serialize($miniprogram),serialize($postData));
                set_return_value(RESULT_SUCCESS, $dataval);
            } else {
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        } else {
            Set_Return_Value(CODE_NULL_ERROR, $dataval);
        }

    }

    public function login($data){
        $dataval = array();
        $username = inject_check(isset($data['username']) ? $data['username'] : '');
        $password = inject_check(isset($data['password']) ? $data['password'] : '');

        if (empty($username) || empty($password)) {
            set_return_value(WILL_FIELD_NULL, $dataval);
            return false;
        }
        $customermodel = get_load_model('customer');
        $user = $customermodel->ModelLogin($username);
        if (empty($user)) {
            set_return_value(USER_LOGIN_ERROR, $dataval);
            return false;
        }
        $password = md5($password.PASSWORD_MD5);
        if ($user['password'] == $password) {
            $dataval = array(
                "id" => intval($user["id"]),
                "nickname" => $user["nickname"],
                "img" => $user["img"],
                "email" => $user["email"],
            );
            date_default_timezone_set('PRC');
            $time = date('Y-m-d H:i:s');
            if(strtotime($time) > strtotime($user['vip_time'])){
                $dataval['days'] = 0;
            }else{
                $dataval['days'] = round((strtotime($user['vip_time']) - strtotime($time))/3600/24);
            }
            //token机制
            $tokenmodel = get_load_model('token');
            $token = md5($user['id'].time());
            $tokenmodel->ModelAdd($user['id'],$token,1);
            $dataval['token'] = $token;
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(PWD_LOGIN_ERROR, $dataval);
        }
    }

    public function update($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $nickname = inject_check(isset($data['nickname']) ? $data['nickname'] : '');
        $img = inject_check(isset($data['img']) ? $data['img'] : '');
        $email = inject_check(isset($data['email']) ? $data['email'] : '');
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $set = array();
        $model = get_load_model('customer');
        if(!empty($nickname)) $set[] = " nickname = '{$nickname}'";
        if(!empty($img)){
//            $img = QINIU_HTTP.$img;
            $set[] = " img = '{$img}'";
        }
        if(!empty($email)) $set[] = " email = '{$email}'";
        $ret = $model->ModelUpdate($set, $userid);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getInfo($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        $customermodel = get_load_model('customer');
        $dataval = $customermodel->ModelInfo($userid);
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function updatePass($data){
        $dataval = array();
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $password = inject_check(isset($data['password']) ? $data['password'] : '');
        $oldpwd = inject_check(isset($data['oldpwd']) ? $data['oldpwd'] : '');
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($password) || empty($oldpwd)) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $customermodel = get_load_model('customer');
        $customer = $customermodel->ModelInfo($userid);
        if(empty($customer)){
            set_return_value(USER_NULL_ERROR, $dataval);
            return false;
        }
        $password = md5($password.PASSWORD_MD5);
        $oldpwd = md5($oldpwd.PASSWORD_MD5);
        $ret = $customermodel->ModelUpdatePwd($userid,$password,$oldpwd);
        if ($ret) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function modifyPass($data){
        $dataval = array();
        $username = Inject_Check(isset($data['username']) ? $data['username'] : '');
        $password = Inject_Check(isset($data['password']) ? $data['password'] : '');
        $captcha = Inject_Check(isset($data['captcha']) ? $data['captcha'] : '');
        if (empty($username) || empty($password) || empty($captcha)) {
            Set_Return_Value(WILL_FIELD_NULL, $dataval);
            return false;
        }
        $customermodel = get_load_model('customer');
        $user = $customermodel->ModelLogin($username);
        if (empty($user)) {
            set_return_value(USER_NULL_ERROR, $dataval);
            return false;
        }
        $captchamodel = get_load_model('captcha');
        $code = $captchamodel->ModelVerify($username);
        if (empty($code)) {
            Set_Return_Value(CODE_NULL_ERROR, $dataval);
            return false;
        }
        if ($captcha == $code['captcha']) {
            $updateCode = $captchamodel->Modelupdate($code['id']);
            if ($updateCode) {
                $password = md5($password.PASSWORD_MD5);
                $ret = $customermodel->ModelUpdatePassWord($user['id'],$password);
                if ($ret !== false) {
                    Set_Return_Value(RESULT_SUCCESS, $dataval);
                } else {
                    Set_Return_Value(DEFEATED_ERROR, $dataval);
                }
            } else {
                Set_Return_Value(DEFEATED_ERROR, $dataval);
            }
        } else {
            Set_Return_Value(CODE_NULL_ERROR, $dataval);
        }
    }

    public function verify($data){
        $dataval = array();
        $username = Inject_Check(isset($data['username']) ? $data['username'] : '');   //注册手机号
        $captcha = Inject_Check(isset($data['captcha']) ? $data['captcha'] : '');   //验证码
        if (empty($username) || empty($captcha)) {
            Set_Return_Value(WILL_FIELD_NULL, '');
            return false;
        }
        if (!preg_match("/1[34578]{1}\d{9}$/", $username)) {
            set_return_value(PHONE_ERROR, '');
            return false;
        }$customerModel = get_load_model('customer');
        $customer = $customerModel->ModelLogin($username);
        if (!empty($customer)) {
            set_return_value(PHONE_REGISTER_ERROR, '');
            return false;
        }
        $captchamodel = get_load_model('captcha');
        $code = $captchamodel->ModelVerify($username);
        if (empty($code)) {
            Set_Return_Value(CODE_NULL_ERROR, '');
            return false;
        }
        if ($captcha == $code['captcha']) {
            set_return_value(RESULT_SUCCESS, '');
            return false;
        }else{
            set_return_value(CODE_NULL_ERROR, '');
            return false;
        }
    }

    public function recharge($data){
        $dataval = array();
        $payTypeAllows = array("wxpay", "alipay","h5wxpay","qrcodepay","xcxpay");
        $userid = isset($data['userid']) ? intval($data['userid']) : 0;
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $openId = inject_check(isset($data['openId']) ? $data['openId'] : '');  //openid
        $payType = inject_check(isset($data['payType']) ? strtolower($data['payType']) : '');
        $id = isset($data['id']) ? intval($data['id']) : 0;  //充值会员对象ID
        if ($userid == 0 || $type > 1) {
            set_return_value(AUTH_ERROR, '');
            return false;
        }
        if (empty($payType) || !in_array($payType, $payTypeAllows)) {
            set_return_value(ORDER_PAY_TYPE_ERROR, '');
            return false;
        }
        if ($id == 0) {
            set_return_value(WILL_FIELD_NULL, '');
            return false;
        }
        $configModel = get_load_model('duesConfig');
        $info = $configModel->ModelInfoById($id);
        if(empty($info)){
            set_return_value(MEMBER_RECHARGE_ERROR,'');
            return false;
        }
        $recordModel = get_load_model('rechargeRecord');
        $recordSn = $recordModel->ModelAdd($userid,$info['name'],$info['days'],$info['price']);
        if($recordSn){
            $payInfoModel = get_load_model('payInfo');
            $return = $payInfoModel->ModelAdd($userid,'recharge',$recordSn,$payType,intval($info['price']*100));
            if($return !== false && $payType == 'alipay'){
                //var_dump("alipay");
                //调用支付
                get_load_libraries('alipay');
                $pay = new alipay();
                $payData = array(
                    "title" 	 => "时差买手",
                    "body"       => $info['name'],
                    "orderNo"    => $recordSn,
                    "product_code"  => "QUICK_WAP_WAY",
                    "fee"        => $info['price'],
                    "timeout_express"=>"1m"
                );
                $result = $pay->doPay($payData);
                $dataval['info'] = $result;
                $dataval['orderSn'] = $recordSn;
                if(!empty($dataval['info'])){
                    set_return_value(RESULT_SUCCESS, $dataval);
                }else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }elseif ($return !== false && $payType == 'wxpay' && !empty($openId)){
                get_load_libraries('wxpay');
                $pay = new wxpay();
                $payData = array(
                    "body"       => $info['name'],
                    "orderNo"    => $recordSn,
                    "fee"        => $info['price']*100,
                    "openId"  =>$openId,
                );
                $result = $pay->doJsApiPay($payData);
                $dataval['info'] = $result;
                $dataval['orderSn'] = $recordSn;
                if(!empty($dataval['info'])){
                    set_return_value(RESULT_SUCCESS, $dataval);
                }else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }elseif ($return !== false && $payType == 'h5wxpay'){
                //微信支付
                get_load_libraries('wxpay');
                $pay = new wxpay();
                $payData = array(
                    "body"       => $info['name'],
                    "orderNo"    => $recordSn,
                    "fee"        => $info['price']*100,
                );
                $result = $pay->doH5Pay($payData);
                $dataval['info'] = $result;
                $dataval['orderSn'] = $recordSn;
                if(!empty($dataval['info'])){
                    set_return_value(RESULT_SUCCESS, $dataval);
                }else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            } elseif ($return !== false && $payType == 'qrcodepay'){
                get_load_libraries('wxpay');
                $pay = new wxpay();
                $payData = array(
                    "body"       => $info['name'],
                    "orderNo"    => $recordSn,
                    "fee"        => $info['price']*100,
                );
                $result = $pay->doQrCodePay($payData);
                $dataval['info'] = $result;
                $dataval['orderSn'] = $recordSn;
                if(!empty($dataval['info'])){
                    set_return_value(RESULT_SUCCESS, $dataval);
                }else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }elseif ($return !== false && $payType == 'xcxpay' && !empty($openId)){
                get_load_libraries('wxpay');
                $pay = new wxpay();
                $payData = array(
                    "body"       => $info['name'],
                    "orderNo"    => $recordSn,
                    "fee"        => $info['price']*100,
                    "openId"  =>$openId,
                );
                $result = $pay->doXcxPay($payData);
                $dataval['info'] = $result;
                $dataval['orderSn'] = $recordSn;
                if(!empty($dataval['info'])){
                    set_return_value(RESULT_SUCCESS, $dataval);
                }else{
                    set_return_value(DEFEATED_ERROR, $dataval);
                }
            }
            else{
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }else{
            set_return_value(DEFEATED_ERROR, $dataval);
        }
    }

    public function getRecharge(){
        $dataval = array();
        $model = get_load_model('duesConfig');
        $dataval = $model->ModelList();
        if (!empty($dataval)) {
            set_return_value(RESULT_SUCCESS, $dataval);
        } else {
            set_return_value(RESULT_ERROR_NULL, $dataval);
        }
    }
}