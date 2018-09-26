<?php
/**
 * Created by PhpStorm.
 * 一天运行一次
 * User: huiyong.yu
 * Date: 2018/6/13
 * Time: 14:05
 */
require_once (__DIR__.'/config.php');

$pdo = mydqlpdo();
$sql = "UPDATE limit_validate SET times = 0 ";
$ret = $pdo->exec($sql);
$pdo = null;
exit;