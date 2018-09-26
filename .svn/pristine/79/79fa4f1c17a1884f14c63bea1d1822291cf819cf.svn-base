<?php
/**
 * Created by PhpStorm.
 * 微信通知
 * User: huiyong.yu
 * Date: 2018/7/10
 * Time: 15:20
 */
class WxMessageModel{
    private $filepath = 'wx_message';
    private $fields = 'id, user_id, template_id, url, mini_program, post_data, times, create_time, update_time, status';

    public function ModelAdd($userId,$templateId,$url,$miniProgram,$postData){
        $setarray = array();
        $setarray[] = "user_id = {$userId}";
        $setarray[] = "template_id = '{$templateId}'";
        $setarray[] = "url = '{$url}'";
        $setarray[] = "mini_program = '{$miniProgram}'";
        $setarray[] = "post_data = '{$postData}'";
        $setarray[] = "times = 0";
        $setarray[] = "create_time = now()";
        $setarray[] = "status = 1";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 2, $this->filepath, $setarray);
        return $ret;
    }

    //根据两个人查找分享关系
    public function getShareByUser($shareuser, $getuser){
        $sql = "SELECT * from wx_share where shareuser = {$shareuser} and getuser = {$getuser} union SELECT * from wx_share where shareuser = {$getuser} and getuser = {$shareuser}";
        $ret = $GLOBALS['DB']->myquery($sql);
        return $ret;
    }

    //增加微信分享
    public function addShare($shareuser, $getuser){
        $set[] = "shareuser = {$shareuser}";
        $set[] = "getuser = {$getuser}";
        $set[] = "addtime = now()";
        $ret = $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH,2,'wx_share',$set);
        return $ret;
    }
}