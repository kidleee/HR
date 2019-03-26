<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class ceshi extends Controller
{
    public function index()
    {	
        echo "abc";
        $this->one();
        return 0;
        
    }
    public function two(){
		
        $this->redirect("http://www.runjianli.com/sifang/abc.html", ['val' => 2]);
    }
    public function one(){
        echo "</br>bcd";
    }
    public function tree(){
        $product1='abcd';
        Db::table('wxceshi')
        ->insert(['wxceshi'=>$product1]);
        return "abcd";
    }
}
