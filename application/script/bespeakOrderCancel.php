<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/23
 * Time: 14:27
 */
//set_time_limit(0);
require_once (__DIR__.'/config.php');

$pdo = mydqlpdo();
date_default_timezone_set('PRC');
$nowtime = date('Y-m-d H:i:s',strtotime('-20 minute'));
$sql = "SELECT * FROM bespeak_order where order_status = 'waitforpay' and status = 1 AND create_time < '{$nowtime}' ";
$res = $pdo->query($sql);
$orderlist = $res->fetchAll();
if (!empty($orderlist)) {
    foreach ($orderlist as $val){
        //更新订单状态为过时取消
        $sql = "UPDATE bespeak_order SET order_status = 'cancel' WHERE id = {$val['id']}  and  status = 1 ";
        $ret = $pdo->exec($sql);
    }
}
$pdo = null;
exit;