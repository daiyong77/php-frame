<?php

namespace Controller\{{Namespace}};

class {{Controller}} extends \Controller\Common {
    private ${{model}}Model;
    public function __construct() {
        parent::__construct();
        $this->{{model}}Model = new \Model\{{Model}}();
    }
    public function index() {
        $get = @array(
            'id' => (int)$_GET['id']
        );
        $data = $this->{{model}}Model->find(['id' => $get['id']]);
        $this->echo($data);
    }
    public function list() {
        $get = @array(
            'begin' => (int)$_GET['begin'],
            'size' => (int)$_GET['size']
        );
        $getSql = @array(
            {{search}}
        );
        $list = $this->{{model}}Model->findAll($getSql, [$get['begin'], $get['size']]);
        $this->echo($list);
    }
    public function edit() {
        $post = @array(
            'id' => (int)$_POST['id'],
            {{edit}}
        );
        $return = $this->{{model}}Model->edit($post);
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
        $return = $this->{{model}}Model->delete(['id' => $get['id']]);
        $this->return($return, '删除成功');
    }
}
