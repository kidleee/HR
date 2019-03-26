<?php
namespace app\share\controller;

use think\Session;
use think\Db;

class Test{
    public function php()
    {
        Phpinfo();
    }
    public function createshareqr()
    {
        $cvweb = 'www.runjianli.com';
        $code = GetRandomString(12);
        $d=mktime(0, 0, 0, 8, 22, 2018);  //mktime(hour,minute,second,month,day,year)
        $dt = date('Y-m-d h:i',$d);
        Db::table('sharecv')
        ->insert(['name' => $code,'keyweb' => $cvweb,'expire' => $dt]);
        echo qrcode("http://www.runjianli.com/index.php/share/test/timejudge?code=$code",15);
    }
    public function timejudge($code)
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
            echo "<script language='javascript' type='text/javascript'>window.location.href='http://$cvweb'; </script>";
        }
        else
        {
            echo '分享已过期！';
        }
    }
}