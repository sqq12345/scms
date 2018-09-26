<?php
/**
 * Created by PhpStorm.
 * 一个月运行一次
 * User: huiyong.yu
 * Date: 2018/6/11
 * Time: 9:52
 */
require_once (__DIR__.'/config.php');

$pdo = mydqlpdo();
$sql = "UPDATE limit_amount SET amount = 0 ";
$ret = $pdo->exec($sql);
$pdo = null;
exit;