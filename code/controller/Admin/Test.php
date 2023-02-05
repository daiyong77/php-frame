<?php

namespace Controller\Admin;

class Test extends \Controller\Common {
    private $testModel;
    public function __construct() {
        parent::__construct();
        $this->testModel = new \Model\Test();
    }
    public function index() {
        $get = @array(
            'id' => (int)$_GET['id']
        );
        $data = $this->testModel->find(['id' => $get['id']]);
        $this->echo($data);
    }
    public function list() {
        $get = @array(
            'begin' => (int)$_GET['begin'],
            'size' => (int)$_GET['size']
        );
        $getSql = @array(
            'title|like' => '%' . $_GET['title'] . '%',
            'type' => (int)$_GET['type'],
            'date_create|>=' => $_GET['date_create_begin'],
            'date_create|<=' => $_GET['date_create_end']
        );
        $list = $this->testModel->findAll($getSql, [$get['begin'], $get['size']]);
        $this->echo($list);
    }
    public function edit() {
        $post = @array(
            'id' => (int)$_POST['id'],
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'images' => $_POST['images'],
            'type' => (int)$_POST['type'],
            'time_create' => $_POST['time_create'],
            'date_create' => $_POST['date_create']
        );
        $return = $this->testModel->edit($post);
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
        $return = $this->testModel->delete(['id' => $get['id']]);
        $this->return($return, '删除成功');
    }
}
