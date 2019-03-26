<?php
namespace app\cv\controller;

use think\Session;
use think\Db;
use think\Controller;
use think\Request;
use think\Image;


Class Model extends Controller{
    public function CreModel()
    {
        $id = input('post.modelid');
        $name = input('post.name');
        $designer = input('post.designer');
        $size = input('post.size');
        $color = input('post.color');
        $language = input('post.language');
        $format = input('post.format');
        $price = input('post.price');
		$class = input('post.class');
		$brief = input('post.brief');
        $checkid = Db::table('model')
                    ->where('modelid', $id)
                    ->find();
        $checkname = Db::table('model')
                    ->where('name', $name)
                    ->find();
        $cdate = date('y-m-d');
        if($checkid)
        {
            return 0;
        }
        else if($checkname)
        {
            return '该简历名称已存在！';
        }
        else{
            $file = request()->file('imgurl');
            $image = Image::open($file);
             $image->thumb(230,325, Image::THUMB_CENTER);
            $saveName = time() . '.png';
            $infop=$image->save(ROOT_PATH . 'public/sifang/cvview/picture/' . $saveName);
            $imgurl = "http://www.runjianli.com/sifang/cvview/picture/$saveName";
            // $picture = request()->file('imgurl');
            $model = request()->file('modeldownload');
            $css = request()->file('cssstyle');
            // $infop = $picture->move(ROOT_PATH . 'public' . DS . 'sifang/cvview/picture');
            $infom = $model->move(ROOT_PATH . 'public' . DS . 'sifang/cvview/model');
            $infoc = $css->move(ROOT_PATH . 'public' . DS . 'sifang/cvview/css');
            // $imgurl = $infop->getSaveName();
            // $imgurl = "http://www.runjianli.com/sifang/cvview/picture/$imgurl";
            $modeldownload = $infom->getSaveName();
            $modeldownload = "http://www.runjianli.com/sifang/cvview/model/$modeldownload";
            $cssstyle = $infoc->getSaveName();
            $cssstyle = "http://www.runjianli.com/sifang/cvview/css/$cssstyle";
            $durl = $infom->getSaveName();
            $durl = ROOT_PATH . 'public' . DS . 'sifang/cvview/model/' . $durl;
            $fname = $infom->getSaveName();
            //
            header('content-type:text/html;charset=utf-8');
            $extend = '.html';
            $path='cv_'.$id.$extend;
            $handle=fopen('/var/www/html/tp5/public/sifang/cvview/model1.html','rb');
            $str=fread($handle,filesize('/var/www/html/tp5/public/sifang/cvview/model1.html'));
            $notail = "http://www.runjianli.com/sifang/cvview/cv_$id.html";
            $str=str_replace('{name}', $name, $str);
            $str=str_replace('{modelid}', $id, $str);
            $str=str_replace('{size}', $size, $str);
            $str=str_replace('{color}', $color, $str);
            $str=str_replace('{language}', $language, $str);
            $str=str_replace('{format}', $format, $str);
            $str=str_replace('{price}', $price, $str);
            $str=str_replace('{notailsweb}', $notail, $str);
            $str=str_replace('{imgurl}', $imgurl, $str);
			$str=str_replace('{cssstyle}', $cssstyle, $str);
			$str=str_replace('{brief}', $brief, $str);
            $str=str_replace('{modeldownload}', $modeldownload, $str);
            $str=str_replace('{designer}', $designer, $str);
            $str=str_replace('{cdate}', $cdate, $str);
            fclose($handle);
            $handle1=fopen('/var/www/html/tp5/public/sifang/cvview/'.$path,'wb');
            fwrite($handle1,$str);
            fclose($handle1);
            Db::table('model')
            ->insert(['modelid' => $id, 'name' => $name, 'designer' => $designer, 'size' => $size, 
                    'color' => $color,'language' => $language, 'cdate' => $cdate, 'format' => $format, 
                    'price' => $price, 'notailsweb' => $notail, 'imgurl' => $imgurl, 'modeldownload' => $modeldownload, 
                    'durl' => $durl, 'cssstyle' => $cssstyle, 'fname' => $fname, 'class' => $class, 'brief'=>$brief]);
                    
            return 1;
        }
    }
	public function CreChangeModel()
    {
        $id = input('post.modelid');
        $name = input('post.name');
		$designer = input('post.designer');
        $checkid = Db::table('model')
                    ->where('modelid', $id)
                    ->find();
        $checkname = Db::table('model')
                    ->where('name', $name)
                    ->find();
        if($checkid)
        {
            return 0;
        }
        else if($checkname)
        {
            return '该简历名称已存在！';
        }
        else{
            $picture = request()->file('imgurl');
            $css = request()->file('cssstyle');
            $infop = $picture->move(ROOT_PATH . 'public' . DS . 'sifang/cvview/picture');
            $infoc = $css->move(ROOT_PATH . 'public' . DS . 'sifang/cvview/css');
            $imgurl = $infop->getSaveName();
            $imgurl = "http://www.runjianli.com/sifang/cvview/picture/$imgurl";
            $cssstyle = $infoc->getSaveName();
            $cssstyle = "http://www.runjianli.com/sifang/cvview/css/$cssstyle";
            Db::table('model_change')
            ->insert(['modelid' => $id, 'name' => $name, 'designer' => $designer, 'imgurl' => $imgurl, 'cssstyle' => $cssstyle]);    
            return 1;
        }
    }
    public function GetModel()      //后台浏览使用
    {
        header('Content-type:text/json');
        $result = Db::table('model')
                    ->select();
        $jsonre = json(0,'',1000,$result);
        echo $jsonre;
    }
	public function GetChangeModel()      //后台浏览使用
    {
        header('Content-type:text/json');
        $result = Db::table('model_change')
                    ->select();
        $jsonre = json(0,'',1000,$result);
        echo $jsonre;
    }
	public function GetChangeModel1()      //后台浏览使用
    {
        header('Content-type:text/json');
        $result = Db::table('model_change')
					->where('modelid','s10003')
                    ->find();			
		session::set('model_id','s10003');			
        echo json_encode($result);
    }
	public function GetModel1()         //预览界面使用
    {
        header('Content-type:text/json');
        $result = Db::table('model')
                    ->select();
        echo json_encode($result);
    }
	public function GetModel2()         //最热门简历
    {
        header('Content-type:text/json');
        $result = Db::table('model')
					->order('usedtimes desc')
					->select();
        echo json_encode($result);
    }
    public function GetWordModel()      //得到Word模板
    {
        header('Content-type:text/json');
        $result = Db::table('model')
					->where('format','Word')
					->select();
        echo json_encode($result);
    }
    public function GetPPTModel()       //得到PPT模板
    {
        header('Content-type:text/json');
        $result = Db::table('model')
					->where('format','PPT')
					->select();
        echo json_encode($result);
    }
    public function MembershipCheck()       //判断用户是否为会员
    {
        $user_id = Session::get('user_id');
        $ms = Db::table('user')
                    ->where('user_id',$user_id)
                    ->value('membership');
        if($ms == '是')
        {
            echo '确认会员';
        }
        else{
            echo '不是会员';
        }
    }
    public function Membertime()
    {
        $uid = Session::get('unionid');
        $ms = Db::table('user')
                    ->where('unionid',$uid)
                    ->value('membership');
        if($ms == '是')
        {
            $mst = Db::table('user')
                    ->where('unionid',$uid)
                    ->value('membershiptime');
            echo "会员到期时间：$mst";
        }
        else if($ms == '否')
        {
            echo '快来成为会员吧！';
        }
    }
    public function BuyCheck($mid)          //判断用户是否成功购买模板
    {
        $uid = Session::get('unionid');
        $state = Db::table('model_buy')
                ->where('unionid',$uid)
                ->where('modelid',$mid)
                ->find();
        if($state)
        {
            echo '已购买模板';
        }
        else
        {
            echo '未购买模板';
        }
    }
    public function DownLoadTimes($mid)  //下载次数传入
    {
        //$mid = input('post.modelid');
        $ut = Db::table('model')
                ->where('modelid',$mid)
                ->value('usedtimes');
        echo $ut;
    }
    public function DownLoadPlus() //下载次数增加
    {
        $mid = input('post.modelid');
        $ut = Db::table('model')
                ->where('modelid',$mid)
                ->value('usedtimes');
        $ut = $ut + 1;
        Db::table('model')
        ->where('modelid',$mid)
        ->update(['usedtimes' => $ut]);
    }

    public function DownLoad($mid)              //下载文件整体判断和下载
    {
        $user_id = Session::get('user_id');
        $uid = Session::get('unionid');
        //$user_id = 'db1e8b84811eb03113e230609240d350';
        //$uid = 'o4FYM1LGhoB7L4Jh77G3Adelbw2A';
        //$mid = 'sf0012';
        $file = Db::table('model')
                ->where('modelid',$mid)
                ->value('modeldownload');
        $name = Db::table('model')
                ->where('modelid',$mid)
                ->value('name');
        if($uid)                                //判断用户是否登录
        {
            $ms = Db::table('user')
                    ->where('user_id',$user_id)
                    ->value('membership');
            if($ms == '是')                      //判断用户是否是会员，是则直接下载，不是会员则进入下一层判断
            {
                echo '开始下载';
            }
            else{                           //不是会员的话判断是否购买过模板
                $state = Db::table('model_buy')
                            ->where('unionid',$uid)
                            ->where('modelid',$mid)
                            ->find();
                if($state)     //如果购买过，直接下载
                {
                    echo '开始下载';               
                }
                else{
                    echo '您未购买此模板。';
                }
            }                      
        }
        else
        {
            echo '您还未进行登录！';
        }
    }
    public function Down($mid)
    {
        $user_id = Session::get('user_id');
        $uid = Session::get('unionid');
        //$user_id = 'db1e8b84811eb03113e230609240d350';
        //$uid = 'o4FYM1LGhoB7L4Jh77G3Adelbw2A';
        //$mid = 'sf0012';
        $file = Db::table('model')
                ->where('modelid',$mid)
                ->value('modeldownload');
		$name = Db::table('model')
                ->where('modelid',$mid)
                ->value('name');		
        //$name = input('post.name');
        if($uid)                                //判断用户是否登录
        {
			$ms = Db::table('user')
                    ->where('user_id',$user_id)
                    ->value('membership');
            if($ms == '是')                      //判断用户是否是会员，是则直接下载，不是会员则进入下一层判断
            {
                $ut = Db::table('model')
				->where('modelid',$mid)
				->value('usedtimes');
				$ut = $ut + 1;
				Db::table('model')
				->where('modelid',$mid)
				->update(['usedtimes' => $ut]);			
				$this->RealDownLoad($mid);
            }
            else{                           //不是会员的话判断是否购买过模板
                $state = Db::table('model_buy')
                            ->where('unionid',$uid)
                            ->where('modelid',$mid)
                            ->find();
                if($state)     //如果购买过，直接下载
                {
                    $ut = Db::table('model')
					->where('modelid',$mid)
					->value('usedtimes');
					$ut = $ut + 1;
					Db::table('model')
					->where('modelid',$mid)
					->update(['usedtimes' => $ut]);			
					$this->RealDownLoad($mid);               
                }
                else{
                    echo '您未购买此模板。';
                }
            }	
        }
        else
        {
            echo '您还未进行登录！';
        }
    }
    public function RealDownLoad($mid)
    {
        $file = Db::table('model')
                ->where('modelid',$mid)
                ->value('durl');
        $fname = Db::table('model')
                ->where('modelid',$mid)
                ->value('fname');
        if(file_exists($file))
        {
            $file1 = fopen($file, "r");

            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length:".filesize($file));
            Header("Content-Disposition: attachment;filename=" . $fname);
            ob_clean();
            flush();
            echo fread($file1, filesize($file));
            fclose($file1);
            exit();
        }
        else{
            $this->error('文件未找到！');
        }
    }
	//判断是否购买简历
	public function judge_buy()
	{
		$uid = session::get("unionid");
		$modelid = input('post.modelid');
		$result = Db::table('model_buy')
                ->where('unionid',$uid)
                ->where('modelid',$modelid)
                ->find();
		echo json_encode($result);		
	}
	public function judge_buy1($mid)
	{
		$user_id = Session::get('user_id');
        $uid = Session::get('unionid');
        //$user_id = 'db1e8b84811eb03113e230609240d350';
        //$uid = 'o4FYM1LGhoB7L4Jh77G3Adelbw2A';
        //$mid = 'sf0012';
        $file = Db::table('model')
                ->where('modelid',$mid)
                ->value('modeldownload');
		$name = Db::table('model')
                ->where('modelid',$mid)
                ->value('name');		
        //$name = input('post.name');
        if($uid)                                //判断用户是否登录
        {
			$ms = Db::table('user')
                    ->where('user_id',$user_id)
                    ->value('membership');
            if($ms == '是')                      //判断用户是否是会员，是则直接下载，不是会员则进入下一层判断
            {
                echo '您是会员可以直接下载此模板';
            }
            else{                           //不是会员的话判断是否购买过模板
                $state = Db::table('model_buy')
                            ->where('unionid',$uid)
                            ->where('modelid',$mid)
                            ->find();
                if($state)     //如果购买过，直接下载
                {
                    echo '您已购买此模板';               
                }
                else{
                    echo '您未购买此模板。';
                }
            }	
        }
        else
        {
            echo '您还未进行登录！';
        }
	}
}