<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;

class PictureUp extends Controller{
    public function upload(){
      $test=input('post.username');
      $picture=input('post.file');
      echo $test;
      echo $picture;
    }
    public function one(){
        $wenjian='abc';
        return  $wenjian;
    }
    public function index(){    
        // 获取表单上传文件 例如上传了001.jpg   
        $test =input('post.username');
        echo $test; 
        $file = request()->file('image'); 
        // 移动到框架应用根目录/public/uploads/ 目录下    
        $info = $file->move(ROOT_PATH . 'public' . DS . 'photo/sifang');    
        if($info){                   
             // 成功上传后 获取上传信息      
             echo $info->getExtension();        
             // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg      
             echo $info->getSaveName();
             $HR_PICTURE=$info->getSaveName();   
             // 输出 42a79759f284b767dfcb2a0197904287.jpg        
             echo $info->getFilename();
             echo "<br>";
            echo $info;    }
             else{        
                 //上传失败获取错误信息       
                 echo $file->getError();    
                } 
}
    }
