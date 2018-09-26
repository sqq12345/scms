<?php
/**
 * Created by PhpStorm.
 * 更新子订单状态
 * User: huiyong.yu
 * Date: 2018/7/5
 * Time: 9:21
 */
set_time_limit(0);
require_once (__DIR__ . '/config.php');
$pdo = mydqlpdo();

$sql = "SELECT * FROM split_order WHERE order_status = 'pendingDelivery' ";
$res = $pdo->query($sql);
$list = $res->fetchAll();

if(!empty($list)){
    foreach ($list as $val){
        $sql = "SELECT * FROM split_order_items WHERE split_order_sn = '{$val['split_order_sn']}' AND status = 1";
        $res = $pdo->query($sql);
        $items = $res->fetchAll();
        if(!empty($items)){
            $is_refund = 1;
            foreach ($items as $v){
                if($v['refund_num'] < $v['num']){
                    $is_refund = 0;
                    break;
                }
            }
            if($is_refund > 0){
                $sql = "UPDATE split_order SET order_status = 'cancel' WHERE split_order_sn = '{$val['split_order_sn']}' AND  status = 1 AND order_status = 'pendingDelivery'";
                $ret = $pdo->exec($sql);
            }
        }
    }
}
$pdo = null;
exit;