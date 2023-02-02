<?php

namespace Controller;

use \Daiyong\Db as db;

class Common
{
    public $config = array();
    public function __construct()
    {
        global $CONFIG;
        //全局配置赋值
        $this->config = $CONFIG;
        //将get参数全部trim
        foreach ($_GET as $k => $v) {
            $_GET[$k] = trim($v);
        }
        //连接数据库
        db::connect($this->config['db']);
    }

    /**
     * @description: 输出返回
     * @param {返回状态} $status
     * @param {如果成功则输出该提示} $message1
     * @param {如果失败则输出该提示} $message2
     * @return {结束后退出}
     */
    public function return($status, $message1 = '成功', $message2 = '失败')
    {
        if ($status) {
            $this->success($message1);
        } else {
            $this->error($message2);
        }
    }
    public function success($data = array(), $message = '成功')
    {
        if (!is_array($data)) {
            $message = $data;
            $data = array();
        }
        $this->echo(array(
            'status' => 1,
            'message' => $message,
            'data' => $data
        ));
    }
    public function error($data = array(), $message = '失败')
    {
        if (!is_array($data)) {
            $message = $data;
            $data = array();
        }
        $this->echo(array(
            'status' => 0,
            'message' => $message,
            'data' => $data
        ));
    }

    /**
     * @description: 输出
     * @param {输出类容} $data
     * @param {输出的json中是否带有中文} $iszw
     * @return {结束后退出}
     */
    public function echo($data, $iszw = true)
    {
        if (is_array($data)) {
            if ($iszw) {
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode($data);
            }
        } else {
            echo $data;
        }
        exit;
    }
}
