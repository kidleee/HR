<?php
namespace app\qrcode\controller;

use think\Controller;

class QrC extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function creQr()
    {
         //内容,下方注释,中间logo地址,logo大小,二维码大小
        echo qrcode('http://www.runjianli.com',23);
        exit;
    }
}
