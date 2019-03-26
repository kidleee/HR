<?php
namespace app\cv\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;

class ModelFind extends controller{
   //全局优先函数
   /*
   public function _initialize()
   {  
    if(session::has('unionid')){
        return ;
    }else{
        $this->redirect("http://www.runjianli.com/sifang/login.html");
    }
   }*/
   //后台录用函数(我没有找到整个模板录入在哪里)
   public function insert_class(){
       return 'insert_class';
   }
    
    //搜索栏模糊搜索
    public function insert(){
        
        header('Content-type:text/json');
		$char = input('post.searchvalue');
        $result = Db::table('model')
            ->where('class','like','%'.$char.'%')
            ->where('format','Word')
            ->select();
            echo json_encode($result);
    }
    //word模块
    //金融
    public function financial(){
        header('Content-type:text/json');
        $result = Db::table('model')
          ->where('class','like','%金融%')
          ->where('format','Word')
          ->select();
          echo json_encode($result);
    }
    //中文
    public function Chinese(){

        $result = Db::table('model')
          ->where('class','like','%中文%')
          ->where('format','Word')
          ->select();
          echo json_encode($result);
    }
    //英文
    public function English(){

        $result = Db::table('model')
          ->where('class','like','%英文%')
          ->where('format','Word')
          ->select();
          
          echo json_encode($result);
    }
    //表格
    public function form(){

        $result = Db::table('model')
          ->where('class','like','%表格%')
          ->where('format','Word')
          ->select();
          echo json_encode($result);
    }
    //企业
    public function enterprise(){

        $result = Db::table('model')
          ->where('class','like','%企业%')
          ->where('format','Word')
          ->select();
          echo json_encode($result);
    }
    //院校
    public function school(){

        $result = Db::table('model')
          ->where('class','like','%院校%')
          ->where('format','Word')
          ->select();
          echo json_encode($result);
    }
    //小升初
    public function xiaoshengchu(){

        $result = Db::table('model')
          ->where('class','like','%小升初%')
          ->where('format','Word')
          ->select();
          echo json_encode($result);
    }
    //自荐信
    public function coverletter(){

        $result = Db::table('model')
          ->where('class','like','%自荐信%')
          ->where('format','Word')
          ->select();
          echo json_encode($result);
    }
    //封面
    public function frontcover(){

        $result = Db::table('model')
          ->where('class','like','%封面%')
          ->where('format','Word')
          ->select();
          echo json_encode($result);
    }
    //热门推荐
    public function popular(){

        $result = Db::table('model')
          ->where('class','like','%热门%')
          ->where('format','Word')
          ->select();
          echo json_encode($result);
    }
    //销售最多
    public function mostsales(){

        $result = Db::table('model')
          ->where('class','like','%销售%')
          ->where('format','Word')
          ->select();
          echo json_encode($result);
    }
    //人气最旺
    public function mostpopular(){

        $result = Db::table('model')
          ->where('class','like','%人气%')
          ->where('format','Word')
          ->select();
          echo json_encode($result);
    }

    //ppt模块
    //简约
    //清新
    //商务风
    //中国风
    //欧美风
    public function modelsearch()
	{
		//$word = input('post.word');
		$word = "邓瑞璠大傻逼";
		$arr = preg_split("//u", $word, -1, PREG_SPLIT_NO_EMPTY);
		for($i = 0;$i<count($arr);$i++)
		{
			echo $arr[$i];
		}
		dump($arr);
	}
}