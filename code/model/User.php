<?php

namespace Model;

use Daiyong\Db as db;

class User extends Common
{
    public function insert()
    {
        $id = db::insert('article', array(
            'id' => 999,
            'title' => '测试',
            'images' => '图片',
            'description' => '简介',
            'type' => 1,
            'time_create' => '2023-01-02',
            'date_create' => '2023-01-02'
        ));
        return $id;
    }
}
