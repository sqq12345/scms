<?php
/*数据库连接*/
define ('DB_DSN','mysql:host=localhost;port=3306;dbname=test');
define ('DB_USER','root');
define ('DB_PASSWD','root');


//log日志
define('LOG_ADDRESS', './data/logs/'); //log日志地址
define('LOG_SWITCH',1); //1打开log日志

//mysql
define('ECHO_AQL_SWITCH',0); //1显示sql

//用户密码加密
define('PASSWORD_MD5','purchase');

//配送费用
define('DISTRIBUTION',6);

//默认赠送会员天数
define('VIP_DAYS',90);

//设置用户月限购金额
define('LIMIT_AMOUNT',20000);

//七牛
define('ACCESS_KEY','M8b5FG47VTVcsH-Bp-mwGQHdK6JhoByYuJyCb0KI');
define('SECRET_KEY','NVSlUSxEd-uXwsGCdAGjF1L4XXGumbsQdRwJ1z4w');
define('QINIU_HTTP','http://upload.hljr.com.cn/');

//微信
define("WCHAT_APPID", 'wx7fc6fef24ddffde6');
define("WCHAT_APPSECRET", '34888890e5062883065884fc738de9d1');

//微信小程序
define("wx_WCHAT_APPID", 'wxe9af8c5281481a90');
define("wx_WCHAT_APPSECRET", '0dc1c01b62aa06d37a684a318d93ec3d');

//微信通知模板
define('WX_MESSAGE_MODEL_1','3gCsqQ0JJTE-atoyw8s-UOxju-thFgNVGxwHPjv_ic4');  //订单状态更新
define('WX_MESSAGE_MODEL_2','7ohcgK2WNaJIFLDMonEIABofb8PNQROnSH6dklcaAhc');  //退款成功通知
define('WX_MESSAGE_MODEL_3','7tiVUZCKImuzkeGF-k3T0MXi8XhaPk3_xBPI25O_Ygc');  //购买失败通知
define('WX_MESSAGE_MODEL_4','8KOxMN1r4DvGB9E9bfYFbQmYCSKUafE3L3jx91g8oR0');  //订单支付成功
define('WX_MESSAGE_MODEL_5','BllhmPzsCqiayd_rUa1yqJQ06FdlY5VKbu6iXWxoL3k');  //成为会员通知
define('WX_MESSAGE_MODEL_6','KY3UGR6Q3RDUaulXIJrwUxcM56ch7sdLCOCZGf8ern4');  //会员充值通知
define('WX_MESSAGE_MODEL_7','VKbXMy4-HBsayvQiET-RDxdjGlRTgjyFlLNO0bO5tKQ');  //商品已发出通知
define('WX_MESSAGE_MODEL_8','X1-LahhhFz2YWq7va8XCOSo7PdNxqH9Chy7Wg7z2Y_w');  //订单未支付通知
define('WX_MESSAGE_MODEL_9','Y5mZwhlWOrOO9MiFgn5AkPEWmKIH_p6M00LZFcV1Z4k');  //退款申请通知
define('WX_MESSAGE_MODEL_10','fw7358KJ42FD2BXZ07SS78rXZmJQd3Ve1SaqObw0vK4'); //会员到期提醒
?>