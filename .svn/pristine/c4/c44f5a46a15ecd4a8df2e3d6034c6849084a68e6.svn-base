<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(1);
require_once('config/configure.php');
require_once ('libraries/mysqlpdo.class.php');
require_once ('libraries/sms.php');
require_once ('libraries/smtps.php');
require_once ('libraries/qiniu/autoload.php');
require_once('function.php');
require_once('config/common.php');
$GLOBALS['DB'] = new mysqldbo(DB_DSN, DB_USER, DB_PASSWD);
$GLOBALS['MESSAGE'] = $_MESSAGE;
?>