<?php
namespace app\cv\controller;

use think\Session;
use think\Db;
use think\Controller;
use think\Request;

Class CvSpread extends Controller{
    public function CvPrint()           //导出
    {
        $uid = Session::get('unionid');
        $cid = Session::get('cv_id');
		//$modelid = Session::get('modelid');
        $pdf = Db::table('cv_keep')
                ->where('unionid',$uid)
                ->where('cv_id',$cid)
                ->value('pdfurl');	
		//$modelid = Db::table('cv_keep')
               // ->where('unionid',$uid)
              //  ->where('cv_id',$cid)
               // ->value('modelid');				
				
        $inner = Db::table('cv_keep')
                ->where('unionid',$uid)
                ->where('cv_id',$cid)
                ->value('innerhtml');	
        $code = GetRandomString(10);
        header('content-type:text/html;charset=utf-8');
        $extend = '.html';
        $path='cvex_'.$code.$extend;
        $handle=fopen('/var/www/html/tp5/public/sifang/Export/S10004.html','rb');
        $str=fread($handle,filesize('/var/www/html/tp5/public/sifang/Export/S10004.html'));
        $str=str_replace('{pdfurl}', $pdf, $str);
        $str=str_replace('{innerhtml}', $inner, $str);
        fclose($handle);
        $handle1=fopen('/var/www/html/tp5/public/sifang/Export/'.$path,'wb');
        fwrite($handle1,$str);
        fclose($handle1);
        $detail = "http://www.runjianli.com/sifang/Export/$path";
        $this->redirect($detail);
    }
    public function ShareCv()       //分享
    {
        $uid = Session::get('unionid');
        $cid = Session::get('cv_id');
        $pdf = Db::table('cv_keep')
                ->where('unionid',$uid)
                ->where('cv_id',$cid)
                ->value('pdfurl');
        $inner = Db::table('cv_keep')
                ->where('unionid',$uid)
                ->where('cv_id',$cid)
                ->value('innerhtml');
        $day = input('post.day');
        $hour = input('post.hour');
        $now = date('Y-m-d h:i');
        $code = GetRandomString(12);
        $webcode = GetRandomString(10);
        $dt = date('Y-m-d H:i',strtotime("+$day day +$hour hour"));
        //
        header('content-type:text/html;charset=utf-8');
        $extend = '.html';
        $path='cvshare_'.$webcode.$extend;
        $handle=fopen('/var/www/html/tp5/public/sifang/Export/Share/ceshi.html','rb');
        $str=fread($handle,filesize('/var/www/html/tp5/public/sifang/Export/Share/ceshi.html'));
        $str=str_replace('{pdfurl}', $pdf, $str);
        $str=str_replace('{innerhtml}', $inner, $str);
        fclose($handle);
        $handle1=fopen('/var/www/html/tp5/public/sifang/Export/Share/'.$path,'wb');
        fwrite($handle1,$str);
        fclose($handle1);
        //
        $cvweb = "http://www.runjianli.com/sifang/Export/Share/$path";
        Db::table('sharecv')
        ->insert(['name' => $code,'keyweb' => $cvweb,'expire' => $dt]);
        echo $code;
    }
    public function CreQr($code)
    {
        echo qrcode("http://www.runjianli.com/index.php/cv/cv_spread/timejudge?code=$code",8);
    }
    public function Timejudge($code)
    {
        $now = date('Y-m-d h:i');
        $dt = Db::table('sharecv')
                ->where('name',$code)
                ->value('expire');
        $cvweb = Db::table('sharecv')
                ->where('name',$code)
                ->value('keyweb');
        if(strtotime($now)<strtotime($dt))
        {
            $this->redirect($cvweb);
        }
        else
        {
            echo '分享已过期！';
        }
    }
    public function DownLoadpdf()
    {
        $uid = Session::get('unionid');
        $cid = Session::get('cv_id');
        $pdf = Db::table('cv_keep')
        ->where('unionid',$uid)
        ->where('cv_id',$cid)
        ->value('pdfurl');
        $this->redirect($pdf);
    }
}