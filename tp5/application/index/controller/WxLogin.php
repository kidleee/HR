<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
define('appID',		"");
define('appsecret',	"");


class WxLogin extends Controller{
 public function index(){
     
        $url= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $this->User($url);
        }
 public function User($callback){
     //传入参数为需要授权登录地址
        $recommend_id = date('dis').rand(101,1000) ;
        $get = input('param.');
        if($get){//这里判断是否有get数据 如果有说明是登录授权回调 用code获取用户openid
            $appid = appID;
            $appsecret =  appsecret;
            if(isset($get['code'])){
                $code=$get['code'];
                $urlp="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
                $res = $this->https_request($urlp);//通过code获取openid
                $userinfo= json_decode($res, JSON_UNESCAPED_UNICODE);
                 session('openid',$userinfo['openid']);
                if($userinfo['scope']=='snsapi_base'){
                    $user=model('user')->where('openid',session('openid'))->find();
                    if(!isset($user['is_auth'])){
                        session(null);
                        $this->redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$callback&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
                    }else{
                        $mistdata=json_decode($user['nickname']);
                        $user['namep']=urldecode($mistdata);
                        $this->is_auth=$user['is_auth'];
                        $this->openid=$user['openid'];
                        $this->user=$user;
                        $this->assign('user',$user);
                        return true;
                    }       
                }else{
                    $token=$userinfo['access_token'];
                    $apenid=$userinfo['openid'];
                    $urln="https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$apenid&lang=zh_CN";

                    $ress = $this->https_request($urln);//用openid获取用户资料
                    $username= json_decode($ress, JSON_UNESCAPED_UNICODE);
                    //$dataname=urlencode($username['nickname']);
                    //$namedata=json_encode($dataname);
                    $close=$username['openid'];
                    $open=$this->Uer_id($close);
                    $balance = '0';
                    $userdata=array(
                       'user_id'=>$open,
                       'openid'=>$username['openid'],
                       'nickname'=>$username['nickname'],
                       'sex'=>$username['sex'],
                       'city'=>$username['city'],
                       'province'=>$username['province'],
                       'country'=>$username['country'],
                       'headimgurl'=>$username['headimgurl'],
                       'unionid'=>$username['unionid'],
                       'recommend_id' =>$recommend_id,
                       'balance'=>$balance,
                       
                       );
                    $login=Db::table('user')->where('openid',$username['openid'])->find();
                    
                    if($login){
                        
                        session::set('unionid',$username['unionid']);
                        session::set('headimgurl',$username['headimgurl']);
                        session::set('nickname',$username['nickname']);
                        session::set('user_id',$open);
                        if($login['usertype'] == 'ordinary'){
                            $this->redirect("http://www.runjianli.com");
                        }if($login['usertype'] == 'HR'){
                            $status = Db::table('consultant')
                                    ->where('user_id',$login['user_id'])
                                    ->value('company_status');
                                if($status){
                                    //如果HR是公司添加需要认证的
                                    $this->redirect("http://www.runjianli.com");
                                    
                                }else{
                                    //HR是正常走HR流程
                                    $this->redirect("$callback");
                                }
                            $this->redirect("$callback");
                        }if($login['usertype'] == 'administrator'){
                            $this->redirect("$callback");
                        }if($login['usertype'] == 'enterprise'){
                            $this->redirect("$callback");
                        }
                        
                    }
                    else{
                       $userstatus=model('user')->allowField(true)->insert($userdata);//将数据写入到数据库
                       Db::table('user_info')
                       ->insert(['unionid' =>$username['unionid']]);
                        session::set('unionid',$username['unionid']);
                        session::set('headimgurl',$username['headimgurl']);
                        session::set('nickname',$username['nickname']); 
                        session::set('user_id',$open);
                                               
                        $this->redirect("http://www.runjianli.com");
                        
                    }
                   
                    return true;
                }
            }else{//如果get数据没有code参数 发起base方式授权登录获取CODE
                $this->redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid='appid'&redirect_uri=$callback&response_type=code&scope=snsapi_base&state=2#wechat_redirect"); 
            }
        }
        //如果没有get数据说明是后台调用方法 发起base方式授权登录获取CODE
        $this->redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx207fa66c97a2db5c&redirect_uri=$callback&response_type=code&scope=snsapi_base&state=2#wechat_redirect");
    }



public  function https_request($url, $data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if(!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
public function Uer_id($user_one){
$user_can=md5($user_one);
return $user_can;

}
}