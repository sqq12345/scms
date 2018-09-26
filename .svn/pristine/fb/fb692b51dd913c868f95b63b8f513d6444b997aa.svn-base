<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/23
 * Time: 13:03
 */
//set_time_limit(0);
require_once (__DIR__.'/config.php');
header('Content-Type: application/json; charset=utf-8');
$pdo = mydqlpdo();
date_default_timezone_set('PRC');
$nowtime = date('Y-m-d H:i:s',strtotime('-20 minute'));
$sql = "SELECT * FROM product_order where order_status = 'waitforpay' and status = 1 AND create_time < '{$nowtime}'";
$res = $pdo->query($sql);
$orderlist = $res->fetchAll();
if (!empty($orderlist)) {
    foreach ($orderlist as $val){
        //更新订单状态为过时取消
        $sql = "UPDATE product_order SET order_status = 'cancel' WHERE id = {$val['id']}  and  status = 1 ";
        $ret = $pdo->exec($sql);
        //如果商品为下单减库存的，需要释放库存
        $sql = "SELECT * FROM order_items WHERE order_sn = '{$val['order_sn']}' AND status = 1";
        $res = $pdo->query($sql);
        $orderitems = $res->fetchAll();
        if(!empty($orderitems)){
            foreach ($orderitems as $k => $v){
                $sql = "SELECT * FROM re_product WHERE id = {$v['product_id']}";
                $res = $pdo->query($sql);
                $row = $res->fetch();
                if($row['is_reduce_stock'] > 0){
                    if($v['sku_id'] > 0){
                        $sql = "UPDATE re_product_sku SET stock = stock + {$v['num']} WHERE id = {$v['sku_id']}";
                        $ret = $pdo->exec($sql);
                    }
                }
                if($v['product_type'] == 3){
                    $sql = "UPDATE product_second_kill SET num = num + {$v['num']} where skuid = {$v['sku_id']} and starttime < now() and endtime > now()";
                    $ret = $pdo->exec($sql);
                }
            }
        }
    }
}
$nowtime = date('Y-m-d H:i:s',strtotime('-10 minute'));
$sql = "SELECT * FROM product_order where order_status = 'waitforpay' and status = 1 AND is_send_msg = 0 AND create_time < '{$nowtime}'";
$res = $pdo->query($sql);
$orderlist = $res->fetchAll();
if (!empty($orderlist)) {
    foreach ($orderlist as $val){
        //新增订单未支付通知
        $url = 'https://www.shichamaishou.com/orderDetail?type=order&isNotice=1&orderSn='.$val['order_sn'];
        $miniprogram = array(

        );
        $miniprogram = serialize($miniprogram);
        $first = "客官，您好！您的订单未支付，即将关闭。";
        $postData = array(
            "first"=>array(
                "value"=> $first,
                "color"=>"#173177"
            ),
            "ordertape"=>array(
                "value"=> $val['create_time'],
                "color"=>"#173177"
            ),
            "ordeID"=>array(
                "value"=> $val['order_sn'],
                "color"=>"#173177"
            ),
            "remark"=>array(
                "value"=> "还未付款，未付款订单10分钟内关闭，请及时付款。感谢您对时差买手的青睐！",
                "color"=>"#173177"
            ),
        );
        $postData = serialize($postData);
        $sql = "INSERT INTO wx_message (user_id,template_id,url,mini_program,post_data,create_time) VALUES ({$val['user_id']},'X1-LahhhFz2YWq7va8XCOSo7PdNxqH9Chy7Wg7z2Y_w','{$url}','{$miniprogram}','{$postData}',now()) ";
        $pdo->exec($sql);
        $sql = "UPDATE product_order SET is_send_msg = 1 WHERE order_sn = '{$val['order_sn']}' AND  status = 1 AND order_status = 'waitforpay'";
        $pdo->exec($sql);
    }
}
$pdo = null;
echo "脚本跑完";
exit;
