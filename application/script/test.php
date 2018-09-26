<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/7/10
 * Time: 9:16
 */
require_once (__DIR__.'/config.php');
$post_data = array(
    "first"=>array(
        "value"=>"恭喜你购买成功！" ,
        "color"=>"#173177"
    ),
);
$res = wxSetSend('oQLbq0Wofl2hPCoWMvhvrF4IKVuU','3gCsqQ0JJTE-atoyw8s-UOxju-thFgNVGxwHPjv_ic4','https://www.shichamaishou.com',$post_data);
var_dump($res);

function getWxAccessToken(){
    //获取access_token
    $url_get = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='
        . WCHAT_APPID . '&secret=' . WCHAT_APPSECRET;
    $ch1 = curl_init ();
    $timeout = 5;
    curl_setopt($ch1, CURLOPT_URL, $url_get );
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false );
    $accesstxt = curl_exec ($ch1);
    curl_close ($ch1);
    $access = json_decode($accesstxt,true);
    // 缓存数据7200秒
    var_dump($access);
    if(!empty($access) && isset($access["access_token"])){
        return $access['access_token'];
    }else{
        return '';
    }
}

function wxSetSend($touser, $template_id, $url, $data, $topcolor = '#7B68EE'){
    $template = array(
        'touser' => $touser,
        'template_id' => $template_id,
        'url' => $url,
        'topcolor' => $topcolor,
        'data' => $data,
	'miniprogram'=>array(
	    'appid'=>'wxe9af8c5281481a90',
	    'pagepath'=>'pages/index/main'
	)
    );
    $jsonData = json_encode($template);
    $wxAccessToken = getWxAccessToken();
    $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$wxAccessToken;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($result,true);
    return $result;
}