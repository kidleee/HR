<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Session;
/**
 * curl 请求http
 */
function curl_get_contents($url)
{
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                //设置访问的url地址
    // curl_setopt($ch,CURLOPT_HEADER,1);               //是否显示头部信息
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);               //设置超时
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);   //用户访问代理 User-Agent
    curl_setopt($ch, CURLOPT_REFERER,$_SERVER['HTTP_HOST']);        //设置 referer
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);          //跟踪301
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果
    $r=curl_exec($ch);
    curl_close($ch);
    return $r;
}

/**
 * 登录拦截
 */
function web(){
    $unionid = session::has('unionid');
    if($unionid){
        return ;
    }else{
        $this->redirect("http://www.runjianli.com/sifang/login.html");
    }
}

//返回json数组
function json($code,$msg="",$count,$data=array())
{
	header('Content-type:text/json');
    $result=array(
     'code'=>$code,
     'msg'=>$msg,
     'count'=>$count,
     'data'=>$data
    );
    //输出json
    echo json_encode($result);
    exit;
}

/**
 * 多重md5加密法
 */
function dmd5($secret)
{
    return md5(md5($secret).md5($secret));
}
//随机生成长度为len的含大小写字母和数字的乱码
function GetRandomString($len, $chars=null)  
{  
    if (is_null($chars)) {  
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
    }  
    mt_srand(10000000*(double)microtime());  
    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++) {  
        $str .= $chars[mt_rand(0, $lc)];  
    }  
    return $str;  
}  


/**
 * 生成二维码
 * @param  string  $url  url连接
 * @param  integer $size 尺寸 纯数字
 * 第一个参数$text；就是上面代码里的URL网址参数；
 * 第二个参数$outfile默认为否；不生成文件；只将二维码图片返回；否则需要给出存放生成二维码图片的路径；
*第三个参数$level默认为L；这个参数可传递的值分别是L(QR_ECLEVEL_L，7%)、M(QR_ECLEVEL_M，15%)、Q(QR_ECLEVEL_Q，25%)、H(QR_ECLEVEL_H，30%)；这个参数控制二维码容错率；不同的参数表示二维码可被覆盖的区域百分比。利用二维维码的容错率；我们可以将头像放置在生成的二维码图片任何区域；
*第四个参数$size；控制生成图片的大小；默认为4；
*第五个参数$margin；控制生成二维码的空白区域大小；
*第六个参数$saveandprint；保存二维码图片并显示出来；$outfile必须传递图片路径；
**第七个参数$back_color；背景颜色；
*第八个参数$fore_color；绘制二维码的颜色；
*note：第七、第八个参数需要传16进制是色值；并且要把“#”替换为“0x”
*举个例子：
*白色：#FFFFFF => 0xFFFFFF
*黑色：#000000 => 0x000000
 */
function qrcode($url,$size=8){
    header("Content-type:text/html;charset=utf-8");
    Vendor('Phpqrcode.phpqrcode');
    $url1='/var/www/html/tp5/public/sifang/pay';
    QRcode::png($url,false,QR_ECLEVEL_L,$size,2,false,0xFFFFFF,0x000000);
    exit();
}


/**
 * 微信扫码支付
 * @param  array $order 订单 必须包含支付所需要的参数 body(产品描述)、total_fee(订单金额)、out_trade_no(订单号)、product_id(产品id)
 */
function weixinpay($order){
    $order['trade_type']='NATIVE';
    Vendor('Weixinpay.Weixinpay');
    $weixinpay=new \Weixinpay();
    $weixinpay->pay($order);
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
function wx_refund($transaction_id1, $total_fee1, $refund_fee1, $out_refund_no1, $out_trade_no1){
    Vendor('Weixinpay.Weixinpay');
    $weixinpay=new \Weixinpay();
    $transaction_id = $transaction_id1;
    $total_fee = $total_fee1;
    $refund_fee = $refund_fee1;
    $out_refund_no = $out_refund_no1;
    $out_trade_no = $out_trade_no1;
    $weixinpay->refund($transaction_id, $total_fee, $refund_fee, $out_refund_no, $out_trade_no);
    
}


/**
 * 邮件提醒
 * @param string $tomail 接收邮件者邮箱
 * @param string $name 接收邮件者名称
 * @param string $subject 邮件主题
 * @param string $body 邮件内容
 * @param string $attachment 附件列表
 */
function send_email($tomail, $name, $subject = '', $body = '', $attachment = null){
        Vendor('PHPMailer.PHPMailer');
        //$mail = new \phpmailer();
        $mail = new \PHPMailer();           //实例化PHPMailer对象
        $mail->CharSet = 'UTF-8';           //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->IsSMTP();                    // 设定使用SMTP服务
        $mail->SMTPDebug = 0;               // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
        $mail->SMTPAuth = true;             // 启用 SMTP 验证功能
        $mail->SMTPSecure = 'ssl';          // 使用安全协议
        $mail->Host = "smtp.qq.com"; // SMTP 服务器
        $mail->Port = 465;                  // SMTP服务器的端口号
        $mail->Username = "1484906435@qq.com";    // SMTP服务器用户名
        $mail->Password = "qrxhojvjuwaojaei";     // SMTP服务器密码
        $mail->SetFrom('1484906435@qq.com', 'M');
        $replyEmail = '';                   //留空则为发件人EMAIL
        $replyName = '';                    //回复名称（留空则为发件人名称）
        $mail->AddReplyTo($replyEmail, $replyName);
        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        $mail->AddAddress($tomail, $name);
    if (is_array($attachment)) { // 添加附件
        foreach ($attachment as $file) {
            is_file($file) && $mail->AddAttachment($file);
        }
    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}


