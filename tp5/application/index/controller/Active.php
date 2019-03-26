<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Request;
class Active
{
    public function jobwrite()							//存入或更新数据库
    {
		$phone = $_POST['phone'];
		$job = $_POST['demo1'];
		$type = $_POST['demo2'];
		$province = $_POST['province'];
		$city = $_POST['city'];
		$time = $_POST['demo4'];
		$salary1 = $_POST['demo5'];
		$salary2 = $_POST['demo6'];
		$arr = array($province,$city);
		$intentioncity = implode('',$arr);
		$result = Db::table('cv')
				->where('phone',$phone)
				->find();
		if($result)									//判断该数据是否存在，存在则进行更新
		{
			if($salary1 == "自定义")
			{
					Db::table('cv')
					->update(['phone' => $phone, 'intentionjob' => $job, 'jobtype' => $type, 'intentioncity' => $intentioncity,
					'entrytime' => $time, 'salary' => $salary2]);
			}
			else
			{
					Db::table('cv')
					->update(['phone' => $phone, 'intentionjob' => $job, 'jobtype' => $type, 'intentioncity' => $intentioncity,
					'entrytime' => $time, 'salary' => $salary1]);
			}
		}
		else										//不存在则进行插入
		{
			if($salary1 == "自定义")
			{
					Db::table('cv')
					->insert(['phone' => $phone, 'intentionjob' => $job, 'jobtype' => $type, 'intentioncity' => $intentioncity,
					'entrytime' => $time, 'salary' => $salary2]);
			}
			else
			{
					Db::table('cv')
					->insert(['phone' => $phone, 'intentionjob' => $job, 'jobtype' => $type, 'intentioncity' => $intentioncity,
					'entrytime' => $time, 'salary' => $salary1]);
			}
		}
    }
	public function jobread()						//从数据库中读取信息
	{
		$phone = $_POST['phone'];
		$result = Db::table('cv')
				->where('phone',$phone)
				->find();
		$jsonre = json(0,'数据返回成功',1000,$result);
		echo $jsonre;
	}
	
	//导出pdf
	public function daochu()
	{
		$cv_id = session::get('cv_id');
		$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
		//$unionid = session::get('unionid');
		$html = input('post.html');
		ob_start();
		$html='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$html;
		$filename1 = time();
		$filename = strval($filename1);
		file_put_contents("/var/www/html/tp5/public/sifang/pdf/{$filename}.html", $html);
		shell_exec("wkhtmltopdf /var/www/html/tp5/public/sifang/pdf/{$filename}.html /var/www/html/tp5/public/sifang/pdf/{$filename}.pdf");
        //Clean the output buffer and turn off output buffering
        ob_end_clean();
		if(file_exists("/var/www/html/tp5/public/sifang/pdf/{$filename}.pdf")){
            header("Content-type:application/pdf");
            header("Content-Disposition:attachment;filename={$filename}.pdf");
            //echo file_get_contents("/var/www/html/tp5/public/pdf/{$filename}.pdf");
			$pdfurl = "http://www.runjianli.com/sifang/pdf/{$filename}.html";
			Db::table('cv_keep')
					->where(['cv_id'=>$cv_id, '$unionid'=>$unionid])
					->update(['pdfurl'=>$pdfurl]);
			echo "pdf/{$filename}.pdf";
            //echo "/pdf/{$filename}.pdf";
        }else{
            echo "error";
        }
	}
	public function daochu1()
	{
		//$cv_id = session::get('cv_id');
		//$cv_id = input('post.cv_id');
		$cv_id = '3';
		$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
		//$unionid = session::get('unionid');
		//$html = input('post.html');
		$html = '123';
		ob_start();
		$html='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$html;
		$filename1 = time();
		$filename = strval($filename1);
		file_put_contents("/var/www/html/tp5/public/sifang/pdf/{$filename}.html", $html);
		shell_exec("wkhtmltopdf /var/www/html/tp5/public/sifang/pdf/{$filename}.html /var/www/html/tp5/public/sifang/pdf/{$filename}.pdf");
        //Clean the output buffer and turn off output buffering
        ob_end_clean();
		if(file_exists("/var/www/html/tp5/public/sifang/pdf/{$filename}.pdf")){
            header("Content-type:application/pdf");
            header("Content-Disposition:attachment;filename={$filename}.pdf");
            //echo file_get_contents("/var/www/html/tp5/public/pdf/{$filename}.pdf");
			$pdfurl = "http://www.runjianli.com/sifang/pdf/{$filename}.pdf";
			Db::table('cv_keep')
					->where(['cv_id'=>$cv_id, '$unionid'=>$unionid])
					->update(['pdfurl'=>$pdfurl]);
			echo "pdf/{$filename}.pdf";
            //echo "/pdf/{$filename}.pdf";
        }else{
            echo "error";
        }
	}
}