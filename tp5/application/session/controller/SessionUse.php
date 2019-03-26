<?php
namespace app\session\controller;

use think\Session;

class SessionUse
{
    public function set($id)            //为session赋值
    {
        Session::set('uid',$id);
    }
    public function check()             //检查session是否存在
    {
        if(Session::has('unionid'))
        {
            echo 1;
        }
        else{
            echo 0;
        }
    }
    public function get()               //得到session
    {
        $result = Session::get('unionid');
        echo $result;
    }
    public function geto()               //得到session
    {
        $result = Session::get('out_trade_no');
        echo $result;
    }
    public function signout()           //用户主动退出账号时删除session
    {
        Session::clear();
    }
	public function imgview()	        //获取头像信息
	{
		$result = Session::get('headimgurl');
        echo $result;
	}
	public function nicknameview()	    //获取头像信息
	{
		$result = Session::get('nickname');
        echo $result;
	}
	public function user_idview()	    //获取头像信息
	{
		$result = Session::get('user_id');
        echo $result;
	}		
	public function getimg()	    //获取头像信息
	{
		$unionid = Session::get('unionid');
        $img = Session::get('headimgurl');
		if($unionid)
		{
			echo $img;
		}
		else
		{
			echo "请先登录";
		}	
	}	
}