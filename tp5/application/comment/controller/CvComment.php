<?php
namespace app\comment\controller;

use think\Db;
use think\Session;

class CvComment
{
	public function save_comment()
	{
		$unionid = Session::get('unionid');
		if($unionid)
		{
			$comment = input('post.comment');
			$time = input('post.time');
			
			$model_id = 2;
			$floor = Db::table('comment')
				->where('model_id', $model_id)
				->count();
			$floor = $floor + 1;
			
			Db::table('comment')
                ->insert(['unionid'=>$unionid,'model_id'=>$model_id,'comment'=>$comment,'time'=>$time ,'floor'=>$floor]);
		}
		else
		{
			echo 0;
		}
	}
	public function read_comment()
	{
		header('Content-type:text/json');
        $uid = Session::get('unionid');
		$model_id = 1;
        if($uid)
        {
		    $result = Db::table('comment')
				    ->where('model_id',$model_id)
				    ->find();
            echo json_encode($result);
        }
        else{
            echo "您还未进行登录！";
        }
	}
}