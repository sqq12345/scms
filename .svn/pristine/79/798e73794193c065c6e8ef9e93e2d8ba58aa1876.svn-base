<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：2.0
 * 修改日期：2016-11-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
require_once("config.php");
require_once("Db_config.php");
require_once 'wappay/service/AlipayTradeService.php';


$arr=$_POST;
$alipaySevice = new AlipayTradeService($config); 
$alipaySevice->writeLog(var_export($_POST,true));
$result = $alipaySevice->check($arr);

/* 实际验证过程建议商户添加以下校验。
1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
4、验证app_id是否为该商户本身。
*/
if($result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号

	$out_trade_no = $_POST['out_trade_no'];

	//支付宝交易号

	$trade_no = $_POST['trade_no'];

	//交易状态
	$trade_status = $_POST['trade_status'];


    if($_POST['trade_status'] == 'TRADE_FINISHED') {

		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
			//如果有做过处理，不执行商户的业务程序			
		//注意：
		//付款完成后，支付宝系统发送该交易状态通知
        $pdo = mydqlpdo();
        $sql = "SELECT * FROM pay_info WHERE order_sn = '{$out_trade_no}' AND status = 'paying' AND pay_type = 'alipay'";
        $res = $pdo->query($sql);
        $info = $res->fetch();
        if(!empty($info)){
            $sql = "UPDATE pay_info SET status = 'payed',pay_time = now(),pay_sn = '{$trade_no}' WHERE order_sn = '{$out_trade_no}' AND status = 'paying' AND pay_type = 'alipay' ";
            $ret = $pdo->exec($sql);
            if($ret){
                if(strstr($out_trade_no,'CO')){
                    $sql = "UPDATE product_order SET order_status = 'pendingDelivery' WHERE order_sn = '{$out_trade_no}' AND order_status = 'waitforpay'";
                    $pdo->exec($sql);
                    //订单log
                    $sql = "INSERT INTO order_log (order_sn,user_id,user_type,from_status,to_status,remark,create_time,status) VALUES ('{$out_trade_no}',{$info['user_id']},1,'waitforpay','pendingDelivery','支付成功',now(),1)";
                    $pdo->exec($sql);
                    //更新用户月消费金额
                    $sql = "SELECT * FROM order_items WHERE order_sn = '{$out_trade_no}' AND status = 1";
                    $res = $pdo->query($sql);
                    $items = $res->fetchAll();
                    if(!empty($items)){
                        $total = 0;
                        foreach ($items as $k => $v){
                            $total = $v['product_price'] * $v['num'] + $total;
                        }
                        $sql = "UPDATE limit_amount SET amount = amount + {$total} WHERE user_id = {$info['user_id']}";
                        $pdo->exec($sql);
                        $first = "我们已收到您的货款，开始为您打包商品，请耐心等待: )";
                        $orderMoneySum = $info['fee']/100;
                        $orderProductName = $items[0]['product_name'].'...';
                        $remark = '如有问题请直接在微信留言，将第一时间为您服务！';
                        $url = 'https://www.shichamaishou.com/orderDetail?type=order&isNotice=1&orderSn='.$out_trade_no;
                        $miniprogram = array(

                        );
                        $miniprogram = serialize($miniprogram);
                        $postData = array(
                            "first"=>array(
                                "value"=> $first,
                                "color"=>"#173177"
                            ),
                            "orderMoneySum"=>array(
                                "value"=> $orderMoneySum.'元',
                                "color"=>"#173177"
                            ),
                            "orderProductName"=>array(
                                "value"=> $orderProductName,
                                "color"=>"#173177"
                            ),
                            "Remark"=>array(
                                "value"=> $remark,
                                "color"=>"#173177"
                            ),
                        );
                        $postData = serialize($postData);
                        $sql = "INSERT INTO wx_message (user_id,template_id,url,mini_program,post_data,times,create_time) VALUES ({$info['user_id']},'8KOxMN1r4DvGB9E9bfYFbQmYCSKUafE3L3jx91g8oR0','{$url}','{$miniprogram}','{$postData}',0,now())";
                        $pdo->exec($sql);
                    }
                }elseif (strstr($out_trade_no,'YY')){
                    $sql = "UPDATE bespeak_order SET order_status = 'pendingOrder' WHERE order_sn = '{$out_trade_no}' AND order_status = 'waitforpay'";
                    $pdo->exec($sql);
                    //订单log
                    $sql = "INSERT INTO order_log (order_sn,user_id,user_type,from_status,to_status,remark,create_time,status) VALUES ('{$out_trade_no}',{$info['user_id']},1,'waitforpay','pendingOrder','支付成功',now(),1)";
                    $pdo->exec($sql);
                    //更新用户月消费金额
                    $sql = "SELECT * FROM bespeak_order_items WHERE order_sn = '{$out_trade_no}' AND status = 1";
                    $res = $pdo->query($sql);
                    $item = $res->fetch();
                    if(!empty($item)){
                        $total = $item['product_price'] * $item['num'];
                        $sql = "UPDATE limit_amount SET amount = amount + {$total} WHERE user_id = {$info['user_id']}";
                        $pdo->exec($sql);

                        $first = "我们已收到您的货款，开始为您打包商品，请耐心等待: )";
                        $orderMoneySum = $info['fee']/100;
                        $orderProductName = $item['product_name'].'...';
                        $remark = '如有问题请直接在微信留言，将第一时间为您服务！';
                        $url = 'https://www.shichamaishou.com/orderDetail?type=reservation&isNotice=1&orderSn='.$out_trade_no;
                        $miniprogram = array(

                        );
                        $miniprogram = serialize($miniprogram);
                        $postData = array(
                            "first"=>array(
                                "value"=> $first,
                                "color"=>"#173177"
                            ),
                            "orderMoneySum"=>array(
                                "value"=> $orderMoneySum.'元',
                                "color"=>"#173177"
                            ),
                            "orderProductName"=>array(
                                "value"=> $orderProductName,
                                "color"=>"#173177"
                            ),
                            "Remark"=>array(
                                "value"=> $remark,
                                "color"=>"#173177"
                            ),
                        );
                        $postData = serialize($postData);
                        $sql = "INSERT INTO wx_message (user_id,template_id,url,mini_program,post_data,times,create_time) VALUES ({$info['user_id']},'8KOxMN1r4DvGB9E9bfYFbQmYCSKUafE3L3jx91g8oR0','{$url}','{$miniprogram}','{$postData}',0,now())";
                        $pdo->exec($sql);
                    }
                }elseif (strstr($out_trade_no,'RR')){
                    $sql = "SELECT * FROM recharge_record WHERE record_sn = '{$out_trade_no}' AND record_status = 'waitforpay' AND status = 1";
                    $res = $pdo->query($sql);
                    $row = $res->fetch();
                    if(!empty($row)){
                        $sql = "UPDATE recharge_record SET record_status = 'payed' WHERE record_sn = '{$out_trade_no}' AND record_status = 'waitforpay' AND status = 1 ";
                        $pdo->exec($sql);
                        $sql = "SELECT * FROM customer WHERE id = {$row['user_id']} AND status = 1";
                        $res = $pdo->query($sql);
                        $user = $res->fetch();
                        if(!empty($user)){
                            $vipTime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",strtotime($user['vip_time']))."+ {$row['days']} days"));
                            $sql = "UPDATE customer SET vip_time = '{$vipTime}' WHERE id = {$row['user_id']} AND status = 1 ";
                            $pdo->exec($sql);

                            $first = "您好，您已成功进行会员续费充值。";
                            $accountType = "会员帐号";
                            $account = $user['username'];
                            $amount = $info['fee']/100;
                            $result = "充值成功";
                            $remark = '如有问题请直接在微信留言，将第一时间为您服务！';
                            $url = 'https://www.shichamaishou.com/personalCenter';
                            $miniprogram = array(

                            );
                            $miniprogram = serialize($miniprogram);
                            $postData = array(
                                "first"=>array(
                                    "value"=> $first,
                                    "color"=>"#173177"
                                ),
                                "accountType"=>array(
                                    "value"=> $accountType,
                                    "color"=>"#173177"
                                ),
                                "account"=>array(
                                    "value"=> $account,
                                    "color"=>"#173177"
                                ),
                                "amount"=>array(
                                    "value"=> $amount.'元',
                                    "color"=>"#173177"
                                ),
                                "result"=>array(
                                    "value"=> $result,
                                    "color"=>"#173177"
                                ),
                                "Remark"=>array(
                                    "value"=> $remark,
                                    "color"=>"#173177"
                                ),
                            );
                            $postData = serialize($postData);
                            $sql = "INSERT INTO wx_message (user_id,template_id,url,mini_program,post_data,times,create_time) VALUES ({$info['user_id']},'KY3UGR6Q3RDUaulXIJrwUxcM56ch7sdLCOCZGf8ern4','{$url}','{$miniprogram}','{$postData}',0,now())";
                            $pdo->exec($sql);
                        }
                    }
                }else{

                }
            }
        }
        $pdo = null;
    }
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        
	echo "success";		//请不要修改或删除
		
}else {
    //验证失败
    echo "fail";	//请不要修改或删除

}

?>

