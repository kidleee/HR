<?php
namespace app\pay\controller;
use think\Db;
use think\Session;
use think\controller;

class Pay extends Controller{

    //账户余额充值
    public function recharge($number){
        $unionid = session::get('unionid');
        $user_id = session::get('user_id');
        $date = date("Y-m-d");
        $date2 = date("H:i:s");
        $pay_id = '未成功订单';
        $time = time();
        
        $body = '充值服务';
        if($number == '10'){
            $body = '润简历充值10';
            $total_fee = '1000';
           
        }else if($number == '20'){
            $body = '润简历充值20';
            $total_fee = '2000';
          
        }else if($number == '100'){
            $body = '润简历充值100';
            $total_fee = '10000';
            
        }else{
            
            $body = '润简历充值'.$number;
            $number = $number*100;
            $total_fee = $number;
        }
        $model_id = '0001';
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
            'pay_id' => $pay_id,
            'unionid'=>$unionid,
            'time'=>$date,
            'time2'=>$date2,
        ];
        Db::table('recharge')
            ->insert($order1);
        weixinpay($order);
    }
    
    //使用账户余额进行会员支付
    public function pay_member($number){
        $unionid = session::get('unionid');
        $user_id = session::get('user_id');
        $date = date("Y-m-d");
        $date2 = date("H:i:s");
        $pay_id = '未成功订单';
        $time = time();
        $body = '会员服务';
        if($number == '3'){
            $body = '会员服务-3个月';
            $total_fee = '18';
            $date3 = date("Y-m-d",strtotime("+3 month"));
        }else if($number == '6'){
            $body = '会员服务-6个月';
            $total_fee = '36';
            $date3 = date("Y-m-d",strtotime("+6 month"));
        }else if($number == '12'){
            $body = '会员服务-12个月';
            $total_fee = '1';
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
            'pay_id' => $pay_id,
        ];
        Db::table('member_ship')
            ->insert($order1);
        session::set('out_trade_no_membership',$order['out_trade_no']);
        $trade_no=$order['out_trade_no'];
        if($balance>=$total_fee){
            $balance = $balance-$total_fee;
            Db::table('user')
                ->where('unionid',$unionid)
                ->update('balance',$balance);
            $recommend = Db::table('user')
                ->where('unionid',$unionid)
                ->value('recommend');
            if($recommend){
                $balance=Db::table('user')->where('recommend_id',$recommend)->value('balance');
                $update_balance=3*$total_fee/10+$balance;
                Db::table('user')
                ->where('recommend_id',$recommend)
                ->update(['balance'=>$update_balance]);
                $body = '推荐人回馈';
                $total_fee =  3*$total_fee/10;
                $info = array(
                        'body' =>$body,
                        'total_fee' =>$total_fee,
                        'out_trade_no' => $order['out_trade_no'],
                        'user_id' => $unionid,
                    );
                $trade_no =$order['out_trade_no'];
                Db::table('recommend')
                ->insert($info);
                }
                Db::table('member_ship')
                ->where(['out_trade_no'=>$trade_no])
                ->update(
                [
                    //a代表支付成功 NULL代表支付失败
                    'pay_id'=>'支付成功',
                ]
                );
                $abc = '是';
                $unionid = Db::table('product')
                    ->where('out_trade_no',$trade_no)
                    ->value('unionid');
                Db::table('user')
                ->where('unionid',$unionid)
                ->update(['membership'=>$abc,]);
                $bd = Db::table('product')
                    ->where('out_trade_no',$trade_no)
                    ->value('body');
                if($bd == '会员服务-3个月')
                {
                    $mt = date("Y-m-d",strtotime("+3 month"));
                }
                else if($bd == '会员服务-6个月')
                {
                    $mt = date("Y-m-d",strtotime("+6 month"));
                }
                else if($bd == '会员服务-12个月')
                {
                    $mt = date("Y-m-d",strtotime("+12 month"));
                }
                Db::table('user')
                ->where('unionid',$unionid)
                ->update(['membershiptime'=>$mt,]);
        }else{
            $out ="账户余额不足，请选用其他充值方式";
        }	
        
    }
    
    //使用账户余额进行模板支付
    public function pay($model_id){
        $unionid = session::get('unionid');
        $balance = Db::table('user')
            ->where('unionid',$unionid)
            ->value('balance');
        $mode_info = Db::table('model')
            ->where('modelid',$model_id)
            ->find();
        if($model_info)	
            {		
            //自定义订单号
            $pay_id = '未成功订单';
            $time = time();
            $date = date("Y-m-d");
            $date2 = date("H:i:s");
            $body = $model_info['name'];
            $total_fee = $model_info['price'];
            $order = [
                'body'=>$body,
                'total_fee'=>$total_fee,
                'out_trade_no'=>strval($time),
                'product_id'=>$model_id,
            ];
            
            $order1 =[
                'user_id'=>$user_id,
                'body'=>$body,
                'total_fee'=>$total_fee,
                'out_trade_no'=>$order['out_trade_no'],
                'pay_id'=>$pay_id,
            ];
            Db::table('model_pay')
            ->insert($order1);
            $pay_id = '未成功订单';
            Db::table('product')
            ->insert([
            'out_trade_no'=>$order['out_trade_no'],
            'unionid'=>$unionid,
            'body'=>$body,
            'time'=>$date,
            'time2'=>$date2,
            'pay_id'=>$pay_id,
            ]);
            session::set('out_trade_no_modelpay',$order['out_trade_no']);
            if($balance>=$total_fee){
                $balance = $balance-$total_fee;
                Db::table('user')
                    ->where('unionid',$unionid)
                    ->update('balance',$balance);
                $recommend = Db::table('user')
                    ->where('unionid',$unionid)
                    ->value('recommend');
                if($recommend){
                    $balance=Db::table('user')->where('recommend_id',$recommend)->value('balance');
                    $update_balance=3*$total_fee/10+$balance;
                    Db::table('user')
                    ->where('recommend_id',$recommend)
                    ->update(['balance'=>$update_balance]);
                    $body = '推荐人回馈';
                    $total_fee =  3*$total_fee/10;
                    $info = array(
                            'body' =>$body,
                            'total_fee' =>$total_fee,
                            'out_trade_no' => $order['out_trade_no'],
                            'user_id' => $unionid,
                        );
                    $trade_no =$order['out_trade_no'];
                    Db::table('recommend')
                    ->insert($info);
                    }
                    Db::table('model_pay')
                    ->where(['out_trade_no'=>$trade_no])
                    ->update(
                    [
                        //a代表支付成功 NULL代表支付失败
                        'pay_id'=>'支付成功',
                    ]
                    );
                    $uid = Db::table('product')
                            ->where('out_trade_no',$trade_no)
                            ->value('unionid');
                    $bodyn = Db::table('product')
                            ->where('out_trade_no',$trade_no)
                            ->value('body');
                    $modelid = Db::table('model')
                            ->where('name',$bodyn)
                            ->value('modelid');
                    Db::table('model_buy')
                    ->insert(['unionid' => $uid, 'modelid' => $modelid]);

            }else{
                $out ="账户余额不足，请选用其他充值方式";
            }
            }
                
    }
    //模板支付函数
    public function model_pay($model_id){
        $unionid = session::get('unionid');
        $user_id = session::get('user_id');
        $model_info = Db::table('model')
                    ->where('modelid',$model_id)
                    ->find();
        $balance = Db::table('user')
            ->where('unionid',$unionid)
            ->value('balance');
        
        if($model_info)	
        {		
        //自定义订单号
        $pay_id = '未成功订单';
        $time = time();
        $date = date("Y-m-d");
        $date2 = date("H:i:s");
        $body = $model_info['name'];
        $total_fee = 100*$model_info['price'];
        $order = [
            'body'=>$body,
            'total_fee'=>$total_fee,
            'out_trade_no'=>strval($time),
            'product_id'=>$model_id,
        ];
        
        $order1 =[
            'user_id'=>$user_id,
            'body'=>$body,
            'total_fee'=>$total_fee,
            'out_trade_no'=>$order['out_trade_no'],
            'pay_id'=>$pay_id,
        ];
        Db::table('model_pay')
        ->insert($order1);
        $pay_id = '未成功订单';
        Db::table('product')
        ->insert([
        'out_trade_no'=>$order['out_trade_no'],
        'unionid'=>$unionid,
        'body'=>$body,
        'time'=>$date,
        'time2'=>$date2,
        'pay_id'=>$pay_id,
        ]);
        session::set('out_trade_no_modelpay',$order['out_trade_no']);
        weixinpay($order);
        }
        else
        {
            echo 404;
        }            
    }

    /**会员服务 */
    public function member_ship($number){
        $unionid = session::get('unionid');
        $user_id = session::get('user_id');
        $date = date("Y-m-d");
        $date2 = date("H:i:s");
        $pay_id = '未成功订单';
        $time = time();
        $body = '会员服务';
        if($number == '3'){
            $body = '会员服务-3个月';
            $total_fee = '18';
            $date3 = date("Y-m-d",strtotime("+3 month"));
        }else if($number == '6'){
            $body = '会员服务-6个月';
            $total_fee = '36';
            $date3 = date("Y-m-d",strtotime("+6 month"));
        }else if($number == '12'){
            $body = '会员服务-12个月';
            $total_fee = '1';
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
            'pay_id' => $pay_id,
        ];
        Db::table('member_ship')
            ->insert($order1);
		session::set('out_trade_no_membership',$order['out_trade_no']);	
        weixinpay($order);
    }
}