<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/23
 * Time: 14:04
 */
//set_time_limit(0);
require_once (__DIR__.'/config.php');

$pdo = mydqlpdo();
date_default_timezone_set('PRC');
$time = date('Y-m-d H:i:s');
$sql = "SELECT * FROM bespeak_order where order_status IN ('pendingOrder','takingOrder','pengdingBuy','noGoods','allReject') AND scare_buy_time < '{$time}' AND status = 1  ";
$res = $pdo->query($sql);
$orderlist = $res->fetchAll();
if (!empty($orderlist)) {
    foreach ($orderlist as $val){
        //更新订单状态为超时
        $sql = "UPDATE bespeak_order SET order_status = 'overtime' WHERE id = {$val['id']}  and  status = 1 ";
        $ret = $pdo->exec($sql);
    }
}
$pdo = null;
exit;
