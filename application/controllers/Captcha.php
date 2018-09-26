<?php
/**
 * Created by PhpStorm.
 * 短信发送
 * User: huiyong.yu
 * Date: 2018/4/18
 * Time: 11:52
 */
class Captcha{

    public function getCaptcha($data)
    {
        $dataval = array();
        $phone = inject_check(isset($data['phone']) ? $data['phone'] : '');
        if (empty($phone) || !preg_match("/1[34578]{1}\d{9}$/", $phone)) {
            set_return_value(PHONE_ERROR, '');
            return false;
        }
        $captchamodel = get_load_model('captcha');
        $count = $captchamodel->ModelFindByMobile($phone);
        if ($count >= 8) {
            set_return_value(SMS_CODE_COUNT_ERROR, '');
            return false;
        } else {
            $captcha = rand(1000, 9999);
            $ip = get_ip();
            $ret = $captchamodel->ModelAdd($phone, $captcha, $ip);
            if ($ret > 0) {
                Set_Return_Value(RESULT_SUCCESS, $dataval);
                $sms  = new sms();
                $sms->sendOne($phone,$captcha);
            } else {
                set_return_value(DEFEATED_ERROR, $dataval);
            }
        }
    }
}