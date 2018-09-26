<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/7/11
 * Time: 12:07
 */
require_once (__DIR__.'/config.php');

$pdo = mydqlpdo();

$sql = "SELECT * FROM wx_message WHERE status = 1 AND times < 3";
$res = $pdo->query($sql);
$list = $res->fetchAll();
if(!empty($list)){
    foreach ($list as $val){
        $sql = "SELECT * FROM customer WHERE id = {$val['user_id']}";
        $res = $pdo->query($sql);
        $user = $res->fetch();
        if(!empty($user)){
            $res = wxSetSend($user['openid'],$val['template_id'],$val['url'],unserialize($val['mini_program']),unserialize($val['post_data']));
            if($res['errcode'] == 0){
                $sql = "UPDATE wx_message SET status = 2 WHERE id = {$val['id']} AND  status = 1";
                $pdo->exec($sql);
            }else{
                $sql = "UPDATE wx_message SET times = times + 1 WHERE id = {$val['id']} AND  status = 1";
                $pdo->exec($sql);
            }
        }else{
            $sql = "UPDATE wx_message SET times = times + 1 WHERE id = {$val['id']} AND  status = 1";
            $pdo->exec($sql);
        }
    }
}
$pdo = null;
echo '脚本跑完';
exit;

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
    if(!empty($access) && isset($access["access_token"])){
        return $access['access_token'];
    }else{
        return '';
    }
}

function wxSetSend($touser, $template_id, $url,$miniprogram, $data, $topcolor = '#7B68EE'){
    $template = array(
        'touser' => $touser,
        'template_id' => $template_id,
        'url' => $url,
        'miniprogram' =>$miniprogram,
        'topcolor' => $topcolor,
        'data' => $data
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