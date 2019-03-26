<?php
namespace app\index\controller;

use think\Db;
use think\Session;

class SuperManager
{
    public function viewuser()           //查看所有用户信息
    {
        header('Content-type:text/json');
        $result = Db::table('user')
                ->select();
        $jsonre = json(0,'',1000,$result);
        echo $jsonre;
    }
	public function nicknamechange()	//修改用户名
	{
		$unionid = input('post.unionid');
		$nickname = input('post.nickname');
		Db::table('user')
		->where('unionid',$unionid)
		->update(['nickname' => $nickname]);
	}
	public function sexchange()			//修改性别（迷）
	{
		$unionid = input('post.unionid');
		$sex = input('post.sex');
		Db::table('user')
		->where('unionid',$unionid)
		->update(['sex' => $sex]);
	}
	public function citychange()		//城市修改
	{
		$unionid = input('post.unionid');
		$city = input('post.city');
		Db::table('user')
		->where('unionid',$unionid)
		->update(['city' => $city]);
	}
	public function usertypechange()	//用户类型修改
	{
		$unionid = input('post.unionid');
		$usertype = input('post.usertype');
		Db::table('user')
		->where('unionid',$unionid)
		->update(['usertype' => $usertype]);
	}
	public function validchange()		//用户有效性修改
	{
		$unionid = input('post.unionid');
		$valid = input('post.valid');
		Db::table('user')
		->where('unionid',$unionid)
		->update(['valid' => $valid]);
	}
	public function setmanager()	//添加管理员
	{
		$id = input('post.id');
		$secret = input('post.secret');
		$secret = dmd5($secret);
		Db::table('manager')
		->insert(['m_id' => $id,'m_secret' => $secret]);
	}
	public function deleteuser()				//删除用户
	{
		$unionid = input('post.unionid');
		Db::table('user')
		->where('unionid',$unionid)
		->delete();
	}
	public function midchange()					//修改管理员id
	{
		$id = input('post.m_id');
		$newid = input('post.newid');
		Db::table('manager')
		->where('m_id',$id)
		->update(['m_id',$newid]);
	}
	public function msecretchange()				//修改管理员密码
	{
		$id = input('post.m_id');
		$secret = input('post.secret');
		$secret = dmd5($secret);
		Db::table('manager')
		->where('m_id',$id)
		->update(['secret',$secret]);
	}
	public function deletem()					//删除管理员
	{
		$id = input('post.m_id');
		Db::table('manager')
		->where('m_id',$id)
		->delete();
	}
	public function noticein()					//公告测试
	{
		$content = input('post.content');
		$title = input('post.title');
		$date = input('post.date');
		$brief = input('post.brief');
		Db::table('notice')
		->insert(['date' => $date, 'content' => $content, 'title' => $title, 'brief' => $brief]);
	}
	public function noticede()					//公告删除
	{
		$nid = input('post.nid');
		Db::table('notice')
		->where('nid', $nid)
		->delete();
	}
	public function viewnotice()				//浏览公告
	{
		header('Content-type:text/json');
        $result = Db::table('notice')
				->select();
		$jsonre = json(0,'',1000,$result);
        echo $jsonre;
	}
	public function viewnotice1()				//浏览公告
	{
		header('Content-type:text/json');
        $result = Db::table('notice')
				->select();
		echo json_encode($result);
	}
	public function viewnotice2()				//浏览详细公告
	{
		header('Content-type:text/json');
		$nid = input('post.nid');
        $result = Db::table('notice')
		->where('nid', $nid)			
		->select();
		echo json_encode($result);
	}
	//每日清除无效订单
	public function clear(){
		Db::table('product')->where('pay_id','未完成订单')->delete();
		Db::table('model_made')->where('pay','未完成订单')->delete();
		Db::table('hr_account')->where('pay','未支付订单')->delete();
	}

	//查看所有HR信息
	public function view_HR(){
		header('Content-type:text/json');
        $result = Db::table('consultant')
                    ->select();
        $jsonre = json (0,'',1000,$result);
        echo $jsonre;
	}
	//根据手机号查询HR信息
	public function view_HR_phone(){
		header('Content-type:text/json');
		$phone = input('post.phone');
		$result = Db::table('consultant')
					->where('phone',$phone)
					->select();
        echo json_encode($result);
	}
	public function viewrefund()           //查看所有退款信息
    {
        header('Content-type:text/json');
        $result = Db::table('refund')
                ->select();
        $jsonre = json(0,'',1000,$result);
        echo $jsonre;
    }
	public function viewcompany()           //查看所有企业信息
    {
        header('Content-type:text/json');
        $result = Db::table('company_cert')
                ->select();
        $jsonre = json(0,'',1000,$result);
        echo $jsonre;
    }
	public function view_company_phone(){
		header('Content-type:text/json');
		$phone = input('post.phone');
		$result = Db::table('company_cert')
					->where('phone',$phone)
					->select();
        echo json_encode($result);
	}
	
}