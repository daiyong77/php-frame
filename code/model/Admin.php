<?php

namespace Model;

use \Daiyong\Db as db;
use \Daiyong\Func as func;

class Admin extends Common
{
    public function rule()
    {
        return array(
            'username|must' => array(
                array('string', '用户名不能为空且必须在6~20个字符之间',  6, 20),
                array('only', '已存在相同的用户名', 'admin'),
                array('reg', '用户名只能为英文或数字组合', '/^[\w]+$/')
            ),
            'password|must' => array('string', '密码不能为空且必须在6~20个字符之间', 6, 20),
            'gid|must' => array('idInTable', '未选择管理组或管理组不存在', 'admin_group'),
            'nickname' => array(
                array('stringName', '昵称必须在2~8(英文2~24)个字符之间', 2, 8),
                array('only', '已存在相同的昵称', 'admin')
            ),
            'mail' => array(
                array('mail', '请填写正确的邮箱地址'),
                array('only', '已存在相同的邮箱', 'admin')
            ),
            'phone' => array(
                array('phone', '请填写正确的手机号码'),
                array('only', '已存在相同的手机号码', 'admin')
            ),
        );
    }
    public function find($param)
    {
        $data = db::find('admin', $param);
        //加入权限组信息
        if (isset($data['group'])) {
            $data['group'] = db::find('admin_group', ['id' => $data['gid']]);
        }
        return $data;
    }
    public function findAll($param, $limit)
    {
        $param = $this->clear($param);
        $limit = $this->limit($limit);
        $list = db::findAll('admin', $param, $limit);
        //加入权限组信息
        $group = db::findAll('admin_group', [], 'id');
        foreach ($list as $k => $v) {
            $list[$k]['group'] = $group[$v['gid']];
        }
        return $list;
    }
    public function edit($data)
    {
        $data = $this->clear($data);
        $verify = $this->verify($data, $this->rule());
        if ($verify !== true) {
            return $this->error($verify);
        }
        $data = $this->auto($data);
        if (isset($data['id'])) {
            return db::update('admin', $data, ['id' => $data['id']]);
        } else {
            return db::insert('admin', $data);
        }
    }
    public function delete($param)
    {
        return db::delete('admin', $param);
    }

    private function auto($data)
    {
        if (isset($data['password'])) {
            $data['salt'] = func::random(5);
            $data['password'] = md5($data['salt'] . $data['password'] . $data['salt']);
        }
        if (!isset($data['id'])) {
            $data['time_create'] = date('Y-m-d H:i:s');
        } else {
            unset($data['time_create']);
        }
        return $data;
    }
}
