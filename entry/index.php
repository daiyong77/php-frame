<?php
require_once __DIR__ . '/../config.php';

//默认参数
$defaultNamespace = 'Admin\\'; //可为空
$defaultController = 'index';
$defaultAction = 'index';

$controller =  ucwords(isset($_GET['c']) ? trim($_GET['c']) : $defaultController);
$action =  ucwords(isset($_GET['a']) ? trim($_GET['a']) : $defaultAction);

$className = '\\Controller\\' . $defaultNamespace . $controller;
$newController = new $className();
$newController->$action();
