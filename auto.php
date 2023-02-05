<?php
/*
 * @Author: daiyong 1031850847@qq.com
 * @Date: 2023-02-03 14:33:57
 * @LastEditors: daiyong
 * @LastEditTime: 2023-02-05 16:24:29
 * @Description: 自动生成代码
 */
require_once __DIR__ . '/config.php';

use \Daiyong\File as file;
use \Daiyong\Db as db;

//只允许在命令行下运行,运行方式php auto.php namespace/controller model/table
if (!isset($argv)) exit('');

db::connect($CONFIG['db']);

//解析cmd传入的参数
$nc = explode('/', @$argv[1]);
$mt = explode('/', @$argv[2]);
if (!isset($nc[0]) || !isset($nc[1]) || !isset($mt[0]) || !isset($mt[1])) {
    error('自动生成代码运行格式为:php auto.php namespace/controller model/table');
}
$namespace = lcfirst($nc[0]);
$controller = lcfirst($nc[1]);
$model = lcfirst($mt[0]);
$table = lcfirst($mt[1]);

//数据库字段解析
preg_match('/=(\w+)$/', $CONFIG['db']['connect'], $match);
$tableInfo = db::findAll('information_schema.COLUMNS|COLUMN_KEY,COLUMN_NAME,IS_NULLABLE,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH,COLUMN_COMMENT', array(
    'TABLE_SCHEMA' => $match[1],
    'TABLE_NAME' => $table
));
$rule = array();
$search = array();
$edit = array();
foreach ($tableInfo as $v) {
    if ($v['COLUMN_NAME'] == 'id') continue;
    //rule
    if (!$v['COLUMN_COMMENT']) $v['COLUMN_COMMENT'] = $v['COLUMN_NAME'];
    $must = $v['IS_NULLABLE'] == 'NO' ? '|must' : ''; //新增时不能为空判断
    $ruleChild = [];
    if ($v['DATA_TYPE'] == 'varchar' && (int)$v['CHARACTER_MAXIMUM_LENGTH'] <= 32) { //字符判断
        $ruleChild[] = "array('string', '" . $v['COLUMN_COMMENT'] . "必须在1~20个字符之间', 1, 20)";
    } elseif ($v['DATA_TYPE'] == 'int') {
        $ruleChild[] = "array('int', '请输入正确的" . $v['COLUMN_COMMENT'] . "')";
    } elseif ($v['DATA_TYPE'] == 'datetime') {
        $ruleChild[] = "array('time', '请输入正确的" . $v['COLUMN_COMMENT'] . "')";
    } elseif ($v['DATA_TYPE'] == 'date') {
        $ruleChild[] = "array('date', '请输入正确的" . $v['COLUMN_COMMENT'] . "')";
    } else {
        $ruleChild[] = "array('none', '请填写" . $v['COLUMN_COMMENT'] . "')";
    }
    if ($v['COLUMN_KEY'] == 'UNI') { //唯一值判断
        $ruleChild[] = "array('only', '已存在相同的" . $v['COLUMN_COMMENT'] . "', '" . $table . "')";
    }
    if (count($ruleChild) > 1) {
        $rule[] = "'" . $v['COLUMN_NAME'] . $must . "' => array(" . PHP_EOL . "                " . implode(',' . PHP_EOL . '                ', $ruleChild) . PHP_EOL . "            )";
    } else {
        $rule[] = "'" . $v['COLUMN_NAME'] . $must . "' => " . implode(',', $ruleChild);
    }
    //search
    if ($v['DATA_TYPE'] == 'int') {
        $search[] = "'" . $v['COLUMN_NAME'] . "' => (int)\$_GET['" . $v['COLUMN_NAME'] . "']";
    } elseif ($v['DATA_TYPE'] == 'date') {
        $search[] = "'" . $v['COLUMN_NAME'] . "|>=' => \$_GET['" . $v['COLUMN_NAME'] . "_begin']";
        $search[] = "'" . $v['COLUMN_NAME'] . "|<=' => \$_GET['" . $v['COLUMN_NAME'] . "_end']";
    } elseif ($v['DATA_TYPE'] == 'varchar' && (int)$v['CHARACTER_MAXIMUM_LENGTH'] <= 255) { //代表可搜索
        $search[] = "'" . $v['COLUMN_NAME'] . "|like' => '%' . \$_GET['" . $v['COLUMN_NAME'] . "'] . '%'";
    }
    //edit
    if ($v['DATA_TYPE'] == 'int') {
        $edit[] = "'" . $v['COLUMN_NAME'] . "' => (int)\$_POST['" . $v['COLUMN_NAME'] . "']";
    } else {
        $edit[] = "'" . $v['COLUMN_NAME'] . "' => \$_POST['" . $v['COLUMN_NAME'] . "']";
    }
}

$ruleTxt = implode(',' . PHP_EOL . '            ', $rule);
$searchTxt = implode(',' . PHP_EOL . '            ', $search);
$editTxt = implode(',' . PHP_EOL . '            ', $edit);

//获取模板
$controllerTpl = file::get('data/autoCode/controller.php.tpl');
$modelTpl = file::get('data/autoCode/model.php.tpl');

//字段替换
$controllerTpl = str_replace(array(
    '{{Namespace}}', '{{namespace}}', '{{Controller}}', '{{controller}}', '{{Model}}', '{{model}}',
    '{{search}}', '{{edit}}'
), array(
    ucwords($namespace), $namespace, ucwords($controller), $controller, ucwords($model), $model,
    $searchTxt, $editTxt
), $controllerTpl);
$modelTpl = str_replace('{{rule}}', $ruleTxt, $modelTpl);

$modelTpl = str_replace(array(
    '{{Model}}', '{{model}}', '{{Table}}', '{{table}}', '{{rule}}'
), array(
    ucwords($model), $model, ucwords($table), $table, $ruleTxt
), $modelTpl);

//文件生成
$controllerFile = 'code/controller/' . ucwords($namespace) . '/' . ucwords($controller) . '.php';
$modelFile = 'code/model/' . ucwords($model) . '.php';

if (!file_exists(file::path($controllerFile)) || @$argv[3] == 'delete') {
    file::put($controllerFile, $controllerTpl);
    tip('创建成功:' . $controllerFile);
} else {
    tip('!!!创建失败,文件已存在(如需覆盖请在命令后加上delete):' . $controllerFile);
}
if (!file_exists(file::path($modelFile)) || @$argv[3] == 'delete') {
    file::put($modelFile, $modelTpl);
    tip('创建成功:' . $modelFile);
} else {
    tip('!!!创建失败,文件已存在(如需覆盖请在命令后加上delete):' . $modelFile);
}

function error($message) {
    echo $message;
    exit;
}
function tip($message) {
    echo $message . PHP_EOL;
}
