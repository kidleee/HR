<?php
namespace app\cert\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;

class Cert extends Controller{
    /**
     * @param 前端页面需显示以下提示语句
     * 请准备好公司员工证明
     * 个人身份证
     * 手机号
     * 认证花费二十元由第第三方公司承担
     */
    public function summary(){
        $summary = input('post.summary');
        $user_id = session::get('user_id');
        Db::table('consultant')
        ->where(['user_id'=>$user_id])
        ->update(['summary'=>$summary]);
        $this->redirect("http://www.runjianli.com/sifang/success.html");

    }
    //HR需提交文字信息
    public function cert_i(){
        $user_id = session::get('user_id');
        $result = Db::table('consultant')
                ->where('user_id',$user_id)
                ->find();
        if($result){
        $nickname = input('get.nickname');
        $career = input('get.career');
        $service_type1 = input('get.service_type/a');
        $service_type = implode(" ",$service_type1);
        $service_direction1 = input('get.service_direction/a');
        $service_direction = implode(" ",$service_direction1);
        $summary = input('get.summary');
        //真实姓名
        $name = input('get.name');
        $id_card = input ('get.id_card');
        //手机号验证
        $phone = input('get.phone');
        //邮箱
        $email = input('get.email');
        //微信
        $wx_n = input('get.wx_n');
        /*$user_id = 'abc';
        //网站展现给用户姓名
        $nickname = 'abc';
        $career = 'abc';
        //$service_type1 = input('get.service_type/a');
        $service_type = 'abc';
        //$service_direction1 = input('get.service_direction/a');
        $service_direction ='abc';
        $summary = 'abc';
        //真实姓名
        $name = 'abc';
        $id_card = 'abc';
        //手机号验证
        $phone = 'abc';*/

        $GW = array(
            'user_id' => $user_id,
            'nickname' => $nickname,
            'career' => $career,
            'service_type' => $service_type,
            'service_direction' => $service_direction,
            'summary' => $summary,
            'name' => $name,
            'ID_card' => $id_card,
            'phone' => $phone,
            'email' => $email,
            'wx_n' => $wx_n,
        );
        Db::table('consultant')
        ->where('user_id',$user_id)
        ->update($GW);
        }else{
        //网站展现给用户姓名
        $nickname = input('get.nickname');
        $career = input('get.career');
        $service_type1 = input('get.service_type/a');
        $service_type = implode(" ",$service_type1);
        $service_direction1 = input('get.service_direction/a');
        $service_direction = implode(" ",$service_direction1);
        $summary = input('get.summary');
        //真实姓名
        $name = input('get.name');
        $id_card = input ('get.id_card');
        //手机号验证
        $phone = input('get.phone');
        //邮箱
        $email = input('get.email');
        //微信
        $wx_n = input('get.wx_n');
        /*$user_id = 'abc';
        //网站展现给用户姓名
        $nickname = 'abc';
        $career = 'abc';
        //$service_type1 = input('get.service_type/a');
        $service_type = 'abc';
        //$service_direction1 = input('get.service_direction/a');
        $service_direction ='abc';
        $summary = 'abc';
        //真实姓名
        $name = 'abc';
        $id_card = 'abc';
        //手机号验证
        $phone = 'abc';*/

        $GW = array(
            'user_id' => $user_id,
            'nickname' => $nickname,
            'career' => $career,
            'service_type' => $service_type,
            'service_direction' => $service_direction,
            'summary' => $summary,
            'name' => $name,
            'ID_card' => $id_card,
            'phone' => $phone,
            'email' => $email,
            'wx_n' => $wx_n,
        );
        Db::table('consultant')->insert($GW);
        }
        
        
    }

    //HR认证提交图像信息
    public function cert_imag1(){
        $user_id = session::get('user_id');
        //个人图像信息
        $headimgur = request()->file('file');
        $info_H = $headimgur->move(ROOT_PATH . 'public' . DS . 'photo');

        $HR_headimgur = $info_H->getSaveName();
        
        Db::table('consultant')
        ->where(['user_id'=>$user_id])
        ->update(['headimgur'=>$HR_headimgur]);
        return json(0,'',1000,0);
    }
    public function cert_imag2(){
        $user_id = session::get('user_id');
        //个人身份证信息
        $ID = request()->file('file');
        $info_I = $ID->move(ROOT_PATH . 'public' . DS . 'photo');
        $HR_ID = $info_I->getSaveName();
       
        Db::table('consultant')
        ->where(['user_id'=>$user_id])
        ->update(['R_id'=>$HR_ID]);
        return json(0,'',1000,0);
    }
    public function cert_imag3(){
        //公司证明
        $user_id = session::get('user_id');
        $c_cert = request()->file('file');
        $info_C = $c_cert->move(ROOT_PATH . 'public' . DS . 'photo');
        $HR_C = $info_C->getSaveName();
        $status = '等待平台认证';
        $GW = array(
            'c_cert' => $HR_C,
            'status' => $status,
        );
        Db::table('consultant')
        ->where(['user_id'=>$user_id])
        ->update(['c_cert'=>$HR_C,'status'=>$status]);
        return json(0,'',1000,0);
    }
    /*public function cert_imag1(){
        //$user_id = session::get('user_id');
        //个人图像信息
        $headimgur = request()->file('file');
        $info_H = $headimgur->move(ROOT_PATH . 'public' . DS . 'photo');
        $HR_headimgur = $info_H->getExtension();
        return json(0,'',1000,0);

        //个人身份证信息
        $ID = request()->file('id');
        $info_I = $ID->move(ROOT_PATH . 'public' . DS . 'photo');
        $HR_ID = $info_I->getExtension();
        //公司证明
        $c_cert = request()->file('c_cert');
        $info_C = $c_cert->move(ROOT_PATH . 'public' . DS . 'photo');
        $HR_C = $info_C->getExtension();
        $status = '等待平台认证';
        $GW1 = array(
            'headimgur' => $HR_headimgur,
            'R_id' => $HR_ID,
            'c_cert' => $HR_C,
            'status' => $status,
        );
        Db::table('consult')
        ->where(['user_id'=>$user_id])
        ->insert($GW1);
    }
    */

    //HR主页
    public function HR(){
        $user_id = session::get('user_id');
        $str = 'HR/'.$user_id.'.html';
        echo $str;
    }
    //订单管理
    public function HR_order(){
        header('Content-type:text/json');
        $user_id = session::get('user_id');
        //$user_id = '5f6c3e2f4f24042e8b76251329e2b57e';
        $result = Db::table('hr_account')
                    ->where('H_user_id',$user_id)
                    ->select();
        $jsonre = json (0,'',1000,$result);
        echo $jsonre;
    }

    //改变订单认证状态
    public function cert_o(){

        header("content-type:text/html;charset=utf-8");
        //status  完成认证 您的认证材料有误，请重新提交材料
        $phone = input('post.phone');
        $status = input('post.status');
        if($status == '完成认证'){
            
        
        Db::table('consultant')
       
        ->where('phone',$phone)
        ->update(['status'=>$status]);
        $judge_HR = Db::table('consultant')
        ->where('phone',$phone)
        ->find();
        
        /**发送文件通知HR认证结果 */
        $toemail = $judge_HR['email'];
        $name = '<润简历>认证通知';
        $subject = 'HR认证订单通知';
        $content =  
        '</br>恭喜您完成认证，已成为HR    '.
        '</br>用户名:          '.$judge_HR['nickname'].
        '</br>订单号:     '.$judge_HR['out_trade_no'].
        '</br>服务类型    '.$judge_HR['service_type'].
        '</br>自身领域    '.$judge_HR['service_direction'].
        '</br>个人邮箱    '.$judge_HR['email'].
        '</br>用户手机号  '.$judge_HR['phone'];
        dump(send_email($toemail, $name, $subject, $content));
        $extend=".html";

//正式文件中处于页面隐藏获得
        $user_id = $judge_HR['user_id'];
        $hr_info = Db::table('consultant')
        ->where('user_id',$user_id)
        ->find();
        $headimgur = $hr_info['headimgur'];

        $nickname = $hr_info['nickname'];
        $career = $hr_info['career'];

        $service_type = explode(" ",$hr_info['service_type']) ;
        $type_long = count($service_type);
        $path=$nickname.$user_id.$extend;
//www.runjianli.com/sifang/HR/$path
 /*$total_fee = 0;
$balance =   Db::table('cv_model')
            ->where('body',$service_type[$i])
            ->value('total_fee');
    $total_fee = $total_fee + $balance;*/

        $service_direction = explode(" ",$hr_info['service_direction']);
        $dirction_long = count($service_direction);
        $summary = $hr_info['summary'];
//评论星级
        $evaluate = $hr_info['evaluate'];

        $user_type = 'HR';
        Db::table('user')
        ->where('user_id',$user_id)
        ->update(['usertype'=>$user_type]);




/**---开始替换---**/
//打开html模板
        $handle=fopen("/var/www/html/tp5/public/sifang/HR/model.html","rb");

//读取模板内容
        $str=fread($handle,filesize("/var/www/html/tp5/public/sifang/HR/model.html"));
        $null = NULL;
        for($i=0;$i<$type_long;$i++){
            if($service_type[$i] == '面试辅导'){
             $str=str_replace("{news_mianshi}",$service_type[$i], $str);
            }
         else if($service_type[$i] == '职业规划'){
                $str=str_replace("{news_zhiye}",$service_type[$i], $str);
            }
         else if($service_type[$i] == '简历优化'){
             $str=str_replace("{news_youhua}",$service_type[$i], $str);
         }
            else if($service_type[$i] == '简历翻译'){
                $str=str_replace("{news_fanyi}",$service_type[$i], $str);
            }
            else if($service_type[$i] == '求职信写作'){
                $str=str_replace("{news_xiezuo}",$service_type[$i], $str);
            }
         else if($service_type[$i] == '求职咨询'){
             $str=str_replace("{news_zixun}",$service_type[$i], $str);
            }
            else if($service_type[$i] == '建立排版'){
             $str=str_replace("{news_paiban}",$service_type[$i], $str);
            }
         }
        for($i=0;$i<$dirction_long;$i++){
            if($service_direction[$i] == '互联网'){
                $str=str_replace("{news_hulianwang}",$service_direction[$i], $str);
            }
            else if($service_direction[$i] == '金融'){
                $str=str_replace("{news_jinrong}",$service_direction[$i], $str);
            }
            else if($service_direction[$i] == '消费'){
                $str=str_replace("{news_xiaofei}",$service_direction[$i], $str);
            }
            else if($service_direction[$i] == '医疗'){
             $str=str_replace("{news_yiliao}",$service_direction[$i], $str);
            }
            else if($service_direction[$i] == '媒体'){
                $str=str_replace("{news_meiti}",$service_direction[$i], $str);
            }
            else if($service_direction[$i] == '建筑'){
                $str=str_replace("{news_jianzhu}",$service_direction[$i], $str);

            }
            else if($service_direction[$i] == '教育'){
             $str=str_replace("{news_jiaoyu}",$service_direction[$i], $str);
                    }
            else if($service_direction[$i] == '服务'){
                $str=str_replace("{news_fuwu}",$service_direction[$i], $str);
            }
            else if($service_direction[$i] == '运输'){
                $str=str_replace("{news_yunxu}",$service_directione[$i], $str);
            }
            else if($service_direction[$i] == '政府'){
                $str=str_replace("{news_zhengfu}",$service_direction[$i], $str);
            }
            else if($service_direction[$i] == '其他'){
                $str=str_replace("{news_qita}",$service_direction[$i], $str);
        
            }
        }



        //替换 str_replace("被替换的"，"替换成"，"在哪替换")
        //为什么在$str里替换?因为上面我们才读取的模板内容，肯定在模板里换撒
        $str=str_replace("{news_title}", $nickname, $str);
        $str=str_replace("{news_contents}",$summary,$str);
        $str=str_replace("{news_headimgur}", $headimgur, $str);
        $str=str_replace("{news_evaluate}", $evaluate,$str);
        $str=str_replace("{user_id}", $user_id,$str);
        $str=str_replace("{career}", $career,$str);
        fclose($handle);

        //把替换的内容写进生成的html文件
        $handle1=fopen("/var/www/html/tp5/public/sifang/HR/".$path,"wb");
        fwrite($handle1,$str);
        fclose($handle1);
             }else if($status == '您的认证材料有误，请重新提交材料'){
        //$user_id = input('get.user_id');
                Db::table('consultant')
        //->where('user_id',$user_id)
             ->where('phone',$phone)
             ->update(['status'=>$status]);

        
        $judge_HR = Db::table('consultant')
        ->where('phone',$phone)
        ->find();
        $toemail = $judge_HR['email'];
        $name = '<润简历>认证通知';
        $subject = 'HR认证订单通知';
        
        $content =  
        '</br>您的认证材料有误请重新提交材料'.
        '</br>用户名:          '.$judge_HR['nickname'].
        '</br>订单号:     '.$judge_HR['out_trade_no'].
        '</br>服务类型    '.$judge_HR['service_type'].
        '</br>自身领域    '.$judge_HR['service_direction'].
        '</br>个人邮箱    '.$judge_HR['email'].
        '</br>用户手机号  '.$judge_HR['phone'];
        dump(send_email($toemail, $name, $subject, $content));
        }else{
            $re_infomation = '输入错误请重新输入';
            echo $re_infomation;
        }    
}







    //HR提交证明访问路径  www.runjianli.com/photo/数据库中路径
    public function img($user_id){
        $HR = Db::table('consultant')
        ->where('user_id',$user_id)
        ->find();
        echo $HR['headimgur'];
        echo $HR['R_id'];
        echo $HR['c_cert'];
    }
    

    //用户支付成功判断
    public function cert_judge(){
        $out_trade_no = session::get('out_trade_noCert');
        $service_pay = Db::table('product')
                    ->where(['out_trade_no'=>$out_trade_no,'pay_id'=>'支付成功'])
                    ->find();
        if($service_pay){
            echo '支付成功';
        }else{
            echo '支付失败';
        }
    }
  

     //HR认证费用
     public function cert_pay(){
        //前端传入模板编号
        $model_id = '11';
        $user = Db::table('cv_model')->where('model_id',$model_id)->find();
        $body = $user['body'];
        $total_fee = $user['total_fee'];
        $product_id = $user['model_id'];
        $user_id = session::get('user_id');
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
        $pay_id = '未成功订单';
        $unionid = session::get('unionid');
        Db::table('product')
        ->insert([
            'out_trade_no'=>$order['out_trade_no'],
            'unionid'=>$unionid,
            'body'=>$body,
            'time'=>$date,
            'time2'=>$date2,
            'pay_id'=>$pay_id,
        ]);
        Db::table('consultant')
        ->where(['user_id'=>$user_id])
        ->update([
            'out_trade_no'=>$order['out_trade_no'],
            'pay' => $pay_id,
        ]);
        session::set('out_trade_noCert',$order['out_trade_no']);
        weixinpay($order);
    }



        //判断用户是否为HR
        public function cert_judge_HR(){
            $user_id = session::get('user_id');

           // $user_id = '5f6c3e2f4f24042e8b76251329e2b57e';
            $result = Db::table('consultant')
                ->where('user_id',$user_id)
                ->find();
            if($result){
                if($result['pay']=='支付成功'){
                    if($result['status']=='完成认证'){
                            $resultone = '支付成功，完成认证';                 
                            echo $resultone;                       
                    }else if($result['status']=='您的认证材料有误，请重新提交材料'){
                        $resultone = '驳回重新提交';
                        echo  $resultone;
                    }
                    else{
                        $resultone = '支付成功，等待认证';
                        echo $resultone;
                    }
                }else{
                    $resultone = '填写数据，尚未支付';
                    echo $resultone;
                }
            }else{
                $resultone = '从未认证';
                echo $resultone;
            }
        }

		public function viewweb()
		{
			header('Content-type:text/json');
			$user_id = session::get('user_id');
			echo $user_id;
		}


        /*public function refund_judge(){
            //$user_id =session::get('user_id');
            $out_trade_no='1534745029';
            $judge1 = Db::table('refund')
                    //->where('user_id',$user_id)
                    ->where('out_trade_no',$out_trade_no='1534745029')
                    ->select();
            $judge=dump($judge1);
            $long = count($judge1,0);
            echo $judge;
            echo $long;
               /* $judge_long = count($judge);
                if($judge){
    
                }
            }*/


}