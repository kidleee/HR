<?php
namespace app\cert\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;

class ModelFinde extends controller{
    //全局优先函数
    /*public function _initialize()
    {       
        if(session::has('unionid')){
            return ;
        }else{
            $this->redirect("http://www.runjianli.com");
        }
        ;
    }*/
    //后台录用函数(我没有找到整个模板录入在哪里)
    public function insert_class(){
        return 'insert_class';
    }
    //word模块
    //金融
    public function financial(){

        $result = Db::table('model')
          ->where('class','like','%金融%')
          ->where('format','doc')
          ->select();
          $jsonre = json (0,'',1000,$result);
          echo $jsonre;
    }
    //中文
    public function Chinese(){

        $result = Db::table('model')
          ->where('class','like','%中文%')
          ->where('format','doc')
          ->select();
          $jsonre = json (0,'',1000,$result);
          echo $jsonre;
    }
    //英文
    public function English(){

        $result = Db::table('model')
          ->where('class','like','%英文%')
          ->where('format','doc')
          ->select();
          $jsonre = json (0,'',1000,$result);
          echo $jsonre;
    }
    //表格
    public function form(){

        $result = Db::table('model')
          ->where('class','like','%表格%')
          ->where('format','doc')
          ->select();
          $jsonre = json (0,'',1000,$result);
          echo $jsonre;
    }
    //企业
    public function enterprise(){

        $result = Db::table('model')
          ->where('class','like','%表格%')
          ->where('format','doc')
          ->select();
          $jsonre = json (0,'',1000,$result);
          echo $jsonre;
    }
    //院校
    public function school(){

        $result = Db::table('model')
          ->where('class','like','%表格%')
          ->where('format','doc')
          ->select();
          $jsonre = json (0,'',1000,$result);
          echo $jsonre;
    }
    //小升初
    public function xiaoshengchu(){

        $result = Db::table('model')
          ->where('class','like','%表格%')
          ->where('format','doc')
          ->select();
          $jsonre = json (0,'',1000,$result);
          echo $jsonre;
    }
    //自荐信
    public function coverletter(){

        $result = Db::table('model')
          ->where('class','like','%表格%')
          ->where('format','doc')
          ->select();
          $jsonre = json (0,'',1000,$result);
          echo $jsonre;
    }
    //封面
    public function frontcover(){

        $result = Db::table('model')
          ->where('class','like','%表格%')
          ->where('format','doc')
          ->select();
          $jsonre = json (0,'',1000,$result);
          echo $jsonre;
    }
    //热门推荐
    public function popular(){

        $result = Db::table('model')
          ->where('class','like','%表格%')
          ->where('format','doc')
          ->select();
          $jsonre = json (0,'',1000,$result);
          echo $jsonre;
    }
    //销售最多
    public function mostsales(){

        $result = Db::table('model')
          ->where('class','like','%表格%')
          ->where('format','doc')
          ->select();
          $jsonre = json (0,'',1000,$result);
          echo $jsonre;
    }
    //人气最旺
    public function mostpopular(){

        $result = Db::table('model')
          ->where('class','like','%表格%')
          ->where('format','doc')
          ->select();
          $jsonre = json (0,'',1000,$result);
          echo $jsonre;
    }
}