<?php
/**
 * Created by PhpStorm.
 * 微信
 * User: huiyong.yu
 * Date: 2018/5/28
 * Time: 17:57
 */
class WxChat{

	public function getOpenId($data){
		$dataval = array();
		$dataval["openid"] = 0;
        $userid = isset($data['userid']) ? intval($data['userid']) : 0; //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $mold = isset($data['mold']) ? intval($data['mold']) : 1;
        $code = inject_check(isset($data['code']) ? $data['code'] : '');
        if ($userid == 0 || $type > 1) {
        	set_return_value(RESULT_SUCCESS, $dataval);
        	return false;
        }
        if(empty($code)){
        	set_return_value(RESULT_SUCCESS, $dataval);
        	return false;
        }
        $auth_token = $this->getWxChatAuthToken($code);
        if($auth_token && isset($auth_token["openid"])){
        	$dataval["openid"] = $auth_token["openid"];
        	$model = get_load_model('customer');
        	if($mold > 1){
        		$model->ModelUpdateOpenId($userid,$auth_token["openid"]);
        	}else{
        		$model->ModelUpdateXcxOpenId($userid,$auth_token["openid"]);
        	}
        }
        set_return_value(RESULT_SUCCESS, $dataval);
    }

    private function getWxChatAuthToken($code) {
    	if(empty($code)){
    		return false;
    	}
    	$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&appid='
    	. WCHAT_APPID . '&secret=' . WCHAT_APPSECRET . '&code=' .$code;
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$result = curl_exec($ch);
    	curl_close ($ch);
    	$access = json_decode ($result,true);
    	return $access;
    }

    public function getWxAccessToken(){
    	get_load_libraries('cache.class');
    	$c = new cache();
    	$info = $c->get("AccessToken");
    	if(!empty($info)){
    		return $info;
    	}else{
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
    		if(!empty($access) && isset($access["access_token"])){
    			$c->set("AccessToken",$access['access_token'],7200);
    			return $access['access_token'];
    		}else{
    			return '';
    		}
    	}
    }

    public function wxSetSend($touser, $template_id, $url, $data, $topcolor = '#7B68EE'){
    	$template = array(
    		'touser' => $touser,
    		'template_id' => $template_id,
    		'url' => $url,
    		'topcolor' => $topcolor,
    		'data' => $data
    	);
    	$jsonData = json_encode($template);
    	$wxAccessToken = $this->getWxAccessToken();
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

    public function wxGetOpenId($data){
    	$dataval = array();
    	$dataval["openid"] = 0;
        $userid = isset($data['userid']) ? intval($data['userid']) : 0; //用户id
        $type = isset($data['type']) ? intval($data['type']) : 0;    //用户的类型
        $code = inject_check(isset($data['code']) ? $data['code'] : '');
        if ($userid == 0 || $type > 1) {
        	set_return_value(RESULT_SUCCESS, $dataval);
        	return false;
        }
        if(empty($code)){
        	set_return_value(RESULT_SUCCESS, $dataval);
        	return false;
        }
        $auth_token = $this->getWxChatOpenId($code);
        if($auth_token && isset($auth_token["openid"])){
        	$dataval["openid"] = $auth_token["openid"];
        	$model = get_load_model('customer');
        	$model->ModelUpdateXcxOpenId($userid,$auth_token["openid"]);
        }
        set_return_value(RESULT_SUCCESS, $dataval);
    }

    public function getWxChatOpenId($code){
    	if(empty($code)){
    		return false;
    	}
    	$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.wx_WCHAT_APPID.'&secret='.wx_WCHAT_APPSECRET.'&js_code='.$code.'&grant_type=authorization_code';
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$result = curl_exec($ch);
    	curl_close ($ch);
    	$access = json_decode ($result,true);
    	return $access;
    }

    //微信分享
    public function wxChatShare($data){

    	$dataval = array();
    	$shareuser = $data['shareUid'] ? intval($data['shareUid']) : '';
    	$getuser = isset($data['userid']) ? intval($data['userid']) : '';
    	echo $getuser;die;
    	$type = isset($data['type']) ? intval($data['type']) : '';
    	if ($getuser <= 0 || $type > 1) {

    		set_return_value(RESULT_SUCCESS, $dataval);
    		return false;
    	}
    	if(!$shareuser || !$getuser){

    		set_return_value(RESULT_SUCCESS, $dataval);
    		return false;
    	}

    	$model = get_load_model('WxMessage');
    	$shareInfo = $model->getShareByUser($shareuser, $getuser);
    	if($shareInfo){
    		set_return_value(RESULT_SUCCESS, $dataval);
    		return false;
    	}
    	$res = $model->addShare($shareuser, $getuser);
    	set_return_value(RESULT_SUCCESS, $dataval);
    }
}