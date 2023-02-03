<?php

namespace Model;

use Daiyong\Db as db;

class Common {
    public $config = array();
    public function __construct() {
        global $CONFIG;
        $this->config = $CONFIG;
    }

    public function error($message = '失败') {
        return array(
            'status' => 0,
            'message' => $message
        );
    }

    /**
     * @description: 数组转换成limit,如果传入错误或者更大的则替换成默认
     * @param {例:limit([0,10])} $data
     * @return {例:limit 0,10}
     */
    public function limit($data) {
        $data[0] = (int)$data[0];
        $data[1] = (int)$data[1];
        if ($data[0] < 0) $data[0] = 0;
        if ($data[1] <= 0) $data[1] = $this->config['page']['limitDefault'];
        if ($data[1] > $this->config['page']['limitMax']) {
            $data[1] = $this->config['page']['limitMax'];
        }
        return 'limit ' . $data[0] . ',' . $data[1];
    }
    /**
     * @description: 数组中如果有为空的则去除
     * @param {array} $data
     * @return {array}
     */
    public function clear($data) {
        $newData = array();
        foreach ($data as $k => $v) {
            if (!$v || $v == '%' || $v == '%%') continue;
            $newData[$k] = $v;
        }
        return $newData;
    }

    /**
     * @description: 验证
     * @param {数据} $data
     * @param {规则} $rule
     * @return {不通过返回string,通过返回true}
     */
    public function verify($data, $rule) {
        foreach ($rule as $k => $v) {
            if (is_string($v[0])) { //一维数组则转换成二维数组
                $v = array($v);
            }
            $k = explode('|', $k);
            $field = $k[0];

            foreach ($v as $v2) {

                if (
                    !isset($data['id']) &&
                    isset($k[1]) && $k[1] == 'must' &&
                    !isset($data[$field])
                ) { //如果不存在则直接返回错误
                    return $v2[1];
                }
                if (isset($data[$field])) {
                    switch ($v2[0]) {
                        case 'idInTable': //id是否在一个表里面
                            if (!db::find($v2[2] . '|id', array('id' => $data[$field]))) {
                                return $v2[1];
                            }
                            break;
                        case 'only': //唯一判断
                            $where = array($field => $data[$field]);
                            if (isset($data['id'])) { //如果是修改则过滤自己的重复判断
                                $where['id|!='] = $data['id'];
                            }
                            if (db::find($v2[2] . '|id', $where)) {
                                return $v2[1];
                            }
                            break;
                        case 'stringName': //一般用作用户昵称使用(3个\w记为1个字符,如果\w太短则1个\w作为1个字符)
                            $len = mb_strlen($data[$field]);
                            //将(每3个英文与数字)计算成一个字符
                            preg_match_all('/[\w]+/', $data[$field], $matches);
                            $res = implode('', $matches[0]);
                            $len = $len - (int)(strlen($res) - strlen($res) / 3);
                            if ($len < $v2[2]) {
                                //英文最小长度转换成实际长度再计算
                                if (preg_match('/^[\w]+$/', $data[$field])) {
                                    if (strlen($data[$field]) < $v2[2]) {
                                        return $v2[1];
                                    }
                                } else {
                                    return $v2[1];
                                }
                            }
                            if ($len > $v2[3]) {
                                return $v2[1];
                            }
                            break;
                        case 'reg':
                            if (!preg_match($v2[2], $data[$field])) {
                                return $v2[1];
                            }
                            break;
                        case 'phone':
                            if (!preg_match('/^1[\d]{10}$/', $data[$field])) {
                                return $v2[1];
                            }
                            break;
                        case 'mail':
                            if (!preg_match('/^\w+@\w+\.\w+(|\.\w+)$/', $data[$field])) {
                                return $v2[1];
                            }
                            break;
                        case 'string':
                            $len = mb_strlen($data[$field]);
                            if ($len < $v2[2] || $len > $v2[3]) {
                                return $v2[1];
                            }
                            break;
                    }
                }
            }
        }
        return true;
    }
}
