<?php
namespace app\pay\controller;

use think\Controller;
use think\Db;
use think\Session;

class WxLp extends Controller{


    public function notify()
    {
        Vendor('Weixinpay.Weixinpay');
        $wxpay=new \Weixinpay();
        $result=$wxpay->notify();
        if ($result) {
            $trade_no = $result['out_trade_no'];
            Db::table('product')
            ->where(['out_trade_no'=>$trade_no])
            ->update(
            [                
                'transaction_id'=>$result['transaction_id'],
                //a代表支付成功 NULL代表支付失败
                'pay_id'=>'支付成功',
                'openid'=>$result['openid'],
                'total_fee'=>$result['total_fee'],
            ]
            );
            //推荐人机制判断
            $unionid = Db::table('product')
                    ->where('out_trade_no',$trade_no)
                    ->value('unionid');
            $recommend = Db::table('user')
                    ->where('unionid',$unionid)
                    ->value('recommend');  
            //判断是否是充值服务
            $judge_recharge = Db::table('recharge')
                            ->where(['out_trade_no'=>$trade_no])
                            ->find();        
            //判断是否是模板购买服务
            $judge_model = Db::table('model_pay')
                           ->where(['out_trade_no'=>$trade_no])
                           ->find();
            //判断是否是专属模板服务
            $judge = Db::table('model_made')
                    ->where(['out_trade_no'=>$trade_no])
                    ->find();
            //判断是否是HR认证服务
            $judge_HR = Db::table('consultant')
                    ->where(['out_trade_no'=>$trade_no])
                    ->find();
            //判断是否是用户与HR之间的服务
            $judge_Account =  Db::table('hr_account')
                            ->where(['out_trade_no'=>$trade_no])
                             ->find();
            //判断是否是企业认证服务
            $judge_company = Db::table('company_cert')
                            ->where(['out_trade_no'=>$trade_no])
                            ->find();
            //判断是否是会员支付服务
            $judge_membership = Db::table('member_ship')
                                ->where(['out_trade_no' => $trade_no])
                                ->find();
            
            if($judge){
                if($recommend){
                    $total_fee = $result['total_fee'];
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
                            'out_trade_no' => $trade_no,
                            'user_id' => $unionid,
                        );
                    Db::table('recommend')
                    ->insert($info);
                }

                Db::table('model_made')
                ->where(['out_trade_no'=>$trade_no])
                ->update(
                [
                    //a代表支付成功 NULL代表支付失败
                    'pay'=>'支付成功',
                ]
                );
                $toemail = '1484906435@qq.com';
                $name = '平台新接模板定制订单通知';
                $subject = '模板定制订单通知';
               /* $content1 =array(
                    'name'=>$judge['name'],
                    'user_id'=>$judge['user_id'],
                    'out_trade_no'=>$judge['out_trade_no'],
                    'model_class'=>$judge['model_class'],
                    'field'=>$judge['field'],
                    'demand'=>$judge['demand'],
                    'time'=>$judge['time'],
                    'date'=>$judge['date'],
                    'date2'=>$judge['date2'],
                );
                $product1=$judge['nickname'];
                Db::table('wxceshi')
                ->insert(['wxceshi'=>$product1]);*/
                $content = '用户名:   '.$judge['nickname'].
                '</br>用户user_id:    '.$judge['user_id'].
                '</br>订单号:     '.$judge['out_trade_no'].
                '</br>模板定制类型:  '.$judge['model_class'].
                '</br>领域:  '.$judge['field'].
                '</br>客户需求:  '.$judge['demand'].
                '</br>要求时限:  '.$judge['time'].
                '</br>订单年月日:  '.$judge['date'].
                '</br>订单小时:  '.$judge['date2'].
                '</br>用户WX号  '.$judge['wx_n'].
                '</br>用户手机号'.$judge['iphone'];
                dump(send_email($toemail, $name, $subject, $content));
            }
            else if($judge_HR){
                Db::table('consultant')
                ->where(['out_trade_no'=>$trade_no])
                ->update(
                [
                    //a代表支付成功 NULL代表支付失败
                    'pay'=>'支付成功',
                ]
                );
                $toemail = '1484906435@qq.com';
                $name = '<润简历>有用户提交HR认证服务申请';
                $subject = 'HR认证订单通知';
                $content = '用户名:   '.$judge_HR['nickname'].
                '</br>用户user_id:    '.$judge_HR['user_id'].
                '</br>订单号:     '.$judge_HR['out_trade_no'].
                '</br>服务类型    '.$judge_HR['service_type'].
                '</br>自身领域    '.$judge_HR['service_direction'].
                '</br>个人邮箱    '.$judge_HR['email'].
                '</br>身份证      '.$judge_HR['ID_card'].
                '</br>用户WX号  '.$judge_HR['wx_n'].
                '</br>用户手机号'.$judge_HR['phone'];
                dump(send_email($toemail, $name, $subject, $content));

            }
            else if($judge_Account){
                if($recommend){
                    $total_fee = $result['total_fee'];
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
                            'out_trade_no' => $trade_no,
                            'user_id' => $unionid,
                        );
                    Db::table('recommend')
                    ->insert($info);
                }
                Db::table('hr_account')
                ->where(['out_trade_no'=>$trade_no])
                ->update(
                [
                    //a代表支付成功 NULL代表支付失败
                    'pay'=>'支付成功',
                ]
                );
                $user_id = $judge_Account['H_user_id'];
                $toemail = Db::table('consultant')
                            ->where('user_id',$user_id)
                            ->value('email');
                $name = '<润简历>用户订单申请';
                $subject = '用户订单订单通知';
                $content = '用户名:   '.$judge_Account['name'].
                        '</br>订单号:     '.$judge_Account['out_trade_no'].
                        '</br>服务类型    '.$judge_Account['service_type'].
                        '</br>用户所在领域    '.$judge_Account['field'].
                        '</br>用户需求备注    '.$judge_Account['summary'].
                        '</br>用户WX号  '.$judge_Account['wx'].
                        '</br>用户手机号'.$judge_Account['phone'];
                dump(send_email($toemail, $name, $subject, $content));                
            }else if($judge_company){
                Db::table('company_cert')
                ->where(['out_trade_no'=>$trade_no])
                ->update(
                [
                    //a代表支付成功 NULL代表支付失败
                    'pay_id'=>'支付成功',
                ]
                );
                $user_id = $judge_company['user_id'];
                $toemail = Db::table('company_cert')
                            ->where('user_id',$user_id)
                            ->value('email');
                $name = '<润简历>企业认证订单申请通知';
                $subject = '企业订单订单通知';
                $content = '企业名:   '.$judge_company['name'].
                        '</br>订单号:     '.$judge_company['out_trade_no'].
                        '</br>客服电话    '.$judge_company['phone'].
                        '</br>公共邮箱    '.$judge_company['email'];
                dump(send_email($toemail, $name, $subject, $content));   
                 
            }else if($judge_model){
                if($recommend){
                    $total_fee = $result['total_fee'];
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
                            'out_trade_no' => $trade_no,
                            'user_id' => $unionid,
                        );
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
            }
            else if($judge_membership){
                if($recommend){
                    $total_fee = $result['total_fee'];
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
                            'out_trade_no' => $trade_no,
                            'user_id' => $unionid,
                        );
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
            } else if($judge_recharge){

                Db::table('recharge')
                ->where(['out_trade_no'=>$trade_no])
                ->update(
                [
                    //a代表支付成功 NULL代表支付失败
                    'pay_id'=>'支付成功',
                ]
                );

                $total_fee = $result['total_fee'];
                //$unionid = $judge_recharge['unionid'];
                $balance =Db::table('user')->where('unionid',$unionid)->value('balance');
                $update_balance = $total_fee+$balance;
                Db::table('user')->where('unionid',$unionid)->update(['balance'=>$update_balance]);


                
            }
            else{
                $this->modelpay();
                $product1='邮件发送失败';
               Db::table('wxceshi')
               ->insert(['wxceshi'=>$product1]);
            }
            //$this->modelpay();
            //$yonghu=$result['openid'];
            //$this->bangding($yonghu);
            /*$unionid=Session::get('unionid');
            $model_id='12345';
            Db::table('order')
            ->insert(
                [
                    'unionid'=>$unionid,
                    'model_id'=>$model_id,
                ]
            );*/
        /*$id_pay=Db::table('product')-where('out_trade_no',$result['out_trade_no'])->find();
        if($id_pay){
            $this->redirect("http://www.runjianli.com/sifang/编辑.html");
        }
        else{
            $this->redirect("http://www.runjianli.com/index.php/index/ce_shi/tree");
        }*/
           // $product1='abcd';
           /* Db::table('wxceshi')
            ->insert(['wxceshi'=>$product1]);*/
        }
        else {
            
            $product1='mmmm';
            Db::table('wxceshi')
            ->insert(['wxceshi'=>$product1]);
        }
        
    }

    /**
     * 用户订单绑定
     * 
     */
    public function bangding($yonghu){
        $unionid=Session::get('unionid');
        $product1='mmmm';
        Db::table('wxceshi')
        ->insert(['wxceshi'=>$unionid]);
        $payid=Db::table('user')
        ->field('pay_id')
        ->where('unionid',$unionid)
        ->find();
        if($payid){
            return;
        }
        else{
         Db::table('user')
        ->where('unionid',$unionid)
        ->update(['pay_id'=>$yonghu]);
        }
        
    }

    /** 
     * 解除模板限制
     * @param string $orderp 支付成功判断
     * @param string $recommend 推荐人机制判断
    */
    
    
    public function modelpay(){
        $out_trade_no=Session::get('out_trade_no');
        $unionid=Session::get('unionid');
        $orderp=Db::table('product')->where(['out_trade_no'=>$out_trade_no])->value('pay_id');
        //$total_fee=Db::table('product')->where(['unionid'=>$unionid])->value('total_fee');
        if($orderp == '支付成功'){
            $orderp1=Db::table('product')->where(['out_trade_no'=>$out_trade_no])->find();
            $total_fee=$orderp1['total_fee'];
            $balance=Db::table('user')->where(['unionid'=>$unionid])->value('balance');
            $update_balance=$total_fee+$balance;
            Db::table('user')
            ->where('unionid',$unionid)
            ->update(['balance'=>$update_balance]);
            $recommend=Db::table('user')->where(['unionid'=>$unionid])->value('recommend');
            if($recommend){
                $balance=Db::table('user')->where('user_id',$recommend)->value('balance');
                $update_balance=3*$total_fee/10+$balance;
                Db::table('user')
                ->where('user_id',$recommend)
                ->update(['balance'=>$update_balance]);
            }
            $this->redirect("http://www.runjianli.com/sifang/success.html");
        }   
        else{
            $this->redirect("http://www.runjianli.com/sifang/fail.html");
        }     

      

    }

    /**
     * 初类会员二维码生成
     */
    public function wxlp(){
        //自定义订单号
        $time= time();
        $order =[
            'body'=>'充值',//产品描述
            'total_fee'=>6.6*100,//金额
            'out_trade_no'=>strval($time),//自定义订单号
            'product_id'=>1
        ];
        weixinpay($order);
    }
    /**
     * 模板支付二维码生成
     */
    public function wxlpm(){
        //前端传入模板编号
        $model_id = '12345';
        $user = Db::table('cv_model')->where('model_id',$model_id)->find();
        $body = $user['body'];
        $total_fee = $user['total_fee'];
        $product_id = $user['model_id'];
        $unionid = session::get('unionid');
        //自定义订单号
        $time = time();
        $date = date("Y-m-d");
        $date2 = date("H:i:s");
        $order =[
            'body'=>$body,
            'total_fee'=>$total_fee,
            'out_trade_no'=>strval($time),
            'product_id'=>$product_id,
        ];
        Session::set('model_id',$user['model_id']);
        Session::set('out_trade_no',$order['out_trade_no']);
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
        //if(Session::has('out_trade_no'))
        weixinpay($order);
    }


    /**
     * 退款
     * @param $transaction_id 微信订单号
     * @param $total_fee 订单总金额，单位为分
     * @param $refund_fee 退款总金额
     * @param $out_refund_no 商户退款单号
     * @param $out_trade_no 商户订单号
     * @return array|bool|mixed
     */
    public function refund(){
        //自定义订单号
        $out_refund_no = $this->md();
        //$out_trade_no = '1534745029';
        $out_trade_no = input('get.out_trade_no');
        $trade_no =Db::table('product')
                    ->where('out_trade_no',$out_trade_no)
                    ->find();
        $user_id = session::get('user_id');
        $order = array(
            'transaction_id' =>$trade_no['transaction_id'],
            'total_fee'    =>$trade_no['total_fee'],
            'refund_fee'    =>$trade_no['total_fee'],
            'out_refund_no' =>$out_refund_no,
            'out_trade_no' =>$trade_no['out_trade_no'],
            'user_id'     =>$user_id,

        );
        $refund_on = Db::table('refund')
                    ->insert($order);
        wx_refund($trade_no['transaction_id'],$trade_no['total_fee'],$trade_no['total_fee'],$out_refund_no,$trade_no['out_trade_no']);
        $pay_id = '取消订单';
        Db::table('product')
            ->where('out_trade_no',$out_trade_no)
            ->update(['pay_id'=>$pay_id]);
    }
    //随机生成订单号
    public function md(){
        $danhao = date('Ymd') . str_pad(mt_rand(1, 99999), 10, '0', STR_PAD_LEFT);
        return $danhao;
    }


    //模板支付实际使用函数
    public function model_cert_pay($modelid){      
        //$modelid = '3';
        
        $unionid =  session::get('unionid');
        $user_id =  session::get('user_id');
        $model_info = Db::table('model')
                    ->where('modelid',$modelid)
                    ->find();
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
            'product_id'=>$modelid,
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
	public function modelviewfee($modelid)
	{
		$modelid = input('post.modelid');
        $model_info = Db::table('model')
					->where('modelid',$modelid)
					->find();
		echo json_encode($model_info);			
	}
    public function model_pay_judge(){
        $out_trade_no = session::get('out_trade_no_modelpay');
        $pay_id = Db::table('product')
                ->where('out_trade_no',$out_trade_no)
                ->value('pay_id');
        if($pay_id=='支付成功'){
            $pay = '支付成功';
            echo $pay;
        }else{
            $pay = '支付尚未成功';
            echo $pay;
        }    
    }

    


     //会员购买
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
            $total_fee = 6.6;
            $total_fee = $total_fee*100;
            $date3 = date("Y-m-d",strtotime("+3 month"));
        }else if($number == '6'){
            $body = '会员服务-6个月';
            $total_fee = 9.9;
            $total_fee = $total_fee*100;
            $date3 = date("Y-m-d",strtotime("+6 month"));
        }else if($number == '12'){
            $body = '会员服务-12个月';
            $total_fee = 12.8;
            $total_fee = $total_fee*100;
            $date3 = date("Y-m-d",strtotime("+12 month"));
        }
        else if($number == '13'){
            $body = '会员服务-终身';
            $total_fee = 20;  
            $total_fee = $total_fee*100;
            $date3 = date("Y-m-d",strtotime("+120 month"));
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
	
	public function member_ship_judge(){
        $out_trade_no = session::get('out_trade_no_membership');
        $pay_id = Db::table('product')
                ->where('out_trade_no',$out_trade_no)
                ->value('pay_id');
        if($pay_id=='支付成功'){
            $pay = '支付成功';
            echo $pay;
        }else{
            $pay = '支付尚未成功';
            echo $pay;
        } 
    }
}
