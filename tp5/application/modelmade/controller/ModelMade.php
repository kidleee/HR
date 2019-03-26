<?php
namespace app\modelmade\controller;

use think\Db;
use think\Session;
use think\Controller;

class ModelMade extends Controller{
    
    
    /**
     * @param 用户需求写入
     * @param 定制模板订单生成
     * model_made 属性
     * user_id 用户唯一标识
     * out_
     */
    public function made(){
        $user_id= session::get('user_id');
        //查询条件由前端传入 比如where('model_id','1')
        //$model_id = '1';
        $model_id = input('get.model_id');
        $user=Db::table('cv_model')->where('model_id',$model_id)->find();
        $model_class=$user['body'];
        //模板定制订单号
        $out_trade_no = time();
        $date = date("Y-m-d");
        $date2 = date("H:i:s");
        //用户WX里联系方式
        //$wx_n = 'q14849066435';
        $wx_n = input('get.wx_n');
        //用户手机号联系方式
        $iphone = input('get.iphone');
        //由前端传入数据
        $field = input('get.field');  
        $demand = input('get.demand');
        $time = input('get.time');
        /*$field = '计算机';
        $demand = '请资深老师完成';
        $time = '二到三日完成';*/
        $nickname = session::get('nickname');
        $pay = '未完成订单';
        $model_made =[
            'user_id' => $user_id,
            'out_trade_no' => strval($out_trade_no),
            'model_class' => $model_class,
            'field' => $field,
            'demand' => $demand,
            'date' => $date,
            'date2' => $date2,
            'time' => $time,
            'nickname' => $nickname,
            'wx_n' => $wx_n,
            'iphone' => $iphone,
            'pay' => $pay,
            
        ];
        $out_trade_noz = $model_made['out_trade_no'];
        Db::table('model_made')
        ->insert($model_made);
        //定制模板支付
        //$this->made_pay($model_made['out_trade_no']);
        echo  $out_trade_noz;
    }

    
    
    /**
     * @param 定制模板服务支付
     */
    public function made_pay($out_trade_no){
        //前端传入选择编号 
        /**
         * 三种定制服务 初级模板定制
         *             中级模板定制
         *             专业模板定制
         * 前端需传回数字1、2、3 与表中产品所对应
         */
        //$model_id = session::get('model_id');
        $body1 = Db::table('model_made')->where('out_trade_no',$out_trade_no)->value('model_class');
        $user = Db::table('cv_model')->where('body',$body1)->find();
        $body = $user['body'];
        $total_fee = $user['total_fee'];
        $product_id = $user['model_id'];
        $unionid = session::get('unionid');
        //自定义订单号
        //$time = time();
        $date = date("Y-m-d");
        $date2 = date("H:i:s");
        $pay = '未完成订单';
        $order =[
            'body'=>$body,
            'total_fee'=>$total_fee,
            'out_trade_no'=>$out_trade_no,
            'product_id'=>$product_id,
        ];
        Session::set('model_id',$user['model_id']);
        Session::set('out_trade_no',$order['out_trade_no']);
        Db::table('product')
        ->insert([
            'out_trade_no'=>$order['out_trade_no'],
            'unionid'=>$unionid,
            'body'=>$body,
            'time'=>$date,
            'time2'=>$date2,
            'pay_id' => $pay,
        ]);
        //if(Session::has('out_trade_no'))
        weixinpay($order);
    }
    
    
    /**
     * 后台定制模板订单显示
     * user_id 用户唯一标示
     * out_trade_no 订单号
     * model_class  服务类型
     * field       自身领域
     * demand      用户需求简述
     * date        订单时间
     * time        用户要求订单完成时长
     * nickname    用户名
     */
    //查询所有模板定制订单
    public function made_trade(){
        header('Content-type:text/json');
        $result = Db::table('model_made')
                    ->select();
        $jsonre = json (0,'',1000,$result);
        echo $jsonre;
        
    }
    
    
    //根据用户user_id查询
    public function user_id_made_trade(){
        header('Content-type:text/json');
        //后台输入用户user_id
        $user_id = '5f6c3e2f4f24042e8b76251329e2b57e';
        // $user_id = input('post.user_id');
        $result = Db::table('model_made')
                    ->where('user_id',$user_id)
                    ->select();
        $jsonre = json (0,'',1000,$result);
        echo $jsonre;
    }
    //根据订单号查询
    public function out_trade(){
        header('Content-type:text/json');
        //后台输入用户user_id
        $out_trade_no = input('post.out_trade_no');
        $result = Db::table('model_made')
                    ->where('out_trade_no',$out_trade_no)
                    ->select();
        echo json_encode($result);
    }
}