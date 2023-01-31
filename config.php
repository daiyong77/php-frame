<?php
require_once __DIR__ . '/vendor/autoload.php';

header('Content-type:text/html;charset=utf-8'); //设置编码
date_default_timezone_set('PRC'); //设置时区
ini_set('display_errors', 1); //是否显示错误
ini_set('error_reporting', E_ALL); //错误级别控制
// error_reporting(0); //如果对服务器没有权限控制则使用这一条直接关闭错误

$CONFIG = array(
    'db' => array(
        'connect' => 'mysql:host=127.0.0.1;dbname=test',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8'
    )
);
