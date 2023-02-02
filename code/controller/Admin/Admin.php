<?php

namespace Controller\Admin;

class Admin extends \Controller\Common
{
    private $adminModel;
    public function __construct()
    {
        parent::__construct();
        $this->adminModel = new \Model\Admin();
    }
    public function index()
    {
        $get = @array(
            'id' => (int)$_GET['id']
        );
        $data = $this->adminModel->find(['id' => $get['id']]);
        $this->echo($data);
    }
    public function list()
    {
        $get = @array(
            'begin' => (int)$_GET['begin'],
            'size' => (int)$_GET['size']
        );
        $getSql = @array(
            'gid' => (int)$_GET['gid'],
            'username|like' => '%' . $_GET['username'] . '%',
            'nickname|like' => '%' . $_GET['nickname'] . '%',
            'phone|like' => '%' . $_GET['phone'] . '%',
            'mail|like' => '%' . $_GET['mail'] . '%',
            'time_create|>=' => $_GET['time_begin'],
            'time_create|<=' => $_GET['time_end'],
        );
        $list = $this->adminModel->findAll($getSql, [$get['begin'], $get['size']]);
        $this->echo($list);
    }
    public function edit()
    {
        $post = @array(
            'id' => (int)$_POST['id'],
            'username' => trim($_POST['username']),
            'password' => trim($_POST['password']),
            'nickname' => trim($_POST['nickname']),
            'phone' => trim($_POST['phone']),
            'mail' => trim($_POST['mail']),
            'gid' => (int)($_POST['gid']),
            'head' => trim($_POST['head'])
        );
        $return = $this->adminModel->edit($post);
        if (is_array($return)) {
            $this->error($return['message']);
        }
        if ($post['id']) {
            $this->return($return, '修改成功', '未进行任何修改');
        } else {
            $this->return($return, '新增成功', '新增失败');
        }
    }
    public function delete()
    {
        $get = @array(
            'id' => (int)$_GET['id']
        );
        $return = $this->adminModel->delete(['id' => $get['id']]);
        $this->return($return, '删除成功');
    }
}
