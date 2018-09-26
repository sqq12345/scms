<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/25
 * Time: 9:58
 */
//set_time_limit(0);
require_once (__DIR__ .'/libraries/Mailtracking.php');
require_once (__DIR__ . '/config.php');
date_default_timezone_set('PRC');
$time = date("Y-m-d H:i:s");
$pdo = mydqlpdo();

//现货订单
$sql = "SELECT * FROM product_order WHERE order_status = 'delivered' AND status = 1";
$res = $pdo->query($sql);
$list = $res->fetchAll();
if(!empty($list)){
    foreach ($list as $val){
        $sql = "SELECT * FROM order_delivery WHERE order_sn = '{$val['order_sn']}'";
        $res = $pdo->query($sql);
        $row = $res->fetch();
        if(!empty($row)){
            if(empty($row['delivery_sn'])){
                continue;
            }
            if (!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT'])) {
                $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; InfoPath.2; AskTbPTV/5.17.0.25589; Alexa Toolbar)';
            }
            $MT = new Mailtracking();
            $MT->setMailId($row['delivery_sn'],$row['code']);
            $MT->curlPost();
            $info = $MT->getMailInfo();
            $info = json_decode($info, true);
            if($info['status'] != 200){
                continue;
            }
            if($info['state'] == 3){
                $sql = "UPDATE product_order SET  order_status = 'complete',update_time = '{$time}' WHERE id = {$val['id']} AND order_status = 'delivered'";
                $ret = $pdo->query($sql);
                continue;
            }elseif($info['state'] == 4){
                $sql = "UPDATE product_order SET  order_status = 'refused' WHERE id = {$val['id']} AND order_status = 'delivered'";
                $ret = $pdo->query($sql);
                continue;
            }else{
                continue;
            }
        }
    }
}

//一件代发订单
$sql = "SELECT * FROM bespeak_order WHERE order_status = 'delivered' AND status = 1";
$res = $pdo->query($sql);
$list = $res->fetchAll();
if(!empty($list)){
    foreach ($list as $val){
        $sql = "SELECT * FROM order_delivery WHERE order_sn = '{$val['order_sn']}'";
        $res = $pdo->query($sql);
        $row = $res->fetch();
        if(!empty($row)){
            if(empty($row['delivery_sn'])){
                continue;
            }
            if (!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT'])) {
                $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; InfoPath.2; AskTbPTV/5.17.0.25589; Alexa Toolbar)';
            }
            $MT = new Mailtracking();
            $MT->setMailId($row['delivery_sn'],$row['code']);
            $MT->curlPost();
            $info = $MT->getMailInfo();
            $info = json_decode($info, true);
            if($info['status'] != 200){
                continue;
            }
            if($info['state'] == 3){
                $sql = "UPDATE bespeak_order SET  order_status = 'complete',update_time = '{$time}' WHERE id = {$val['id']} AND order_status = 'delivered'";
                $ret = $pdo->query($sql);
                continue;
            }elseif($info['state'] == 4){
                $sql = "UPDATE bespeak_order SET  order_status = 'refused' WHERE id = {$val['id']} AND order_status = 'delivered'";
                $ret = $pdo->query($sql);
                continue;
            }else{
                continue;
            }
        }
    }
}
$pdo = null;
echo '脚本跑完';
exit();