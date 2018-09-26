<?php
/**
 * Created by PhpStorm.
 * 根据子订单状态更新订单状态
 * User: huiyong.yu
 * Date: 2018/7/3
 * Time: 14:22
 */
set_time_limit(0);
require_once (__DIR__ . '/config.php');
$pdo = mydqlpdo();

$sql = "SELECT * FROM product_order WHERE order_status = 'delivered' AND status = 1";
$res = $pdo->query($sql);
$list = $res->fetchAll();
if(!empty($list)){
    foreach ($list as $val){
        $sql = "SELECT * FROM split_order WHERE order_sn = '{$val['order_sn']}' AND status = 1";
        $res = $pdo->query($sql);
        $spkitOrderList = $res->fetchAll();
        if(!empty($spkitOrderList)){
            $is_complete = 1;
            foreach ($spkitOrderList as $v){
                if($v['order_status'] != 'complete'){
                    $is_complete = 0;
                    break;
                }
            }
            if($is_complete > 0){
                $sql = "UPDATE product_order SET order_status = 'complete' WHERE order_sn = '{$val['order_sn']}' AND  status = 1 AND order_status = 'delivered'";
                $ret = $pdo->exec($sql);
            }
        }
    }
}

$pdo = null;
exit;