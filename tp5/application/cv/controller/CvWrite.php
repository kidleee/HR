<?php
namespace app\cv\controller;

use think\Db;
use think\Session;

class CvWrite
{
    public function check_cv()
    {
        if(Session::has('unionid'))
        {
            $uid = Session::get('unionid');
            $res = Db::table('cv')
                    ->where('unionid', $uid)
                    ->find();
            if($res)
            {
                echo '已存在该条记录';
            }
            else{
                Db::table('cv')
                ->insert(['unionid' => $uid]);
            }
        }
        else{
            echo '您还未进行登录!';
        }
    }
	/*
    public function save_intention()
    {
		
        $uid = Session::get('unionid');
        $cid = Session::get('cv_id');
        if($uid)
        {
            $job = input('post.demo1');
		    $type = input('post.demo2');
		    $iprovince = input('post.iprovince');
		    $icity = input('post.icity');
		    $entrytime = input('post.demo4');
		    $salary1 = input('post.demo5');
            $salary2 = input('post.demo6');
            $iarr = array($iprovince, $icity);
            $intentioncity = implode('', $iarr);
            if($salary1 = "自定义")
            {
                Db::table('cv')
                ->where('unionid', $uid)
                ->where('cv_id', $cid)
                ->update(['intentionjob' => $job, 'jobtype' => $type, 'intentioncity' => $intentioncity, 'entrytime' => $entrytime,
                'salary' => $salary2]);
            }
            else{
                Db::table('cv')
                ->where('unionid', $uid)
                ->where('cv_id', $cid)
                ->update(['intentionjob' => $job, 'jobtype' => $type, 'intentioncity' => $intentioncity, 'entrytime' => $entrytime,
                'salary' => $salary1]);
            }
        }
		else
		{
			echo "您还未进行登录！";
		}
    }*/
	public function save_base()
	{
        $uid = Session::get('unionid');
        $cid = Session::get('cv_id');
        if($uid)
        {
            $name = input('post.message1');
			$year = input('post.message2');
			$month = input('post.message3');
            $province = input('post.province');
            $city = input('post.city');
            $phone = input('post.message6');
            $email = input('post.message7');
            $introduction = input('post.message8');
			$arr = array($province, $city);
			$arr1 = array($year, $month);
			$incity = implode('', $arr);
			$birthday = implode('', $arr1);
			Db::table('cv')
                ->where('unionid', $uid)
                ->where('cv_id', $cid)
                ->update(['name' => $name, 'birthday' => $birthday, 'city' => $incity, 'phone' => $phone,
                'emali' => $email, 'introduction' => $introduction]);
        }
		else
		{
			echo "您还未进行登录！";
		}	
    }
    public function read()
    {
        header('Content-type:text/json');
        $uid = Session::get('unionid');
        $cid = input('post.cv_id');
        if($uid)
        {
		    $result = Db::table('cv')
                    ->where('unionid',$uid)
                    ->where('cv_id', $cid)
				    ->find();
            echo json_encode($result);
        }
        else{
            echo "您还未进行登录！";
        }
    }
}