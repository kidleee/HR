<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;

class CeshiOne extends Controller{
    public function ceshi(){
        $arr = input('get.service_type/a');
        //$arr = array('Hello','World!','I');
        $abc = implode(" ",$arr);

        echo $abc;
        Db::table('wxceshi')
               ->insert(['wxceshi'=>$abc]);
    }
    
    public function ceshi1(){
         $ceshi = Db::table('wxceshi')
               ->where(['unionid' => '1'])
               ->value('wxceshi');
              // echo $ceshi;
        $one = explode(" ",$ceshi);
        $two=dump($one);
        echo $two;
    }
}