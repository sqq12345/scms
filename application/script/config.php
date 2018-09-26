<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/4/23
 * Time: 13:02
 */

define ('DB_DSN','mysql:host=119.3.28.226;port=3306;dbname=purchase');
define ('DB_USER','purchase');
define ('DB_PASSWD','zPMBiMbFxpmRcE5r');

define("WCHAT_APPID", 'wx7fc6fef24ddffde6');
define("WCHAT_APPSECRET", '34888890e5062883065884fc738de9d1');

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