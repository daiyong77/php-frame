<?php

namespace Model;

use \Daiyong\Db as db;

class AdminGroup extends Common {
    public function rule() {
        return array(
            'name|must' => array(
                array('string', '管理组名称必须在2~15个字符之间', 2, 15),
                array('only', '管理组名称重复', 'admin_group'),
            ),
            'power|must' => array('none', '请填写权限信息'),
            'lv|must' => array('none', '请输入正确的等级')
            /*详细写法参考readme.md
            'username|must' => array(//这是一个例子
                array('string', '用户名必须在6~15个字符之间',  6, 15),
                array('only', '用户名重复', 'admin'),
                array('reg', '用户名必须为英文或数字组合', '/^[\w]+$/'),
            ),
            'nickname'=>array(//这是一个例子
                array('stringName', '昵称必须在2~6个字符之间', 2, 6),
            ),
            'mail'=>array('mail', '请填写正确的邮箱地址'),//这是一个例子
            'phone'=>array('phone', '请填写正确的手机号码'),//这是一个例子
            'group'=>array('idInTable','您选择的类型不存在','admin_type'),//这是一个例子
            'gid'=>array('none','请选择组管理组'),//这是一个例子
        */
        );
    }
    public function find($param) {
        $data = db::find('admin_group', $param);
        //加入其他信息
        /*if (isset($data['group'])) {
            $data['group'] = db::find('table_group', ['id' => $data['gid']]);
        }*/
        return $data;
    }
    public function findAll($param, $limit) {
        $param = $this->clear($param);
        $limit = $this->limit($limit);
        $list = db::findAll('admin_group', $param, $limit);
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
            return db::update('admin_group', $data, ['id' => $data['id']]);
        } else {
            return db::insert('admin_group', $data);
        }
    }
    public function delete($param) {
        return db::delete('admin_group', $param);
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
