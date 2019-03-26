<?php
namespace app\user\controller;

use think\Db;
use think\Session;

class TradeRun{
    /**
     * 内置订单查询
     * out_trade_no  商户订单号
     * total_fee     支付金额
     * body          产品描述
     * time          时间年月日
     * time2         时间24小时制
        
     */
    //查看所有订单信息
    public function viewtrade(){
        header('Content-type:text/json');
        $result = Db::table('product')
                    ->select();
        $jsonre = json (0,'',1000,$result);
        echo $jsonre;
    }
    //根据用户unionid查询订单记录
    public function unionidtrade(){
        header('Content-type:text/json');
        //后台输入unionid进行查询
        //$unionid = 'o4FYM1KYHxTQ29IZt4RqAmDMv7W0';
        $unionid = input('post.unionid');
        $result = Db::table('product')
            ->where('unionid',$unionid)
            ->select();
        echo json_encode($result);
    }
    //用户查询自己定制模板服务的信息
    public function model_made_trade(){
        header('Content-type:text/json');
        $user_id = session::get('user_id');
        $result = Db::table('model_made')
        ->where('user_id',$user_id)
        ->select();
        //此语句方便前端查看输出数组，正式运行进行注释
        //echo dump($result);
        echo json_encode($result);
    }
    //用户自己查询自己的订单信息
    public function product_trade(){
        header('Content-type:text/json');
        $user_id = session::get('user_id');
        $unionid = session::get('unionid');
        $result = Db::table('product')
                ->where('unionid',$unionid)
                ->select();
        //此语句方便前端查看输出数组，正式运行进行注释
        //echo dump($result);
        $jsonre = json (0,'',1000,$result);
        echo $jsonre;
        //多表联合查询
        /*$result=Db::field('product.out_trade_no as pout_trade_no,product.time as ptime,product.*,model_made.*')
        ->table(['product'=>'product','model_made'=>'model_made'])
        ->where(['product.unionid'=>$unionid,'model_made.user_id'=>$user_id])
        ->select();
        
        echo dump($result);
        echo json_encode($result);*/
        
       
    }
	 public function product_trade1(){
        header('Content-type:text/json');
        $user_id = session::get('user_id');
        $unionid = session::get('unionid');
        $result = Db::table('product')
                ->where('unionid',$unionid)
				->order('time desc')
                ->select();
        //此语句方便前端查看输出数组，正式运行进行注释
        //echo dump($result);
        echo json_encode($result);
        //多表联合查询
        /*$result=Db::field('product.out_trade_no as pout_trade_no,product.time as ptime,product.*,model_made.*')
        ->table(['product'=>'product','model_made'=>'model_made'])
        ->where(['product.unionid'=>$unionid,'model_made.user_id'=>$user_id])
        ->select();
        
        echo dump($result);
        echo json_encode($result);*/
        
       
    }
    //根据时间查询订单记录
    public function timetrade(){
        //后台输入日期进行查询
        header('Content-type:text/json');
        //$time = '2018-8-10';
        $time = input('post.time');
        $result = Db::table('product')
                ->where('time',$time)
                ->select();
        echo json_encode($result);
    }

}