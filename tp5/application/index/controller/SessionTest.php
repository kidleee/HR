<?php
namespace app\index\controller;

use think\Session;

class SessionTest
{
    public function set($id)
    {
        Session::set('unionid',$id);
    }
    public function judge()
    {
        if(Session::has('unionid'))
        {
            return 1;
        }
        else{
            return 0;
        }
    }
    public function get()
    {
        $result = Session::get('unionid');
        echo $result;
    }
}