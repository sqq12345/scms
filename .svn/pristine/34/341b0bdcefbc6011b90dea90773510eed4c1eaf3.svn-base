<?php
/**
 * 获取用户openid
 */
error_reporting(E_ALL & ~E_NOTICE);
include './oauth2.php';
//if(!isset($_SESSION['openid'])){
    $code=$_GET['code'];//code 微信接口参数(必须)
    $state=$_GET['state'];//state微信接口参数(不需传参则不用)；若传参可考虑规则： 'act'.'arg1'.'add'.'arg2'
    
    $APPID='wx7fc6fef24ddffde6';
    $SECRET='34888890e5062883065884fc738de9d1';
    $REDIRECT_URL='https://api.shichamaishou.com/application/libraries/wxpay/test.php';//当前页面地址
    $oauth2=new oauth2();
    $oauth2->init($APPID, $SECRET,$REDIRECT_URL);
   // sleep(10);
   // if(empty($code)){
    //    $oauth2->get_code($state);//获取code，会重定向到当前页。若需传参，使用$state变量传参。
//	return false;
  //  }
    $openid=$oauth2->get_openid();//获取openid
    if(empty($openid)){
    var_dump(1111);
    	return false;
    }
 //   session_start();
 //   $_SESSION['openId'] = $openid;
  //  echo '</br>welcome test!';
 //   echo '</br>code: '.$code;
  //  echo '</br>openid: '.$openid;
   // header('Location: https://www.baidu.com');
//}
//sleep(5);
$url = "Location: https://www.shichamaishou.com/#".$_GET['path']."?openId=".$openid;
//var_dump($url);
//return false;
header($url);
//$url = "https://api.shichamaishou.com/test";
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, $url);
//curl_exec($ch);
//curl_close($ch);
exit;

?>