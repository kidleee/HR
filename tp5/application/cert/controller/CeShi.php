<?php
namespace app\cert\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;

class CeShi
{
    public function cv_keep_infomation(){
        //$unionid = session::get('unionid');
        $unionid = 'o4FYM1LGhoB7L4Jh77G3Adelbw2A';
        $cv_id = '1';
        $div_id = input('post.div_id');
        $create_title = input('post.create_title');
        $create_content1 = input('post.create_content1');
        $create_content2 = input('post.create_content2');
        $create_content3 = input('post.create_content3');
        $create_textarea = input('post.create_textarea');
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
        Db::table('create_keep')
            ->insert($infomation);
        }
    public function cv_keep_put(){
        $unionid = 'o4FYM1LGhoB7L4Jh77G3Adelbw2A';
        $cv_id = '1';
        $cv_infomation = Db::table('create_keep')
        ->where(['unionid'=>$unionid,'cv_id'=>$cv_id])
        ->select();
        echo json_encode($cv_infomation);
    }
    public function ceshi(){
        $recommend = '5f6c3e2f4f24042e8b76251329e2b57e';
        $result['total_fee'] = '1';
        $total_fee = '1';
        $trade_no = '123456';
        $balance=Db::table('user')->where('user_id',$recommend)->value('balance');
        $update_balance=3*$total_fee/10+$balance;
        Db::table('user')
        ->where('user_id',$recommend)
        ->update(['balance'=>$update_balance]);
        //
        $body = '推荐人回馈';
        $total_fee =  3*$total_fee/10;
        $info = array(
                'body' =>$body,
                'total_fee' =>$result['total_fee'],
                'out_trade_no' => $trade_no,
                'user_id' => $recommend,
            );

        Db::table('recommend')
        ->insert($info);
    }
    public function random() {
        echo date('dis');
        }
    //会员购买
    public function member_ship(){
        //$unionid = session::get('unionid');
        $unionid = '123456';
        $user_id = '6789';
        //$user_id = session::get('user_id');
        $date = date("Y-m-d");
        $date2 = date("H:i:s");
       

        $pay_id = '未成功订单';
        $time = time();
        $body = '会员服务';
        //$number = input('get.number');
        $number = '3';
        if($number = '3'){
            $total_fee = '20';
            $date3 = date("Y-m-d",strtotime("+3 month"));
        }else if($number = '6'){
            $total_fee = '55';
            $date3 = date("Y-m-d",strtotime("+6 month"));
        }else{
            $total_fee = '100';
            $date3 = date("Y-m-d",strtotime("+12 month"));
        }
        $model_id = '9865';
        $order =[
            'body'=>$body,
            'total_fee'=>$total_fee,
            'out_trade_no'=>strval($time),
            'product_id'=>$model_id,
        ];
        Db::table('product')
        ->insert([
        'out_trade_no'=>$order['out_trade_no'],
        'unionid'=>$unionid,
        'body'=>$body,
        'time'=>$date,
        'time2'=>$date2,
        'pay_id'=>$pay_id,
    ]);
    $order1 =[
        'user_id'=>$user_id,
        'body'=>$body,
        'total_fee'=>$total_fee,
        'out_trade_no'=>$order['out_trade_no'],
        'date' =>$date3,
    ];
    Db::table('member_ship')
        ->insert($order1);
    weixinpay($order);
    }
}
