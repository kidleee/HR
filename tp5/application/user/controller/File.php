<?php
namespace app\user\controller;
use think\Db;
use think\Session;

class File{
    /**收藏功能 */
    /** 收藏删除 */
    public function collection_delte(){
        $unionid=session::get('unionid');
        $modelid = input('post.modelid');//模板编号传入
        $histroy=db::table('model_collection')
                ->where('unionid',$unionid)
                ->where('modelid',$modelid)
                ->delete();
            echo "取消收藏";
    }
    /** 点击收藏*/
    public function lisen_collection (){
        $unionid=session::get('unionid');
        $modelid = input('post.modelid');//模板编号传入
		if($unionid)
		{
        $histroy=db::table('model_collection')
        ->where('unionid',$unionid)
        ->where('modelid',$modelid)
        ->find();
        if($histroy)
		{
            $histroy=db::table('model_collection')
                ->where('unionid',$unionid)
                ->where('modelid',$modelid)
                ->delete();
            echo "取消收藏";
        }
		else
		{
            db::table('model_collection')
            ->insert([
                'unionid'=>$unionid,
                'modelid'=>$modelid,
            ]);
            echo "添加收藏";
        }
		}
		else
		{
			echo "请先登陆";
		}	
    }
	/** 点击收藏新*/
    public function lisen_collection1 (){
        $unionid=session::get('unionid');
        $modelid = input('post.modelid');//模板编号传入
		if($unionid)
		{
			$histroy=db::table('model_collection')
				->where('unionid',$unionid)
				//->where('modelid',$modelid)
				->find();
			if($histroy)
			{
				$collection=db::table('model_collection')
					->where('unionid',$unionid)
					->value('collection');	
				$json = (array) json_decode($collection,true);
				if(array_key_exists($modelid,$json))
				{
					$bool = $json[$modelid];
				}
				else
				{
					$bool = "first";
				}		
				//$json = json_encode($collection);
				//$bool = $json[$modelid]	;
				if($bool == "true")
				{
					$json[$modelid] = "false";
					$collection = json_encode($json);
					db::table('model_collection')
						->where('unionid',$unionid)
						->update(['collection'=>$collection]);
					echo "取消收藏"; 
				}	
				else if($bool == "false")
				{
					$json[$modelid] = "true";
					$collection = json_encode($json);
					db::table('model_collection')
						->where('unionid',$unionid)
						->update(['collection'=>$collection]);
					echo "添加收藏";
				}
				else if($bool == "first")
				{
					$json[$modelid] = "true";
					$collection = json_encode($json);
					db::table('model_collection')
						->where('unionid',$unionid)
						->update(['collection'=>$collection]);
					echo "添加收藏";
				}	
/*
            $histroy=db::table('model_collection')
                ->where('unionid',$unionid)
                ->where('modelid',$modelid)
                ->delete();
            echo "取消收藏";*/
			}
			else
			{
				$json = [];
				$json[$modelid] = "true";
				$collection= json_encode($json);
				//$collection = json_decode($json);
				db::table('model_collection')
					->insert(['unionid'=>$unionid,'collection'=>$collection]);
				echo "添加收藏";
			}
		}
		else
		{
			echo "请先登陆";
		}	
    }
    //收藏模板显示新
    public function get_collection(){
        $unionid = session::get('unionid');
        $collection =db::table('model_collection')
            ->where('unionid',$unionid)
            ->value('collection');
		if($collection)
		{	
        //$count=count($result_one);
		$json = (array) json_decode($collection,true);
		$result = [];
		foreach($json as $key=>$value)
		{
			if($value == "true")
			{
			$result_one = db::table('model')
				->where('modelid',$key)
				->select();
			$result = array_merge($result_one,$result);
			}
		}
		echo json_encode($result);
		}
		/*
        $result=$total_fee=db::table('model')
            ->where('modelid',$result_one[0]['modelid'])
            ->select();
        for($i=1;$i<$count;$i++){
            $total_fee = db::table('model')
            ->where('modelid',$result_one[$i]['modelid'])
            ->select();
            $result=array_merge($total_fee,$result);
        }
        echo json_encode($result);
		}*/
    }
    //收藏模板图标显示新
	public function get_collection1(){
        $unionid = session::get('unionid');
        if($unionid)
	    {
		    $result = db::table('model_collection')
						->where('unionid',$unionid)
						->find();
			if($result)
			{
				echo json_encode($result);
			}
			else
			{
				echo json_encode("请先登陆");
			}	
		}
	    else
		{
			echo json_encode("请先登陆");
		}		
    }
	public function delete_collection(){
		$unionid = session::get('unionid');
		$modelid = input('post.modelid');
		$collection =db::table('model_collection')
            ->where('unionid',$unionid)
            ->value('collection');
		$json = (array) json_decode($collection,true);
		$json[$modelid]	= "fasle";
		$collection = json_encode($json);
		db::table('model_collection')
			->where('unionid',$unionid)
			->update(['collection'=>$collection]);
	}
    /**用户余额显示 */
    public function balance(){
        $unionid=session::get('unionid');
        $balance = db::table('user')
            ->where('unionid',$unionid)
            ->value('balance');
        echo $balance;
    }
    /**邀请人填写 */
    public function recommend(){
        $unionid = session::get('unionid');
        $date = date("Y-m-d");
        $recommend = input('post.recommend');
        db::table('user')
        ->where('unionid',$unionid)
        ->update(['recommend'=>$recommend,
                'recommend_date'=>$date,
        ]);
    }
    /**个人中心邀请人订单查询 */
    public  function recommend_find(){
        $unionid=session::get("unionid");
        $recommend_id = Db::table('user')
            ->where('unionid',$unionid)
            ->value('recommend_id');       
        $nickname = Db::table('user')
        ->where('recommend',$recommend_id)
        ->select();
        $status="邀请成功";
        echo json_encode($nickname);
    }
    /**个人中心订单回馈 */
    public function recommend_huikui(){
        $unionid=session::get("unionid");
        $recommend_id = Db::table('user')
            ->where('unionid',$unionid)
            ->value('recommend_id');
        $unionid_one=db::table('user')
            ->where('recommend',$recommend_id)
            ->field('unionid')
            ->select();
        $count=count($unionid_one);
        $result=$total_fee = db::table('product')
        ->where('unionid',$unionid_one[0]['unionid'])
        ->where('pay_id','支付成功')
        ->select();
        for($i=1;$i<$count;$i++){
            $total_fee = db::table('product')
            ->where('unionid',$unionid_one[$i]['unionid'])
            ->where('pay_id','支付成功')
            ->select();
            $result=array_merge($total_fee,$result);
            
        }
        echo json_encode($result);
    }
}