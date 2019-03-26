<?php
namespace app\user\controller;

use think\Db;
use think\Session;

class UserInfo
{
       //前台获取退款基本信息
       public function refund_get(){
        $out_trade_no = input('post.out_trade_no');
        //$out_trade_no = '1535008003';
        //$summary = 'sadsad';
        $summary = input('post.summary');
        $user_id = session::get('user_id');
        $status = '申请退款中耐心等待';
        $judge =Db::table('product')
        ->where('out_trade_no',$out_trade_no)
        ->find();
        $total_fee = $judge['total_fee'];
        if($judge){
            Db::table('product')
            ->where('out_trade_no',$out_trade_no)
            ->update(['pay_id'=>$status]);
        $Re =  array(
            'out_trade_no' =>$out_trade_no,
            'summary'=>$summary,
            'status'=>$status,
            'user_id'=>$user_id,
            'total_fee'=>$toatl_fee,
        );
        Db::table('refund')
        ->insert($Re);
        
        echo $status;
        }else {
            $fail = '无效订单号';
            echo  $fail;
        }
        

    }
    //用户退款
    public function refund(){
        //自定义订单号
        $out_refund_no = $this->md();
        //$out_trade_no = '1534745029';
        $out_trade_no = input('post.out_trade_no');
        $trade_no =Db::table('product')
                    ->where('out_trade_no',$out_trade_no)
                    ->find();
        $user_id = session::get('user_id');
        $order = array(
            'transaction_id' =>$trade_no['transaction_id'],
            'total_fee'    =>$trade_no['total_fee'],
            'refund_fee'    =>$trade_no['total_fee'],
            'out_refund_no' =>$out_refund_no,
            'user_id'     =>$user_id,

        );
        $refund_on = Db::table('refund')
                    ->where('out_trade_no',$out_trade_no)
                    ->update($order);
        wx_refund($trade_no['transaction_id'],$trade_no['total_fee'],$trade_no['total_fee'],$out_refund_no,$trade_no['out_trade_no']);
        $pay_id = '退款完成';
        Db::table('product')
            ->where('out_trade_no',$out_trade_no)
            ->update(['pay_id'=>$pay_id]);
        Db::table('refund')
            ->where('out_trade_no',$out_trade_no)
            ->delete();
    }
    //随机生成订单号
    public function md(){
        $danhao = date('Ymd') . str_pad(mt_rand(1, 99999), 10, '0', STR_PAD_LEFT);
        return $danhao;
    }
    public function refund_judge(){
        $user_id =session::get('user_id');
        //$user_id ='db1e8b84811eb03113e230609240d350';
        $judgeone = Db::table('refund')
                ->where('user_id',$user_id)
                //->where('out_trade_no',$out_trade_no='1534745029')
                ->select();
        //$judge = dump($judgeone);
        if($judgeone){
        $judge_long = count($judgeone,0);
        $status ='申请退款中耐心等待';
        for($i=0;$i<$judge_long;$i++)
           {
               if($judgeone[$i]['user_id'] == $user_id && $judgeone[$i]['status'] == $status){
                $fail = '有订单正在申请退款';
                //break;

            }else {
                $fail='并无正在申请退款中订单';                
            }
           }
        echo $fail;
        }
        else{
            echo '并无正在申请退款中订单';
        }
            
    }


    //推荐人机制
    public function recommend(){
        $user_id = session::get('user_id');
        $recommend_id = Db::table('user')
                    ->where('user_id',$user_id)
                    ->value('recommend_id');
        echo $recommend_id;
    }
    public function recommend_judge(){
        $user_id = session::get('user_id');
        $judge = Db::table('user')
            ->where('user_id',$user_id)
            ->value('recommend');
        if($judge){
            echo  1;
        }else{
            echo 0;
        }
    }
    public function recommend_judge_repeat(){
        $recommd = input('post.recommend');
        $user_id = session::get('user_id');
        $recommend_id = Db::table('user')
                    ->where('user_id',$user_id)
                    ->value('recommend_id');
        $info = Db::table('user')
            ->where('recommend_id',$recommd)
            ->find();
        if($recommd == $recommend_id){
            $fail = '自身id为无效id，请重新填写';
            echo $fail;
        }else if($info){
			$date = date("Y-m-d");
            $success = '验证成功';
            $info = Db::table('user')
            ->where('user_id',$user_id)
            ->update(['recommend'=>$recommd,
                'recommend_date'=>$date]);
            echo $success;           
        }else{
            $fail = '无效邀请码，请核对后填写';
            echo $fail;

        }
    }
	public function addphone(){
		$user_id = session::get('user_id');
		$phone = input('post.iphone');
		$info = Db::table('user')
            ->where('user_id',$user_id)
            ->update(['phone'=>$phone]);
	}	
    //查看推荐人回馈信息
    public function recommend_judge_call(){
        $user_id = session::get('user_id');
        $result = Db::table('recommend')
            ->where('user_id',$user_id)
            ->select();
            $jsonre = json (0,'',1000,$result);
            echo $jsonre;
    }


    //
    public function check_user()
    {
        if(Session::has('unionid'))
        {
            $uid = Session::get('unionid');
            $res = Db::table('user_info')
                    ->where('unionid',$uid)
                    ->find();
            if($res)
            {
                echo '已存在该条记录';
            }
            else{
                Db::table('user_info')
                ->insert(['unionid' => $unionid]);
            }
        }
        else{
            echo '您还未进行登录！';
        }
    }
    public function submit()
    {
        $uid = Session::get('unionid');
        $name = input('post.name');
        $sex = input('post.sex');
        $city = input('post.city');
        $introduction = input('post.introduction');
        $result = Db::table('user_info')
                    ->where('unionid', $uid)
                    ->update(['name' => $name, 'sex' => $sex, 'city' => $city, 'introduction' => $introduction]);
    }
    public function getinfo()
    {
        header('Content-type:text/json');
        $uid = Session::get('unionid');
        if($uid)
        {
		    $result = Db::table('user_info')
				    ->where('unionid',$uid)
				    ->find();
            echo json_encode($result);
        }
        else{
            echo "您还未进行登录！";
        }
    }
	
	public function getip()
	{
		$reIP=$_SERVER["REMOTE_ADDR"];
		$ch = curl_init();
		$str ="http://ip.taobao.com/service/getIpInfo.php?ip=$reIP";
		curl_setopt($ch, CURLOPT_URL, $str);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true );
		$output = curl_exec($ch);	
		echo $output;
	}
	
	public function putphone()
	{
		header('Content-type:text/json');
        $uid = Session::get('unionid');
		$phone = input('post.phone');
        if($uid)
        {
		    Db::table('user')
				->where('unionid',$uid)
				->update(['phone'=>$phone]);
		}		
	}
	public function getbaseinformation()
	{   
		header('Content-type:text/json');
		$uid = Session::get('unionid');
		$result = Db::table('user')
                    ->where('unionid', $uid)
                    ->find();
		echo json_encode($result);
    }
	
	public function getmodelbuy()
	{   
		header('Content-type:text/json');
		$uid = Session::get('unionid');
		$result = Db::table('model_buy')
                    ->where('unionid', $uid)
                    ->select();
		echo json_encode($result);
    }
	public function getmodelbuy1()
	{   
		header('Content-type:text/json');
		$modelid = input('post.modelid');
		//$uid = Session::get('unionid');
		$result = Db::table('model')
                    ->where('modelid', $modelid)
                    ->find();
		echo json_encode($result);
    }
    //设置后台管理
    public function set_backstage(){
        header('Content-type:text/json');
        $user_id = input('post.user_id');
        $result = Db::table('user')
                ->where('user_id',$user_id)
                ->select();
        echo json_encode($result);
    }
    public function set_backstagep(){
        header('Content-type:text/json');
        $usertype = 'backstage';
        $user_id = input('post.user_id');
        $result = Db::table('user')
                ->where('user_id',$user_id)
                ->update('usertype',$usertype);
        echo json_encode($result);
    }
	//客服反馈
	public function service()
	{
		$content1 = input('post.content');
		$phone = input('post.phone');
		$toemail = '314236788@qq.com';
        $name = '<润简历>反馈通知';
        $subject = '用户反馈通知';
        $content =  
        '用户电话：'.$phone.
        '</br>用户反馈内容：</br>  '.$content1;
        dump(send_email($toemail, $name, $subject, $content));
	}
}