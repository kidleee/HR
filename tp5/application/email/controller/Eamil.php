<?php
namespace app\eamil\controller;

use think\Db;
use think\Session;

class Eamil {
    public function email(){
        $toemail = '1484906435@qq.com';
        $name = 'M';
        $subject = 'QQ邮件发送测试';
        $content = '恭喜你，邮件测试成功。';
        dump(send_email($toemail, $name, $subject, $content));
    }
}