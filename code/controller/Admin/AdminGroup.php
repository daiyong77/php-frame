<?php

namespace Controller\Admin;

class AdminGroup extends \Controller\Common {
    private $adminGroupModel;
    public function __construct() {
        parent::__construct();
        $this->adminGroupModel = new \Model\adminGroup();
    }
    public function index() {
        $get = @array(
            'id' => (int)$_GET['id']
        );
        $data = $this->adminGroupModel->find(['id' => $get['id']]);
        $this->echo($data);
    }
    public function list() {
        $get = @array(
            'begin' => (int)$_GET['begin'],
            'size' => (int)$_GET['size']
        );
        $getSql = @array(
            'lv' => (int)$_GET['lv'],
            'name|like' => '%' . $_GET['name'] . '%'
        );
        $list = $this->adminGroupModel->findAll($getSql, [$get['begin'], $get['size']]);
        $this->echo($list);
    }
    public function edit() {
        $post = @array(
            'id' => (int)$_POST['id'],
            'name' => $_POST['name'],
            'power' => $_POST['power'],
            'lv' => (int)$_POST['lv']
        );
        $return = $this->adminGroupModel->edit($post);
        if (is_array($return)) {
            $this->error($return['message']);
        }
        if ($post['id']) {
            $this->return($return, '修改成功', '未进行任何修改');
        } else {
            $this->return($return, '新增成功', '新增失败');
        }
    }
    public function delete() {
        $get = @array(
            'id' => (int)$_GET['id']
        );
        $return = $this->adminGroupModel->delete(['id' => $get['id']]);
        $this->return($return, '删除成功');
    }
}
