<?php

namespace Controller;

use Daiyong\Db as db;

class Common
{
    public $config = array();
    public function __construct()
    {
        global $CONFIG;
        $this->config = $CONFIG;

        db::connect($this->config['db']);
    }
}
