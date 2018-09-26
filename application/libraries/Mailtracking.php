<?php

/**
 * Mailtracking file.
 *
 * @author huiyong.yu
 * @copyright Copyright &copy; 2018-2018 时差买手
 * @version 1.0
 */

class Mailtracking{

	public $version = '1.0';
	public $curl = null;
	
	public $mailId = '';
	public $mailType = '';
	
	public $perUrl = '';
	public $doUrl = '';
	
	public $curlInfo = array();
	public $curlHtml = '';
	
	public function __construct(){
		$this->curl = curl_init(); // 启动一个CURL会话           
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
		curl_setopt($this->curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
		//curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
		curl_setopt($this->curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Post请求
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie'); // 存放Cookie信息的文件名称
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie'); // 读取上面所储存的Cookie信息
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
		curl_setopt($this->curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		
		$this->init();
	}
	
	public function init(){
		curl_setopt($this->curl, CURLOPT_REFERER, "http://www.kuaidi100.com/");
	}
	
	public function __destruct(){
		curl_close($this->curl); // 关键CURL会话
	}
	
	public function creatUrl(){
		$this->perUrl = '';
		$this->doUrl = 'http://www.kuaidi100.com/query?type='
						. $this->mailType 
						. '&postid=' 
						. $this->mailId 
						. '&id=1&valicode=&temp=' 
						. ( 1/time() );
	}
	
	public function checkMailType(){
		$this->mailType = 'ems';
	}
	
	public function setMailId( $id, $mailTpye = ''){
		if($mailTpye == ''){
			$this->checkMailType($id);
		}else{
			$this->mailType = $mailTpye;
		}
		$this->mailId = $id;
		$this->creatUrl();
	}
	
	public function loadCheckcode(){
		curl_setopt($this->curl, CURLOPT_URL, $this->perUrl); // 要访问的地址
		$this->curlHtml = curl_exec($this->curl); // 执行操作
		if (curl_errno($this->curl)) {
		   $this->curlInfo['err'] = 1;	
		   $this->curlInfo['info'] = '操作超时!';
		}
	}
	
	public function curlPost(){
		curl_setopt($this->curl, CURLOPT_URL, $this->doUrl); // 要访问的地址
		$this->curlHtml = curl_exec($this->curl); // 执行操作
		if (curl_errno($this->curl)) {
		   $this->curlInfo['err'] = 1;	
		   $this->curlInfo['info'] = '操作超时!';
		}
	}

	public function getMailInfo(){
		if( isset($this->curlInfo['err']) && $this->curlInfo['err'] ){
			return $this->curlInfo['info'];
		}else{
			return $this->curlHtml;
		}
		
	}
}