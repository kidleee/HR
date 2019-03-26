<?php
namespace app\session\controller;

use think\Controller;
use think\Db;
use think\Session;

class Web extends Controller{
    //页面拦截
    public function web(){
        $url= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        
        $unionid = session::has('unionid');
        if($unionid){
            return;
        }else{
            $this->redirect("http://www.runjianli.com/sifang/login.html");
        }
    }
}