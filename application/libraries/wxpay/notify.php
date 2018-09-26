<?php
/**
 * 原生支付（扫码支付）及公众号支付的异步回调通知
 * 说明：需要在native.php或者jsapi.php中的填写回调地址。例如：http://www.xxx.com/wx/notify.php
 * 付款成功后，微信服务器会将付款结果通知到该页面
 */
header('Content-type:text/html; Charset=utf-8');
$mchid = '1504640251';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
$appid = 'wx7fc6fef24ddffde6';  //公众号APPID 通过微信支付商户资料审核后邮件发送
$apiKey = '4LUgUywoGg9R6WvCZnxDqnDxgVwTZ5Y1';   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
$wxPay = new WxpayService($mchid,$appid,$apiKey);
$result = $wxPay->notify();

/*数据库连接*/
define ('DB_DSN','mysql:host=119.3.28.226;port=3306;dbname=purchase');
define ('DB_USER','purchase');
define ('DB_PASSWD','zPMBiMbFxpmRcE5r');

/**
 * 链接数据库
 *
 */
function mydqlpdo(){
    try{
        $pdo = new PDO(DB_DSN, DB_USER, DB_PASSWD);
        $pdo->query('set names utf8');
    } catch(Exception $e) {
        echo "db error!";
    }
    return $pdo;
}
if($result){
    //完成你的逻辑
    //例如连接数据库，获取付款金额$result['cash_fee']，获取订单号$result['out_trade_no']，修改数据库中的订单状态等;
    if(array_key_exists("return_code",$result) && array_key_exists("result_code",$result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS"){
        //商户订单号
        $out_trade_no = substr($result["out_trade_no"],2);
        //微信交易号
        $trade_no = $result["transaction_id"];
        //交易类型
        $payType = "h5wxpay";
        if($result["trade_type"] == "JSAPI"){
            if($result["appid"] == 'wxe9af8c5281481a90'){
                $payType = "xcxpay";
            }else{
                $payType = "wxpay";
            }
        }elseif($result["trade_type"] == "NATIVE"){
            $payType = "qrcodepay";
        }
        $pdo = mydqlpdo();
        $sql = "SELECT * FROM pay_info WHERE order_sn = '{$out_trade_no}' AND status = 'paying' AND pay_type = '{$payType}'";
        $res = $pdo->query($sql);
        $info = $res->fetch();
        if(!empty($info)){
            $sql = "UPDATE pay_info SET status = 'payed',pay_time = now(),pay_sn = '{$trade_no}' WHERE order_sn = '{$out_trade_no}' AND status = 'paying' AND pay_type = '{$payType}' ";
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

}else{
    echo 'pay error';
}
class WxpayService
{
    protected $mchid;
    protected $appid;
    protected $apiKey;
    public function __construct($mchid, $appid, $key)
    {
        $this->mchid = $mchid;
        $this->appid = $appid;
        $this->apiKey = $key;
    }
    public function notify()
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($postObj === false) {
            die('parse xml error');
        }
        if ($postObj->return_code != 'SUCCESS') {
            die($postObj->return_msg);
        }
        if ($postObj->result_code != 'SUCCESS') {
            die($postObj->err_code);
        }
        $arr = (array)$postObj;
        unset($arr['sign']);
        if (self::getSign($arr, $config['key']) == $postObj->sign) {
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            return $arr;
        }
    }
    /**
     * 获取签名
     */
    public static function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }
    protected static function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}