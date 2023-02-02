<?php
require_once __DIR__ . '/../config.php';

//默认参数
$defaultNamespace = 'admin\\'; //可为空
$defaultController = 'index';
$defaultAction = 'index';

if (!isset($argv)) { //链接访问
    $controller =  ucwords(isset($_GET['c']) ? trim($_GET['c']) : $defaultController);
    $action =  isset($_GET['a']) ? trim($_GET['a']) : $defaultAction;
} else { //命令行访问
    //解析命令行的controller与action
    $controller = ucwords($defaultController);
    $action = $defaultAction;
    if (isset($argv[1])) {
        $ca = explode('/', $argv[1]);
        if (count($ca) == 2) {
            $controller = ucwords($ca[0]);
            $action = $ca[1];
        }
    }
    //获取命令行的参数将命令行的信息传递给$_GET
    $param = array();
    foreach ($argv as $k => $v) {
        if ($k == 0) continue;
        $exv = explode('=', $v);
        if (isset($exv[0]) && isset($exv[1]) && count($exv) == 2) {
            $_GET[$exv[0]] = $exv[1];
        }
    }
}
$className = '\\Controller\\' . ucwords($defaultNamespace) . $controller;
$newController = new $className();
$newController->$action();
