<?php

namespace Controller\Admin;

class Index extends Common
{
    public function Index()
    {
        $userModel = new \Model\User();
        $id = $userModel->insert();
        echo $id;
    }
}
