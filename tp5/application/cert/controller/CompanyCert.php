<?php
namespace app\cert\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;
/**
 * @param compaycer_info  收录公司相关信息
 * @param imag            收录图片信息
 */
class companycert extends Controller{
    //公司相关信息填写
    public function companycert_info(){
        $user_id =  session::get('user_id');
        $result = Db::table('company_cert')
                ->where('user_id',$user_id)
                ->find();
        if($result){
            $company = input('post.company');
            $name = input('post.name');
            $id_card = input('post.id_card');
            $address = input('post.address');
            $credit_code = input('post.credit_code');
            $email = input('post.email');
            $phone = input('post.phone');
            $compay_infomation = array(
            'user_id' =>$user_id,
            'company' =>$company,
            'name'=>$name,
            'id_card'=>$id_card,
            'address'=>$address,
            'credit_code'=>$credit_code,
            'email'=>$email,
            'phone'=>$phone,
            );
        Db::table('company_cert')
        ->where('user_id',$user_id)
        ->update($compay_infomation);
        echo '修改信息提交成功能';
        }else{
            $company = input('post.compay');
            $name = input('post.name');
            $id_card = input('post.id_card');
            $address = input('post.address');
            $credit_code = input('post.credit_code');
            $email = input('post.email');
            $phone = input('post.phone');
            $compay_infomation = array(
            'user_id' =>$user_id,
            'company' =>$company,
            'name'=>$name,
            'id_card'=>$id_card,
            'address'=>$address,
            'credit_code'=>$credit_code,
            'email'=>$email,
            'phone'=>$phone,
            );
        Db::table('company_cert')
        ->insert($compay_infomation);
        echo '企业信息提交成功';
        }
        
    }
    public function cert_imag1(){
        $user_id = session::get('user_id');
        //个人身份信息正面
        $headimgur = request()->file('file');
        $info_H = $headimgur->move(ROOT_PATH . 'public' . DS . 'photo');

        $HR_headimgur = $info_H->getSaveName();
        
        Db::table('company_cert')
        ->where(['user_id'=>$user_id])
        ->update(['id_card_photo'=>$HR_headimgur]);
        return json(0,'',1000,0);
    }
    public function cert_imag2(){
        $user_id = session::get('user_id');
        //个人身份证信息反面
        $ID = request()->file('file');
        $info_I = $ID->move(ROOT_PATH . 'public' . DS . 'photo');
        $HR_ID = $info_I->getSaveName();
       
        Db::table('company_cert')
        ->where(['user_id'=>$user_id])
        ->update(['id_card_photo_negative'=>$HR_ID]);
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
        Db::table('company_cert')
        ->where(['user_id'=>$user_id])
        ->update(['cert_company'=>$HR_C,'status'=>$status]);
        return json(0,'',1000,0);
    }
    //判断用户是否为企业用户
    public function cert_judge_companycert(){
        $user_id = session::get('user_id');

       // $user_id = '5f6c3e2f4f24042e8b76251329e2b57e';
        $result = Db::table('company_cert')
            ->where('user_id',$user_id)
            ->find();
        if($result){
            if($result['pay_id']=='支付成功'){
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
    //添加公司普通用户
    public function companycert_hr(){
        $user_id = input('post.user_id');
        $compaycert_HR = Db::table('user')
                    ->where('user_id',$user_id)
                    ->find();
        $compaycert_HR_o = Db::table('consultant')
                        ->where('user_id',$user_id)
                        ->find();
        $compaycert_HR_op = $compaycert_HR_o['pay'];
        $user_id_c = session::get('user_id');
        $compaycert_company = Db::table('company_cert')
                    ->where('user_id',$user_id_c)
                    ->find();
        if($compaycert_HR_o){
            if($compaycert_HR_o['pay'] == '支付成功' && $compaycert_HR_o['status']== '完成认证'){
                Db::table('consultant')
                ->where('user_id',$user_id)
                ->update([
                    'company_id'=>$user_id_c,
                ]);
                echo '成功路径';
            }else{
                $pay = '支付成功';
                $status = '您的认证材料有误，请重新提交材料';
                $out_trade_no = $compaycert_company['out_trade_no'];
                $infomation = array(
                'company_id'=>$user_id_c,
                'user_id'=>$user_id,
                'pay'=>$pay,
                'out_trade_no'=>$out_trade_no,
                'status'=>$status,
            );
            Db::table('consultant')
            ->where('user_id',$user_id)
            ->update($infomation);
            echo '驳回路径1';
            }           
        }else if($compaycert_HR){
            $pay = '支付成功';
            $status = '您的认证材料有误，请重新提交材料';
            $out_trade_no = $compaycert_company['out_trade_no'];
            $infomation = array(
                'company_id'=>$user_id_c,
                'user_id'=>$user_id,
                'pay'=>$pay,
                'out_trade_no'=>$out_trade_no,
                'status'=>$status,
            );
            Db::table('consultant')
            ->insert($infomation);
            echo '驳回路径2';
        }else{
            $fail = '输入无效id,请重新提交';
            echo $fail;
        }
    }
    //公司认证费用
    public function cert_pay(){
        //前端传入模板编号
        $model_id = '12';
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
        Db::table('company_cert')
        ->where(['user_id'=>$user_id])
        ->update([
            'out_trade_no'=>$order['out_trade_no'],
            'pay_id' => $pay_id,
        ]);

        session::set('out_trade_noCert',$order['out_trade_no']);
        weixinpay($order);
    }
    public function cert_o(){

        header("content-type:text/html;charset=utf-8");
        $status = '完成认证';
        $phone = input('post.phone');
        //$phone = '18151688687';
        //$user_id = input('get.user_id');
        Db::table('consultant')
        //->where('user_id',$user_id)
        ->where('phone',$phone)
        ->update(['status'=>$status]);
        $judge_HR = Db::table('consultant')
        ->where('phone',$phone)
        ->find();
    }
    //改变订单状态
    public function cert_companycert_op(){
        header("content-type:text/html;charset=utf-8");
        //status  完成认证 您的认证材料有误，请重新提交材料
        $phone = input('post.phone');
        $status = input('post.status');
        //$phone = '15027152963';
        //$status = "您的认证材料有误，请重新提交材料";
        if($status == '您的认证材料有误，请重新提交材料'){
        $status = '您的认证材料有误，请重新提交材料';
        
        //$user_id = input('get.user_id');
        Db::table('company_cert')
        //->where('user_id',$user_id)
        ->where('phone',$phone)
        ->update(['status'=>$status]);
      
        $judge_company = Db::table('company_cert')
        ->where('phone',$phone)
        ->find();
        $toemail = $judge_company['email'];
        $name = '<润简历>企业认证订单申请通知';
        $subject = '企业订单订单通知';
        $content = '企业名:   '.$judge_company['name'].
                        '</br>订单号:     '.$judge_company['out_trade_no'].
                        '</br>客服电话    '.$judge_company['phone'].
                        '</br>公共邮箱    '.$judge_company['email'].
                        '</br>认证通知    '.$judge_company['status'];
                send_email($toemail, $name, $subject, $content);
                $sifangshabi = '提交成功，等待用户重新提交材料';
                echo $sifangshabi;
                //echo '滚犊子去让我孙子四方帮你重新认证去吧';
        }else if($status == '完成认证'){
            $status = '完成认证';
        $phone = input('post.phone');
        //$user_id = input('get.user_id');
        Db::table('company_cert')
        //->where('user_id',$user_id)
        ->where('phone',$phone)
        ->update(['status'=>$status]);      
        $judge_company = Db::table('company_cert')
        ->where('phone',$phone)
        ->find();
        $toemail = $judge_company['email'];
        $name = '<润简历>企业认证订单申请通知';
        $subject = '企业订单订单通知';
        $content = '企业名:   '.$judge_company['name'].
                        '</br>订单号:     '.$judge_company['out_trade_no'].
                        '</br>客服电话    '.$judge_company['phone'].
                        '</br>公共邮箱    '.$judge_company['email'].
                        '</br>认证通知    '.$judge_company['status'];
                send_email($toemail, $name, $subject, $content);
                $user_type = 'enterprise';
                Db::table('user')
                    ->where('user_id',$judge_company['user_id'])
                    ->update(['usertype'=>$user_type]); 
                    $sifangshabi = '该用户已成为企业用户';
                    echo $sifangshabi;        
        }else{
            $re_infomation = '输入错误请重新输入';
            echo $re_infomation;
        }
        
    }
}