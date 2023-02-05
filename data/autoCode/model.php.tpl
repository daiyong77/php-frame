<?php

namespace Model;

use \Daiyong\Db as db;

class {{Model}} extends Common {
    public function rule() {
        return array(
            //详细写法参考readme.md
            {{rule}}
        );
    }
    public function find($param) {
        $data = db::find('{{table}}', $param);
        //加入其他信息
        /*if (isset($data['group'])) {
            $data['group'] = db::find('table_group', ['id' => $data['gid']]);
        }*/
        return $data;
    }
    public function findAll($param, $limit) {
        $param = $this->clear($param);
        $limit = $this->limit($limit);
        $list = db::findAll('{{table}}', $param, $limit);
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
            return db::update('{{table}}', $data, ['id' => $data['id']]);
        } else {
            return db::insert('{{table}}', $data);
        }
    }
    public function delete($param) {
        return db::delete('{{table}}', $param);
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
