<?php

/**
 * 接口输出值得类型
 *
 * @param mixed $var
 */
function json_var($var) {
    $var_to = json_encode($var);

    echo $var_to;
    $GLOBALS['DB']->close();
}

/**
 *保存输出值信息
 *
 * @param mixed $response
 * @param mixed $awardID
 * @param mixed $date
 */
function set_return_value($response, $data, $total = 0, $isdata = 'data') {
    $GLOBALS['ReturnValues']['message'] = $GLOBALS['MESSAGE'][$response];
    $GLOBALS['ReturnValues']['code'] = $response;
    if($total>0){
        $GLOBALS['ReturnValues']['total'] = $total;
    }
    isset($data[0])||$isdata=='datalist' ? $GLOBALS['ReturnValues']['datalist'] = $data : $GLOBALS['ReturnValues']['data'] = $data;

}

function set_return_value_ref($response, $data, $ref = '', $isdata = 'data') {
    $GLOBALS['ReturnValues']['message'] = $ref.'--'.$GLOBALS['MESSAGE'][$response];
    $GLOBALS['ReturnValues']['code'] = $response;
    isset($data[0])||$isdata=='datalist' ? $GLOBALS['ReturnValues']['datalist'] = $data : $GLOBALS['ReturnValues']['data'] = $data;

}

/**
 * log日志
 *
 * @param mixed $drid
 * @param mixed $val
 */
function get_cpalog($url, $returnval) {
    if (LOG_SWITCH === 1) {
        $body = "加密值:" . $url . "返回值:" . $returnval . "-" . date('Y-m-d H:i:s') . "\n";
        error_log($body . "\n", 3, LOG_ADDRESS . date('Ymd') . ".log");
    }

}

/**
 * 函数作用：检测提交的值是不是含有SQL注射的字符，防止注射，保护服务器安全
 *
 * @param mixed $sql_str
 * @return int
 */
function inject_check($sql_str) {
    $str = strtr($sql_str, array("select" => "", "insert" => "", "update" => "", "delete" => "", "|" => "", "'" => "", "union" => "", "into" => "", "load_file" => "", "outfile" => "", "*" => "", "<" => "", ">" => ""));

    return $str;
}

/***
 */
function get_input($key, $data, $defualt = null, $pretreatment = "") {
    $_return = $defualt;
    if(is_array($data) && isset($data[$key])){
        if(in_array($pretreatment, array(
            'intval',
            'floatval',
            'inject_check',
            'htmlspecialchars',
            'trim',
        ))){
            $_return = $pretreatment($data[$key]);
        } else {
            $_return =  $data[$key];
        }
    }
    return $_return;

}

/**
 * 获取手机ip
 *
 */
function get_ip() {
    $onlineip = '';
    if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];

    }

    return $onlineip;
}

/**
 * post提交数据
 *
 */
function curl_post($url, $post) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $post,
    );

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

/**
 * 判断接口时间是否过时
 *
 * @param mixed $date
 */
function is_date_outmoded($date) {
    if (TIME_SWITCH != 1) {
        return true;
    }
    $time = time();
    if (empty($date)) {
        return false;
    } else {
        $unctime = $time - strtotime($date);
        if ($unctime > -TIME_DIFFERENCE && $unctime <= TIME_DIFFERENCE) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * url请求数据处理
 *
 */
function url_request() {

    $post_data = isset($_POST['data']) ? $_POST['data'] : '';
    $post_token = isset($_POST['token']) ? $_POST['token'] : '';

    if (empty($post_data)) {

        set_return_value(DEFEATED_ERROR, '');
        get_cpalog($post_data . '-', json_encode($GLOBALS['ReturnValues']));
        json_var($GLOBALS['ReturnValues']);
        exit;
    }

    $data = array();
    $data = json_decode($post_data, true);
    $_NEED_CHECK = array(
        'cart_getList'
    );
    $act = $data['act'];	
    if(empty($post_token)){

        if(in_array($act, $_NEED_CHECK)) {

            set_return_value(USER_LOGIN_OUT, '');
            json_var($GLOBALS['ReturnValues']);
            exit;
        } else {

            $data['userid'] = 0;
            return $data;
        }

    } else{

        $tokenmodel = get_load_model('token');
        $tokenrow = $tokenmodel->ModelInfoByToken($post_token);
        if(empty($tokenrow)) {
            if(in_array($act,array('cart_getList'))){
                set_return_value(USER_LOGIN_OUT, '');
            }
            else{
                set_return_value(USER_TOKEN_ERROR, '');
            }
            json_var($GLOBALS['ReturnValues']);
            exit;
        }else {
            $data['userid'] = $tokenrow['userId'];
            $data['type'] = $tokenrow['type'];
            $data['token'] = $post_token;
            return $data;
        }
    }

}


/**
 * 调用model类
 *
 * @param mixed $classname
 */
function get_load_model($classname,$groupname=''){
    $classname = ucfirst($classname).'Model'; 
    require_once('models/'.$groupname.'/'.$classname.'.php');
    $newclass = new $classname();

    return $newclass;
}

/**
 * 调用libraries类
 *
 * @param mixed $classname
 */
function get_load_libraries($classname) {
    require_once('libraries/' . $classname . '.php');

    return true;
}
/**
 * 调用controllers
 *
 * @param $classname
 * @return mixed
 */
function get_load_controllers($classname){
    if(!empty($_FILENAMES[$classname])){
        $classname = $_FILENAMES[$classname];
    }else{
        $classname = ucfirst($classname);
    }
    require_once('controllers/'.$classname.'.php');
    $newclass = new $classname();
    return $newclass;
}

function get_load_controller($classname,$groupname=''){
    $classname = ucfirst($classname);
    require_once('controllers/'.$groupname.'/'.$classname.'.php');
    $newclass = new $classname();

    return $newclass;
}

/**
 * 调用util类
 *
 * @param mixed $classname
 */
function get_load_util($classname) {
    $classname = ucfirst($classname) . 'Util';
    require_once('util/' . $classname . '.class.php');
    $newclass = new $classname();

    return $newclass;
}


/**
 * 解密
 * 先用key2, 再用key1解密
 */
function ful_decode($str, $key1, $key2) {
    $c2 = new DES($key2);
    $s2 = $c2->decrypt($str);
    $c1 = new DES($key1);

    return $c1->decrypt($s2);
}

/**
 *  加密
 *  先用key1, 再用key2加密
 */

function ful_encode($str, $key1, $key2) {
    $c1 = new DES($key1);
    $s1 = $c1->encrypt($str);
    $c2 = new DES($key2);

    return $c2->encrypt($s1);
}

/**
 * 调用短信和上传图片
 *
 * @param mixed $str
 */
function get_service($str) {
    $coredir = CPRE_DIR;
    $filename = $coredir . '/' . $str . '.class.php';
    if (file_exists($filename)) {
        require_once $filename;
        $classname = "\\YCore\\" . $str;

        return new $classname();
    }

    return null;
}

/**
 * 用户redis调用
 *
 * @param mixed $token
 * @param mixed $userid
 * @param mixed $type
 */
function set_redis($token, $userid = 0, $type = 0) {
    get_load_libraries('redis.class');
    $redis = new redis_class();
    $redis->connect(REDIS_HOST, REDIS_PORT);
    if ($type == 1) {
        $redis->setValueToHashTable('USER_TOKEN:', $token, $userid);
    } elseif ($type == 2) {
        $redis->removeHashTableField('USER_TOKEN:', $token);
    } else {
        if ($redis->getValueFromHashTable('USER_TOKEN:', $token)) {
            $userid = $redis->getValueFromHashTable('USER_TOKEN:', $token);
        } else {
            $userid = 0;
        }

        return $userid;
    }
    
}

/**
 * 徽章redis调用
 *
 * @param int $userid
 * @param string $badge
 * @param int $val
 * @param int $type
 * @return int
 */
function set_redis_badge($userid = 0, $badge = 'coupon', $val = 0,  $type = 0){
    get_load_libraries('redis.class');
    $redis = new redis_class();
    $redis->connect(REDIS_HOST, REDIS_PORT);

    if ($type == 1) {
        $redis->setValueToHashTable("BADGE", $badge.'_'.$userid, $val);
    } elseif ($type == 2) {
        $redis->removeHashTableField("BADGE", $badge.'_'.$userid);
    } else {
        if ($redis->getValueFromHashTable("BADGE", $badge.'_'.$userid)) {
            $val = $redis->getValueFromHashTable("BADGE", $badge.'_'.$userid);
        } else {
            $val = 0;
        }
        return $val;
    }
}

/**
 * 记录每个接口的详情的日记
 *
 * @param mixed $str
 */
function set_api_log($data) {
    $date = date('Ymd');
    $table = 't_login_log_' . $date;
    $setarray[] = "f_userid = {$data['userid']}";
    $setarray[] = "f_act = '{$data['act']}'";
    if (isset($data['deviceToken'])) {
        $setarray[] = "f_device_token = '{$data['deviceToken']}'";
    }
    $GLOBALS['DB']->setInsert(ECHO_AQL_SWITCH, 1, $table, $setarray);
}

/**
 * 分享图片格式化
 *
 * @param $url
 * @param $type
 */
function share_oss_img($url, $type = '120w_120h_2e') {
    $_urls = (explode("@", $url));

    return $_urls[0] . '@' . $type;
}

/**
 * php中json_encode UTF-8中文乱码的解决方法
 *
 * @param $str
 * @return string
 */
function encode_json($str) {
    return urldecode(json_encode(url_encode($str)));
}

function url_encode($str) {
    if (is_array($str)) {
        foreach ($str as $key => $value) {
            $str[urlencode($key)] = url_encode($value);
        }
    } else {
        $str = urlencode($str);
    }

    return $str;
}


function getPostContent($content, $html = 0) {
    if ($html == 0) {
        if (preg_match('/\<div\>\<style\>img\{width:100%;height:auto;\}\<\/style\>/s', $content)) {
            $len = mb_strlen($content, 'utf-8');

            // per 48; end 6;
            return mb_substr($content, 48, $len - 48 - 6, 'utf-8');
        } else {
            return $content;
        }
    } else {
        if (preg_match('/\<div\>\<style\>img\{width:100%;height:auto;\}\<\/style\>/s', $content)) {
            return $content;
        } else {
            return "<div><style>img{width:100%;height:auto;}</style>" . $content . "</div>";
        }
    }
}

function getEnum($enum, $str) {
    $enum = strtoupper('enum_' . $enum);
    if (isset($GLOBALS['ENUMS'][$enum])) {
        return isset($GLOBALS['ENUMS'][$enum][$str]) ? $GLOBALS['ENUMS'][$enum][$str] : $str;
    } else {
        return $str;
    }
}
function getEnumConfig($enum, $type=0) {
    $enum = strtoupper('enum_' . $enum);
    if($type==0) {
     return  array_keys($GLOBALS['ENUMS'][$enum]);
 } else {
     return $GLOBALS['ENUMS'][$enum];
 }
}
function getEnumKey($enum, $value=""){
    $enum = strtoupper('enum_' . $enum);
    $_return = "";
    foreach($GLOBALS['ENUMS'][$enum] as $key=>$val){
        if($val == $value){
            $_return = $key;
        }
    }
    return $_return;
}

function model_inputcheck($role, $data) {

}

/*
 * 将从数据库中取出的数据已驼峰取出
 * */
function model_toobj($data) {
    if (!is_array($data)) {
        if($data === false){
            $data = null;
        }
        return $data;
    }
    $_return = array();
    foreach ($data as $key => $val) {
        if (is_numeric($key)) {
            continue;
        }
        $str = str_replace('f_', '', $key);
        $str = ucwords(str_replace('_', ' ', $str));
        $str = str_replace(' ', '', lcfirst($str));
        $jsonval = !is_numeric($val) && is_string($val) ? json_decode($val, true) : false;
        $_return[$str] = $jsonval ? $jsonval : $val;
    }
    return $_return;
}

function model_filte($data, $filte) {
    foreach($data as $key=>$val) {
        if(!in_array($key, $filte)) {
            unset($data[$key]);
        }
    }
    return $data;
}

function model_default($data, $key, $default = null) {
    if(isset($data[$key]) && empty($data[$key])) {
        $data[$key] = $default;
    }
    return $data;
}


/*
 * 将从数据库中取出的数据已驼峰取出
 * */
function model_toobjlist($data) {
    if (!is_array($data)) {
        return $data;
    }
    $_return = array();
    foreach ($data as $val) {
        $_return[] = model_toobj($val);
    }

    return $_return;
}

function model_setDefault($data, $default){
    foreach($default as $key => $val) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $data[$key] = $val;
        }
    }
    return $data;
}
function model_createWhere($where , $map){
    $wheresql = "";
    if(is_array($where)){
        foreach($where as $key=>$val){
            $sqlkey = array_key_exists($key,$map)?$map[$key]:$key;
            if(!is_array($val)) {
                $wheresql .= " and `{$sqlkey}` = '$val'";
            } else {
                if(isset($val["in"]) && is_array($val["in"])){
                    $wheresql .= " and `{$sqlkey}` in ('".implode("','",$val['in'])."')";
                } elseif(isset($val["like"])){
                    $wheresql .= " and `{$sqlkey}` like '%".$val['like']."%' ";
                } elseif(isset($val["between"]) && isset($val['like'][0]) && isset($val['like'][1])){
                    $wheresql .= " and `{$sqlkey}` between '".$val['like'][0]."' and '".$val['like'][1]."' ";
                } elseif(isset($val["sql"])){
                    $wheresql .= $val["sql"];
                }
            }
        }
    } else {
        $wheresql .= $where;
    }
    return $wheresql;
}

function get_brower() {
    $agent = strtolower($_SERVER["HTTP_USER_AGENT"]);
    if (strpos($agent, 'yipaiseller') !== false)
        return "yipaiseller";
    else if (strpos($agent, 'yipai') !== false)
        return "yipai";
    else if (strpos($agent, 'msie') !== false || strpos($agent, 'rv:11.0')) //ie11判断
    return "ie";
    else if (strpos($agent, 'firefox') !== false)
        return "firefox";
    else if (strpos($agent, 'chrome') !== false)
        return "chrome";
    else if (strpos($agent, 'opera') !== false)
        return 'opera';
    else if ((strpos($agent, 'chrome') == false) && strpos($agent, 'safari') !== false)
        return 'safari';
    else
        return 'unknown';
}


function get_adminid($token) {
    if (empty($token)) {
        return 0;
    }
    $admin_token = json_decode($GLOBALS['REDIS']->getValueFromHashTable("AdminToken", $token), true);

    if (empty($admin_token)) {
        return 0;
    }
    if ($admin_token['time']<time()) {
        $GLOBALS['REDIS']->removeHashTableField("AdminToken", $token);
        return 0;
    }
    $adminid = $admin_token['id'];
    $admin_token['time'] = time() + 60*60*2;
    $GLOBALS['REDIS']->setValueToHashTable("AdminToken", $token, json_encode($admin_token));
    return $adminid;
}

function amount_str($amount, $symbol = 1) {
    if($symbol == 1) {
        return sprintf("%.2f",$amount);
    } else {
        return sprintf("%.2f",0-$amount);
    }
}

function iptocityByQQ($ip){
    $unlownstr = "未知";
    $loc = json_decode($GLOBALS['REDIS']->getValueFromHashTable("iptocity", $ip), true);
    if(empty($loc) || ($loc == $unlownstr) && rand(1,100)==100){
        $url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$ip;
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        $result = curl_exec($ch);
        $result = mb_convert_encoding($result, "utf-8", "gb2312"); // 编码转换，否则乱码
        curl_close($ch);
        preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);
        $loc = $ipArray[1];
        if(!empty($loc)){
            $loc = $unlownstr;
        }
    }
    $GLOBALS['REDIS']->setValueToHashTable("iptocity", $ip, $loc);
    return $loc;
}
function set_redis_integral($hashName = '', $key = '', $val = 0,  $type = 0){
    get_load_libraries('redis.class');
    $redis = new redis_class();
    $redis->connect(REDIS_HOST, REDIS_PORT);
    if ($type == 1) {
        $redis->setValueToHashTable($hashName, $key, $val);
    } elseif ($type == 2) {
        $redis->removeHashTableField($hashName, $key);
    } else {
        if ($redis->getValueFromHashTable($hashName, $key)) {
            $val = $redis->getValueFromHashTable($hashName, $key);
        } else {
            $val = 0;
        }
        return $val;
    }
}

function getTheWeek(){
    date_default_timezone_set('PRC');
    $nowtime = date("Y-m-d");
    $lastday=date('Y-m-d 23:59:59',strtotime("$nowtime Sunday"));
    $firstday=date('Y-m-d 00:00:00',strtotime("$lastday -6 days"));
    return array($firstday,$lastday);
}

function getTheMonth(){
    date_default_timezone_set('PRC');
    $nowtime = date("Y-m-d");
    $firstday = date('Y-m-01 00:00:00', strtotime($nowtime));
    $lastday = date('Y-m-d 23:59:59', strtotime("$firstday +1 month -1 day"));
    return array($firstday,$lastday);
}

function getTheSeason(){
    $season = ceil((date('n'))/3);//当月是第几季度
    $firstday = date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y')));
    $lastday = date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y')));
    return array($firstday,$lastday);
}

function getTheYear(){
    date_default_timezone_set('PRC');
    $date = date("Y-m-d");
    $firstday = date('Y-01-01 00:00:00', strtotime($date));
    $lastday = date('Y-12-31 23:59:59', strtotime($date));
    return array($firstday,$lastday);
}

//CURL抓网页内容
function CURL($url = ''){
    if(!$url){
        return null;
    }
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 2);
    curl_setopt($ch,CURLOPT_HEADER,0);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
