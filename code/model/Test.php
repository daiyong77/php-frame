<?php

namespace Model;

use \Daiyong\Db as db;

class Test extends Common {
    public function rule() {
        return array(
            //详细写法参考readme.md
            'title|must' => array('none', '请填写title'),
            'description|must' => array('none', '请填写description'),
            'images|must' => array('none', '请填写images'),
            'type|must' => array('int', '请输入正确的type'),
            'time_create|must' => array('time', '请输入正确的time_create'),
            'date_create|must' => array('date', '请输入正确的date_create')
        );
    }
    public function find($param) {
        $data = db::find('article', $param);
        //加入其他信息
        /*if (isset($data['group'])) {
            $data['group'] = db::find('table_group', ['id' => $data['gid']]);
        }*/
        return $data;
    }
    public function findAll($param, $limit) {
        $param = $this->clear($param);
        $limit = $this->limit($limit);
        $list = db::findAll('article', $param, $limit);
        //加入其他信息
        /*$group = db::findAll('table_group', [], 'id');
        foreach ($list as $k => $v) {
            $list[$k]['group'] = $group[$v['gid']];
        }*/
        return $list;
    }
    public function edit($data) {
        $data = $this->clear($data);
        $verify = $this->verify($data, $this->rule());
        if ($verify !== true) {
            return $this->error($verify);
        }
        $data = $this->auto($data);
        if (isset($data['id'])) {
            return db::update('article', $data, ['id' => $data['id']]);
        } else {
            return db::insert('article', $data);
        }
    }
    public function delete($param) {
        return db::delete('article', $param);
    }

    private function auto($data) {
        /*if (!isset($data['id'])) {
            $data['time_create'] = date('Y-m-d H:i:s');
        } else {
            unset($data['time_create']);
        }*/
        return $data;
    }
}
