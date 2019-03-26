<?php
namespace app\cv\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;

class CvModel extends Controller{
    public function a(){
	//$id=$_SERVER["QUERY_STRING"];
	header("Content-type: text/html; charset=gbk");
	$url="http://www.hnntv.cn/m2o/channel/channel_info.php?channel_id=4";
	$info=file_get_contents($url);
	$json = json_decode($info,true);
	//preg_match('/"m3u8":"%"/i',$info,$m);
	$this->redirect($json[0]["m3u8"]);
	//echo $json[0]["m3u8"];
	//var_dump($json);
	//header('location:'.urldecode($m[1]));
	}
	
    //在线编辑页面打开载入函数
    public function leftcreate_infomation(){
        
		$unionid = session::get('unionid');
	    //$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
		$cv_id = session::get('cv_id');
        //$cv_id = '1';
        $div_id = input('post.div_id');
        $create_title = input('post.create_title');
        $create_content1 = input('post.create_content1');
        $create_content2 = input('post.create_content2');
        $create_content3 = input('post.create_content3');
        $create_textarea = input('post.create_textarea');
		if($unionid)
		{
		//$unionid = session::get('unionid');
        $infomation = array(
            'unionid' => $unionid,
            'cv_id' => $cv_id,
            'div_id' => $div_id,
            'create_title' => $create_title,
            'create_content1' => $create_content1,
            'create_content2' => $create_content2,
            'create_content3' => $create_content3,
            'create_textarea' => $create_textarea,
        );
		Db::table('leftcreate_keep')
            ->insert($infomation);
		}
		else
		{	
			echo '数据不会保存请登陆后进行编辑';
		}
        }
		

        public function rightcreate_infomation(){
            $unionid = session::get('unionid');
            //$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
            $cv_id = session::get('cv_id');
            //$cv_id = '1';
            $div_id = input('post.div_id');
            $create_title = input('post.create_title');
            $create_content1 = input('post.create_content1');
            $create_content2 = input('post.create_content2');
            $create_content3 = input('post.create_content3');
            $create_textarea = input('post.create_textarea');
			if($unionid)
			{
			//$unionid = session::get('unionid');
            $infomation = array(
                'unionid' => $unionid,
                'cv_id' => $cv_id,
                'div_id' => $div_id,
                'create_title' => $create_title,
                'create_content1' => $create_content1,
                'create_content2' => $create_content2,
                'create_content3' => $create_content3,
                'create_textarea' => $create_textarea,
            );
            Db::table('rightcreate_keep')
                ->insert($infomation);
            }
			else
			{
				echo '数据不会保存请登陆后进行编辑';
			}
        }

        //取出信息
        public function leftcreate_put(){
			header('Content-type:text/json');
            //$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
            $unionid = session::get('unionid');
            //$cv_id = '1';
			$cv_id = input('post.cv_id');
            //model_id
            $cv_infomation = Db::table('leftcreate_keep')
            ->where(['unionid'=>$unionid,'cv_id'=>$cv_id])
            ->select();
            echo json_encode($cv_infomation);
        }

        public function rightcreate_put(){
			header('Content-type:text/json');
            //$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
            $unionid = session::get('unionid');
            //$cv_id = session::get('cv_id');
			$cv_id = input('post.cv_id');
            //$cv_id = '1';
            $cv_infomation = Db::table('rightcreate_keep')
            ->where(['unionid'=>$unionid,'cv_id'=>$cv_id])
            ->select();
            echo json_encode($cv_infomation);
        }
        //加载一个页面
        public function cv_keep(){
            $unionid = session::get('unionid');
			
			Db::table('cv_keep')
				->where('cv_title', null)
				->delete();
			//$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
			if($unionid)
			{	
				$inform = Db::table('cv_keep')
					->where('unionid',$unionid)
                    ->find();
				if($inform)
				{	
					$count = Db::table('cv_keep')
					->where('unionid',$unionid)
                    ->count();
					if($count<=10)
					{
						$max = Db::table('cv_keep')
						->where('unionid',$unionid)
						->max('cv_id');
						$cv_id = $max +1;
						session::set('cv_id',$cv_id);
						Db::table('cv_keep')
						->insert([
							'cv_id'=>$cv_id,
							'unionid'=>$unionid,
						]);
						Db::table('cv')
						->insert([
							'cv_id'=>$cv_id,
							'unionid'=>$unionid,
						]);
						echo '简历模板加载完成';
					}
					else
					{
						echo '您的模板储存已达上限，请删除后在进行添加';
					}	
				}
				else
				{
					$cv_id = 1;
					session::set('cv_id',$cv_id);
					Db::table('cv_keep')
						->insert([
							'cv_id'=>$cv_id,
							'unionid'=>$unionid,
						]);
					Db::table('cv')
						->insert([
							'cv_id'=>$cv_id,
							'unionid'=>$unionid,
						]);	
					/*Db::table('cv')
					->insert(['model_id',$model_id]);*/
					echo '简历模板加载完成';
				}			
			}
			else
			{
				echo '数据不会保存请登陆后进行编辑';
			}		
        }
            //存储div排序
        public function cv_keep_div(){
            $unionid = session::get('unionid');
			if($unionid)
			{	
            //$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
            //$cv_id = '1';
            $cv_id =  session::get('cv_id');
            $new_leftorder = input('post.new_leftorder');
			$new_rightorder = input('post.new_rightorder');
            /*
			$new_leftorder = '123';
			$new_rightorder = '123';
			$old_leftorder = '123';
			$old_rightorder = '123';*/
            $old_leftorder = input('post.old_leftorder');
			$old_rightorder = input('post.old_rightorder');
			$date = date('y-m-d');
            //$old_order = '456';
			if($unionid)
			{	
            Db::table('cv_keep')
            ->where(['cv_id'=>$cv_id,'unionid'=>$unionid])
            ->update([
				'date' => $date,
                'new_leftorder' => $new_leftorder,
                'new_rightorder' => $new_rightorder,
				'old_leftorder' => $old_leftorder,
				'old_rightorder' => $old_rightorder,
            ]);
			}
			}
			else
			{
				echo "请先登陆";
			}	
        }
		          //存储div排序
        public function cv_keep_div1(){
            $unionid = session::get('unionid');
            //$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
            //$cv_id = '1';
            //$cv_id =  session::get('cv_id');
			if($unionid)
			{	
			$cv_id = input('post.cv_id');
            $new_leftorder = input('post.new_leftorder');
			$new_rightorder = input('post.new_rightorder');
            /*
			$new_leftorder = '123';
			$new_rightorder = '123';
			$old_leftorder = '123';
			$old_rightorder = '123';*/
            $old_leftorder = input('post.old_leftorder');
			$old_rightorder = input('post.old_rightorder');
			$date = date('y-m-d');
            //$old_order = '456';
			if($unionid)
			{	
            Db::table('cv_keep')
            ->where(['cv_id'=>$cv_id,'unionid'=>$unionid])
            ->update([
				'date' => $date,
                'new_leftorder' => $new_leftorder,
                'new_rightorder' => $new_rightorder,
				'old_leftorder' => $old_leftorder,
				'old_rightorder' => $old_rightorder,
            ]);
			}
			}
			else
			{
				echo "请先登陆";
			}	
        }
        //取出div排序
        public function cv_keep_div_out(){
			header('Content-type:text/json');
            $unionid = session::get('unionid');
            //$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
            $cv_id = input('post.cv_id');
            //$cv_id =  session::get('cv_id');
            //$new_order = input('post.new_order');
            //$new_order = '123';
            //$old_order = input('post.old_order');
            //$old_order = '456';
            $cv_infomation = Db::table('cv_keep')
            ->where(['unionid'=>$unionid,'cv_id'=>$cv_id])
            ->find();
            echo json_encode($cv_infomation);
        }
		//个人所有编辑过的简历
		public function cv_personal()
		{
			header('Content-type:text/json');
			//$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
			$unionid = session::get('unionid');
			$result = Db::table('cv_keep')
			->where('unionid',$unionid)
			->select();
			echo json_encode($result);
		}
		//简历导出
		public function cv_input1()
		{
		//$cv_id = session::get('cv_id');
		$cv_id = input('post.cv_id');
		//$cv_id = '3';
		//$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
		$unionid = session::get('unionid');
		$html = input('post.html');
		//$html = '123';
		ob_start();
		$html='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="layui-v2.2.6/layui-v2.2.6/layui/css/layui.css" type="text/css">
		<link href="LXXUploadPic-master/LXXUploadNeeded/LXXUploadPic.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="mycss.css" type="text/css">'.$html;
		$filename1 = time();
		$filename = strval($filename1);
		file_put_contents("/var/www/html/tp5/public/sifang/pdf/{$filename}.html", $html);
		shell_exec("wkhtmltopdf /var/www/html/tp5/public/sifang/pdf/{$filename}.html /var/www/html/tp5/public/sifang/pdf/{$filename}.pdf");
        //Clean the output buffer and turn off output buffering
        ob_end_clean();
		if(file_exists("/var/www/html/tp5/public/sifang/pdf/{$filename}.pdf")){
            //header("Content-type:application/pdf");
            //header("Content-Disposition:attachment;filename={$filename}.pdf");
            //echo file_get_contents("/var/www/html/tp5/public/pdf/{$filename}.pdf");
			$pdfurl = "http://www.runjianli.com/sifang/pdf/{$filename}.pdf";
			Db::table('cv_keep')
					->where(['cv_id'=>$cv_id,'unionid'=>$unionid])
					->update(['pdfurl'=>$pdfurl,'inputinnerhtml'=>$html]);
			session::set('cv_id',$cv_id );		
			//echo "pdf/{$filename}.pdf";
            //echo "/pdf/{$filename}.pdf";
        }else{
            echo "error";
        }
		}
		public function cv_input()
		{
		$cv_id = session::get('cv_id');
		//$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
		//$cv_id = '3';
		$unionid = session::get('unionid');
		if($unionid)
		{	
		$html = input('post.html');
		//$modelcss = input('post.modelcss');
		//$html = '123';
		ob_start();
		$html="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
		<link rel=\"stylesheet\" href=\"../css/style.css\" type=\"text/css\">".$html;
		$filename1 = time();
		$filename = strval($filename1);
		file_put_contents("/var/www/html/tp5/public/sifang/pdf/{$filename}.html", $html);
		shell_exec("wkhtmltopdf --disable-smart-shrinking --margin-left 0mm --margin-right 0mm --margin-top 0mm --margin-bottom 0mm /var/www/html/tp5/public/sifang/pdf/{$filename}.html /var/www/html/tp5/public/sifang/pdf/{$filename}.pdf");
        //Clean the output buffer and turn off output buffering
        ob_end_clean();
		if(file_exists("/var/www/html/tp5/public/sifang/pdf/{$filename}.pdf")){
            //header("Content-type:application/pdf");
            //header("Content-Disposition:attachment;filename={$filename}.pdf");
            //echo file_get_contents("/var/www/html/tp5/public/pdf/{$filename}.pdf");
			$pdfurl = "http://www.runjianli.com/sifang/pdf/{$filename}.pdf";
			Db::table('cv_keep')
					->where(['cv_id'=>$cv_id,'unionid'=>$unionid])
					->update(['pdfurl'=>$pdfurl,'innerhtml'=>$html, 'changesign'=>'0']);
			//echo "http://www.runjianli.com/sifang/pdf/{$filename}.pdf";
            //echo "/pdf/{$filename}.pdf";
        }else{
            echo "error";
        }
		}
		else
		{
			echo "请先登陆";
		}	
		}
		public function view_input()
		{
			$cv_id = session::get('cv_id');
			//$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
			$unionid = session::get('unionid');
			$pdfurl = Db::table('cv_keep')
					->where(['cv_id'=>$cv_id,'unionid'=>$unionid])
					->value('pdfurl');
			echo $pdfurl;		
		}
		//存入innerhtml//存入分块json
		public function innerhtml_input()
		{
			$innerhtml = input('post.innerhtml');
			$jsonhtml = input('post.jsonhtml');
			$modelid = input('post.modelid');
			$showorhidehtml = input('post.showorhidehtml');
			$slidewordhtml = input('post.slidewordhtml');
			$extrahtml = input('post.extrahtml');
			$title = input('post.title');
			session::set('modelid',$modelid);
			$cv_id = session::get('cv_id');
			$unionid = session::get('unionid');
			if($unionid)
			{	
			Db::table('cv_keep')
				->where(['cv_id'=>$cv_id,'unionid'=>$unionid])
				->update(['innerhtml'=>$innerhtml, 'modelid'=>$modelid, 'jsonhtml'=>$jsonhtml, 'showorhidehtml'=>$showorhidehtml, 'slidewordhtml'=>$slidewordhtml, 'cv_title'=>$title, 'extrahtml'=>$extrahtml, 'changesign'=>'0']);
			echo $cv_id;	
			}
			else
			{
				echo "请先登陆";
			}	
		}
		public function innerhtml_input1()
		{
			$innerhtml = input('post.innerhtml');
			$modelid = input('post.modelid');
			$cv_id = input('post.cv_id');
			$unionid = session::get('unionid');
			Db::table('cv_keep')
				->where(['cv_id'=>$cv_id,'unionid'=>$unionid])
				->update(['innerhtml'=>$innerhtml]);
		}
		
		//读取innerhtml
		public function innerhtml_read()
		{
			header('Content-type:text/json');
            $unionid = session::get('unionid');
            //$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
            $cv_id = input('post.cv_id');
            session::set('cv_id',$cv_id);
            //$new_order = input('post.new_order');
            //$new_order = '123';
            //$old_order = input('post.old_order');
            //$old_order = '456';
            $cv_infomation = Db::table('cv_keep')
            ->where(['unionid'=>$unionid,'cv_id'=>$cv_id])
            ->find();
            echo json_encode($cv_infomation);
		}
		public function jsonhtml_read()
		{
			header('Content-type:text/json');
            $unionid = session::get('unionid');
            //$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
            $cv_id = session::get('cv_id');
            //$new_order = input('post.new_order');
            //$new_order = '123';
            //$old_order = input('post.old_order');
            //$old_order = '456';
            $cv_infomation = Db::table('cv_keep')
            ->where(['unionid'=>$unionid,'cv_id'=>$cv_id])
            ->find();
            echo json_encode($cv_infomation);
		}
		public function inputinnerhtml_read()
		{
			header('Content-type:text/json');
            $unionid = session::get('unionid');
            //$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
            $cv_id = session::get('cv_id');
            //$cv_id =  session::get('cv_id');
            //$new_order = input('post.new_order');
            //$new_order = '123';
            //$old_order = input('post.old_order');
            //$old_order = '456';
            $cv_infomation = Db::table('cv_keep')
            ->where(['unionid'=>$unionid,'cv_id'=>$cv_id])
            ->find();
            echo json_encode($cv_infomation);
		}
		public function viewmodelcss()
		{
			header('Content-type:text/json');
            $unionid = session::get('unionid');
            //$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
            $cv_id = input('post.cv_id');
            session::set('cv_id',$cv_id);
            //$new_order = input('post.new_order');
            //$new_order = '123';
            //$old_order = input('post.old_order');
            //$old_order = '456';
            $modelid = Db::table('cv_keep')
            ->where(['unionid'=>$unionid,'cv_id'=>$cv_id])
            ->value('modelid');
			$cv_infomation = Db::table('model_change')
            ->where('modelid', $modelid)
            ->find();
            echo json_encode($cv_infomation);
		}
		
    public function headimg()
    {
		$unionid = session::get('unionid');
		$cv_id =  session::get('cv_id');
		if($unionid)
		{
            $headimg1 = request()->file('headimg');
			$infop = $headimg1->move(ROOT_PATH . 'public' . DS . 'sifang/cvview/picture');
            $headimg = $infop->getSaveName();
            $headimg = "http://www.runjianli.com/sifang/cvview/picture/$headimg";
            Db::table('cv_keep')
			->where(['unionid'=>$unionid,'cv_id'=>$cv_id])
            ->update(['headimg' => $headimg]);    
            echo $headimg;
        }
		else
		{
			$headimg1 = request()->file('headimg');
			$infop = $headimg1->move(ROOT_PATH . 'public' . DS . 'sifang/cvview/picture');
            $headimg = $infop->getSaveName();
            $headimg = "http://www.runjianli.com/sifang/cvview/picture/$headimg";
			echo $headimg;
		}	
    }
	
	public function getcvid()
	{
		$cv_id = session::get("cv_id");
		$unionid = session::get("unionid");
	    Db::table('cv_keep')
			->where(['unionid'=>$unionid,'cv_id'=>$cv_id])
            ->update(['changesign' => '1']);    
		echo $cv_id;
	}
}