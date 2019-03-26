<?php
namespace app\share\controller;

class CvShare{
   public function qr()
   {
        $web = input('post.cvweb');
        echo qrcode($web,23);
   } 
}