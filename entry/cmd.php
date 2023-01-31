<?php
require_once __DIR__ . '/../config.php';

//只允许在命令行下运行,运行方式php cmd.php controller/action id=123 xxx=123
if (!isset($argv)) exit('');

//默认参数
$defaultNamespace = 'Cmd\\'; //可为空
$defaultController = 'index';
$defaultAction = 'index';

//解析命令行的controller与action
$controller = ucwords($defaultController);
$action = ucwords($defaultAction);
if (isset($argv[1])) {
    $ca = explode('/', $argv[1]);
    if (count($ca) == 2) {
        $controller = ucwords($ca[0]);
        $action = ucwords($ca[1]);
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

$className = '\\Controller\\' . $defaultNamespace . $controller;
$newController = new $className();
$newController->$action();
