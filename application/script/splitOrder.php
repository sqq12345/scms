<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/6/27
 * Time: 9:37
 */
require_once (__DIR__.'/config.php');

$pdo = mydqlpdo();

//$orderSn = 'CO2018062709241984393';
//$sql = "SELECT * FROM product_order WHERE order_sn = '{$orderSn}'";
//
//$res = $pdo->query($sql);
//$order = $res->fetch();
//if(!empty($order)){
//    $sql = "SELECT * FROM order_items WHERE order_sn = '{$orderSn}' AND status = 1";
//    $res = $pdo->query($sql);
//    $orderItems = $res->fetchAll();
//    if(!empty($orderItems) && sizeof($orderItems) > 0){
//        foreach ($orderItems as $key=>$value){
//            $sql = "SELECT * FROM re_product WHERE id = {$value['product_id']}";
//            $res = $pdo->query($sql);
//            $product = $res->fetch();
//            if(!empty($product)){
//                $sql = "SELECT * FROM fare_template WHERE id = {$product['fare_id']}";
//                $res = $pdo->query($sql);
//                $template = $res->fetch();
//                $orderItems[$key]['tid'] = 0;
//                $orderItems[$key]['dispatch_region'] = '';
//                if(!empty($template)){
//                    $orderItems[$key]['tid'] = $template['id'];
//                    $orderItems[$key]['dispatch_region'] = $template['dispatch_region'];
//                }
//                $orderItems[$key]['weight'] = 0;
//                $sql = "SELECT * FROM re_product_sku WHERE id = {$value['sku_id']}";
//                $res = $pdo->query($sql);
//                $sku = $res->fetch();
//                if(!empty($sku)){
//                    $orderItems[$key]['weight'] = $sku['weight'];
//                }
//            }
//        }
//        $sortData = [];
//        foreach ($orderItems as $key=>$value){
//            $sortData[$value['dispatch_region']]['items'][] = $value;
//        }
//        foreach ($sortData as $key=>$value){
//            $sortData[$key]['distribution_fee'] = 0;
//        }
//        if($order['distribution_fee'] > 0){
//            $total = 0;
//            foreach ($sortData as $key=>$value){
//                $templateArray = [];
//                foreach ($value['items'] as $k=>$v){
//                    $templateArray[$v['tid']][] = $v;
//                }
//                $fee = 0;
//                foreach ($templateArray as $k=>$val){
//                    $sql = "SELECT * FROM fare_template WHERE id = {$k}";
//                    $res = $pdo->query($sql);
//                    $template = $res->fetch();
//
//                    if(!empty($template)){
//                        if($template['is_incl_postage'] == 1){
//                            $sql = "SELECT * FROM carry_mode WHERE fare_id = {$template['id']}";
//                            $res = $pdo->query($sql);
//                            $carryMode = $res->fetch();
//                            if(!empty($carryMode)){
//                                if($template['valuation_model'] == 1){
//                                    //按照件数计费
//                                    $num = 0;
//                                    foreach ($val as $v){
//                                        $num = $num + $v['num'];
//                                    }
//                                    if($num <= $carryMode['first_piece']){
//                                        $fee = $carryMode['first_amount'];
//                                    }else{
//                                        $fee = $carryMode['first_amount'] + ceil(($num-$carryMode['first_piece'])/$carryMode['second_piece']) * $carryMode['second_amount'];
//                                    }
//                                }elseif ($template['valuation_model'] == 2){
//                                    //按照重量计费
//                                    $weight = 0;
//                                    foreach ($val as $v){
//                                        $weight = $v['num'] * $v['weight'] + $weight;
//                                    }
//                                    if($weight <= $carryMode['first_weight']){
//                                        $fee = $carryMode['first_amount'];
//                                    }else{
//                                        $fee = $carryMode['first_amount'] + ceil(($weight-$carryMode['first_weight'])/$carryMode['second_weight']) * $carryMode['second_amount'];
//                                    }
//                                }else{
//
//                                }
//                            }
//                        }elseif ($template['is_incl_postage'] == 2 || $template['is_incl_postage'] == 3){
//                            $fee = 0;
//                        }else{
//
//                        }
//                    }
//                    $total = $total + $fee;
//                }
//                $sortData[$key]['distribution_fee'] = $fee;
//            }
//        }
//        foreach ($sortData as $key=>$value){
//            $splitOrderSn = date('YmdHis') . rand(10000, 99999);
//            $sql = "INSERT INTO split_order (order_sn,split_order_sn,user_id,dispatch_region,distribution_fee,province,city,area,address,code,receiver_name,receiver_phone,create_time) VALUES ('{$order['order_sn']}','{$splitOrderSn}',{$order['user_id']},'{$key}',{$value['distribution_fee']},'{$order['province']}','{$order['city']}','{$order['area']}','{$order['address']}','{$order['code']}','{$order['receiver_name']}','{$order['receiver_phone']}',now())";
//            $pdo->exec($sql);
//            foreach ($value['items'] as $v){
//                $sql = "INSERT INTO split_order_items (split_order_sn,product_id,sku_id,product_type,product_name,model,attr,product_icon,product_price,num,create_time) VALUES ('{$splitOrderSn}',{$v['product_id']},{$v['sku_id']},{$v['product_type']},'{$v['product_name']}','{$v['model']}','{$v['attr']}','{$v['product_icon']}',{$v['product_price']},{$v['num']},now())";
//                $pdo->exec($sql);
//            }
//        }
//    }
//}

$sql = "SELECT * FROM product_order WHERE order_status = 'pendingDelivery' AND status = 1 AND is_split = 0";
$res = $pdo->query($sql);
$orderList = $res->fetchAll();
if(!empty($orderList) && sizeof($orderList) > 0){
    foreach ($orderList as $order){
        $sql = "SELECT * FROM order_items WHERE order_sn = '{$order['order_sn']}' AND status = 1";
        $res = $pdo->query($sql);
        $orderItems = $res->fetchAll();
        if(!empty($orderItems) && sizeof($orderItems) > 0){
            foreach ($orderItems as $key=>$value){
                $sql = "SELECT * FROM re_product WHERE id = {$value['product_id']}";
                $res = $pdo->query($sql);
                $product = $res->fetch();
                if(!empty($product)){
                    $orderItems[$key]['weight'] = 0;
                    $orderItems[$key]['tid'] = 0;
                    $orderItems[$key]['dispatch_region'] = '';
                    $sql = "SELECT * FROM re_product_sku WHERE id = {$value['sku_id']}";
                    $res = $pdo->query($sql);
                    $sku = $res->fetch();
                    if(!empty($sku)){
                        $orderItems[$key]['weight'] = $sku['weight'];
                        $sql = "SELECT * FROM fare_template WHERE id = {$sku['fare_id']}";
                        $res = $pdo->query($sql);
                        $template = $res->fetch();
                        if(!empty($template)){
                            $orderItems[$key]['tid'] = $template['id'];
                            $orderItems[$key]['dispatch_region'] = $template['dispatch_region'];
                        }
                    }
                }
            }
            $sortData = [];
            foreach ($orderItems as $key=>$value){
                $sortData[$value['dispatch_region']]['items'][] = $value;
            }
            foreach ($sortData as $key=>$value){
                $sortData[$key]['distribution_fee'] = 0;
            }

            if($order['distribution_fee'] > 0){
                $total = 0;
                foreach ($sortData as $key=>$value){
                    $templateArray = [];
                    foreach ($value['items'] as $k=>$v){
                        $templateArray[$v['tid']][] = $v;
                    }
                    $fee = 0;
                    foreach ($templateArray as $k=>$val){
                        $sql = "SELECT * FROM fare_template WHERE id = {$k}";
                        $res = $pdo->query($sql);
                        $template = $res->fetch();

                        if(!empty($template)){
                            if($template['is_incl_postage'] == 1){
                                $sql = "SELECT * FROM carry_mode WHERE fare_id = {$template['id']}";
                                $res = $pdo->query($sql);
                                $carryMode = $res->fetch();
                                if(!empty($carryMode)){
                                    if($template['valuation_model'] == 1){
                                        //按照件数计费
                                        $num = 0;
                                        foreach ($val as $v){
                                            $num = $num + $v['num'];
                                        }
                                        if($num <= $carryMode['first_piece']){
                                            $fee = $carryMode['first_amount'];
                                        }else{
                                            $fee = $carryMode['first_amount'] + ceil(($num-$carryMode['first_piece'])/$carryMode['second_piece']) * $carryMode['second_amount'];
                                        }
                                    }elseif ($template['valuation_model'] == 2){
                                        //按照重量计费
                                        $weight = 0;
                                        foreach ($val as $v){
                                            $weight = $v['num'] * $v['weight'] + $weight;
                                        }
                                        if($weight <= $carryMode['first_weight']){
                                            $fee = $carryMode['first_amount'];
                                        }else{
                                            $fee = $carryMode['first_amount'] + ceil(($weight-$carryMode['first_weight'])/$carryMode['second_weight']) * $carryMode['second_amount'];
                                        }
                                    }else{

                                    }
                                }
                            }elseif ($template['is_incl_postage'] == 2 || $template['is_incl_postage'] == 3){
                                $fee = 0;
                            }else{

                            }
                        }
                        $total = $total + $fee;
                    }
                    $sortData[$key]['distribution_fee'] = $fee;
                }
            }
            foreach ($sortData as $key=>$value){
                $splitOrderSn = date('YmdHis') . rand(10000, 99999);
                $sql = "INSERT INTO split_order (order_sn,split_order_sn,user_id,dispatch_region,distribution_fee,province,city,area,address,code,receiver_name,receiver_phone,create_time) VALUES ('{$order['order_sn']}','{$splitOrderSn}',{$order['user_id']},'{$key}',{$value['distribution_fee']},'{$order['province']}','{$order['city']}','{$order['area']}','{$order['address']}','{$order['code']}','{$order['receiver_name']}','{$order['receiver_phone']}',now())";
                $pdo->exec($sql);
                foreach ($value['items'] as $v){
                    $sql = "INSERT INTO split_order_items (split_order_sn,product_id,sku_id,product_type,product_name,model,attr,product_icon,product_price,num,create_time) VALUES ('{$splitOrderSn}',{$v['product_id']},{$v['sku_id']},{$v['product_type']},'{$v['product_name']}','{$v['model']}','{$v['attr']}','{$v['product_icon']}',{$v['product_price']},{$v['num']},now())";
                    $pdo->exec($sql);
                }
            }
            $sql = "UPDATE product_order SET is_split = 1 WHERE order_sn = '{$order['order_sn']}' AND  status = 1 AND order_status = 'pendingDelivery'";
            $ret = $pdo->exec($sql);
        }
    }

}
$pdo = null;
echo '脚本跑完';
exit();