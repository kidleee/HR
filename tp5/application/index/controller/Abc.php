<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use endroid\qrcode\QrCode;

class Abc extends controller
{
    public function hello(){
        return 'hello';
    }
    public function view()
    {
        //生成当前的二维码
        $qrCode = new QrCode();
        
            //想显示在二维码中的文字内容，这里设置了一个查看文章的地址
            $url = url('http://www.runjianli.com','',true,true);
            $qrCode->setText($url)
                ->setSize(300)
                ->setPadding(10)
                ->setErrorCorrection('high')
                ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
                ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
                ->setLabel('thinkphp.cn')
                ->setLabelFontSize(16)
                ->setImageType(\Endroid\QrCode\QrCode::IMAGE_TYPE_PNG);
            $qrCode->render();
        }
    
}