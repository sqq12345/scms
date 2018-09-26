<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/9
 * Time: 10:25
 */
class CaptchaModel{
    private $filepath = 'captcha';
    private $fields = 'id,phone,captcha,status,ip,update_time,create_time';

    public function ModelVerify($phone){
        $where = "AND status = 1 AND phone = '{$phone}'";
        $captcha = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 1, $this->filepath, $this->fields, $where);
        return $captcha;
    }

    public function ModelUpdate($id){
        $set = ' status = 2 ';
        $where = "AND id = {$id} ";
        $ret = $GLOBALS['DB']->setUpdate(ECHO_AQL_SWITCH, 1, $this->filepath, $set, $where);
        if ($ret) {
            return true;
        } else {
            return false;
        }
    }

    public function ModelAdd($phone, $captcha, $ip){
        $set = array();
        $set[] = "phone = '{$phone}'";
        $set[] = "status = 1";
        $set[] = "captcha = {$captcha}";
        $set[] = "ip = '{$ip}'";
        $set[] =  "create_time = now()";
        $set[] =  "update_time = now()";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $set);
        return $ret;
    }

    public function ModelFindByMobile($phone){
        $stattime = date('Y-m-d').' 00:00:00';
        $endtime = date('Y-m-d').' 23:59:59';
        $where = "AND phone = '{$phone}' AND create_time BETWEEN '{$stattime}' and '{$endtime}'";
        $count = $GLOBALS['DB']->getSelect(ECHO_AQL_SWITCH, 2, $this->filepath, $this->fields, $where);
        return $count;
    }
}