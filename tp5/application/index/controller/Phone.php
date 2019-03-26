<?php
namespace app\index\controller;

use think\Db;

class Phone
{
    public function index()
    {
        $dbtest = Db::Connect('dbtest');
        $result = $dbtest->query('select * from user');
        dump($result);
        return 'Hello World!';
    }
}