<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/29
 * Time: 9:48
 */
//set_time_limit(0);
require_once (__DIR__.'/config.php');

$pdo = mydqlpdo();
date_default_timezone_set('PRC');
$nowtime = date('Y-m-d H:i:s',strtotime('-5 minute'));
$sql = "SELECT * FROM captcha where status = 1 AND create_time < '{$nowtime}'";
$res = $pdo->query($sql);
$list = $res->fetchAll();
if (!empty($list)) {
    foreach ($list as $val){
        //更新验证码状态为已使用状态
        $sql = "UPDATE captcha SET status = 2 WHERE id = {$val['id']}  and  status = 1 ";
        $ret = $pdo->exec($sql);
    }
}
$pdo = null;
exit;