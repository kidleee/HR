<?php
namespace app\cert\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;

class Service extends Controller{
    //获取服务类型
    public function service_i(){
        $A_user_id = session::get('user_id');
        $H_user_id = input('get.H_user_id');
        $service_type1 = input('get.service_type/a');
        $service_type = implode(" ",$service_type1);
        $count1 = count($service_type);
        $total_fee = input('get.total_fee');
        /*for($x=0; $x<$count1; $x++){
            $balance = Db::table('cv_model')
                    ->where(['body' => $service_type1[$x]])
                    ->value('total_fee');
            $total_fee = $total_fee + $balance;
        }*/
        $field1 = input('get.field/a');
        $field = implode(" ",$field1);
        //个人工作经验
        $work_experience = input('get.work_experience');
        //简历字数需求
        $resume_words = input('get.resume_words');
        //完成时间要求
        $time = input('get.time');
        //语言类型
        $language = input('get.language');
        //用户真实姓名
        $name = input ('get.name');
        //用户微信号
        $wx = input('get.wx');
        //服务定制订单号
        $out_trade_no = time();
        $date = date("Y-m-d");
        $date2 = date("H:i:s");
        //备注
        $summary = input('get.summary');
        //支付状态
        $pay = '未支付订单';
        //用户手机号
        $phone= input('get.phone');
        
    /*
        //源代码测试区域
          $phone = '5416161';
          $H_user_id = 'abc';
           $A_user_id = 'aaa';
            $service_type = 'ccc';
            $total_fee = 'vas';
            $field = 'asdsa';
            $work_experience ='asdsa';
            $resume_words = 'sad';
            $time = 'dasd';
            $language = 'dasd';
            $name = 'dasd';
            $wx = 'dasda';
            $summary = 'dasdas';
            $out_trade_no = time();
            $pay = '未支付订单';
         */
        //交易日期
        $date = date("Y-m-d");
        $date2 = date("H:i:s");
        $date3 = date("Y-m_d H:i:s");
        $service = array(
            'H_user_id' => $H_user_id,
            'A_user_id' => $A_user_id,
            'service_type' => $service_type,
            'total_fee' => $total_fee,
            'field' => $field,
            'work_experience' => $work_experience,
            'resume_words' =>  $resume_words,
            'time' => $time,
            'language' => $language,
            'name' => $name,
            'wx' => $wx,
            'summary' => $summary,
            'out_trade_no' => strval($out_trade_no),
            'phone' => $phone,
            'pay'  => $pay,
            'date' => $date3,
        );
        Db::table('hr_account')
        ->insert($service);
        $body = 'HR专属定制服务';
        $order =[
            'body'=>$body,
            'total_fee'=>$total_fee,
            'out_trade_no'=>$service['out_trade_no'],
        ];
        $unionid = session::get('unionid');
        session::set('out_trade_nom',$order['out_trade_no']);
        Db::table('product')
        ->insert([
            'out_trade_no'=>$order['out_trade_no'],
            'unionid'=>$unionid,
            'body'=>$body,
            'time'=>$date,
            'time2'=>$date2,
        ]);
        echo $order['out_trade_no'];
    }


    //支付二维码
    public function service_pay($out_trade_no){
        $total_fee = Db::table('hr_account')
                    ->where('out_trade_no',$out_trade_no)
                    ->value('total_fee');
        $body = 'HR专属定制服务';
        $order =[
            'body'=>$body,
            'total_fee'=>$total_fee,
            'out_trade_no'=>$out_trade_no,
        ];

        weixinpay($order);
    }
    //判断支付是否成功
    public function service_judge_pay(){
        $out_trade_no = session::get('out_trade_nom');
        $service_pay = Db::table('product')
                    ->where(['out_trade_no'=>$out_trade_no,'pay_id'=>'支付成功'])
                    ->find();
        if($service_pay){
            //若用户支付成功则
            $information = Db::table('hr_account')
                        ->where('out_trade_no',$out_trade_no)
                        ->value('H_user_id');
            $HR = Db::table('consultant')
                        ->where('user_id',$information)
                        ->find();
                        $name = '<润简历>HR服务申请';
                        $toemail = $HR['email'];
                        $subject = '用户订单订单通知';
                        $content = 
                                '姓名'.$HR['name'].
                                'HR手机号:   '.$HR['phone'].
                                ' HR微信号:     '.$HR['wx_n'];
                        dump(send_email($toemail, $name, $subject, $content));   
            $this->redirect("http://www.runjianli.com/sifang/success.html?phone={$HR['phone']}&wx={$HR['wx_n']}");
        }else{
            //用户支付失败
            $index = '重新提交订单';
            $this->redirect("http://www.runjianli.com/sifang/fail.html");
            
        }
    }


     //用户方点确认订单完成，将相应的金额存入HR账户
     public function order_completed(){
        //$user_id = session::get('user_id');
        $user_id = '5f6c3e2f4f24042e8b76251329e2b57e';
        //$out_trade_no = input("get.out_trade_noo");
        $out_trade_no = '1534683754';
        $HR_info = Db::table('hr_account')
                    ->where(['A_user_id'=>$user_id,'out_trade_no'=>$out_trade_no])
                    ->find();
        $HR_id = $HR_info['H_user_id'];
        $total_fee = $HR_info['total_fee'];
        $balance = Db::table('user')
                ->where('user_id',$HR_id)
                ->find();
        $total_fee1 = $total_fee + $balance['balance'];
        $balance = Db::table('user')
                ->where('user_id',$HR_id)
                ->update(['balance'=>$total_fee1]);
        
    }
    
}