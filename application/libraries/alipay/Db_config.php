<?php
/**
 * Created by PhpStorm.
 * User: huiyong.yu
 * Date: 2018/5/21
 * Time: 10:19
 */

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