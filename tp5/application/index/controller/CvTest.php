<?php 
namespace app\index\controller;

use think\Db;

class CvTest
{
    public function index()
    {
        $result = Db::query('select * from cv_intention');
        dump($result);
        echo phpinfo();
    }
}