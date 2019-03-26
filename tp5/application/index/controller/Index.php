<?php
namespace app\index\controller;
use think\Db;
use think\Session;
include("CeShi.php");

class Index
{
    public function index()
    {
        return;
    }
    public function hello(){
        echo "abc";
        
    }
    public function ce(){
        $a=new ceshi();
        $b=$a->index();
    }
    public function cee(){
        require_once 'CeShi.php';
        $a=new ceshi();
        $b=$a->index();
    }
}
