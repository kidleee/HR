<?php
namespace app\cert\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;
class Add  extends Controller{
public function add(){
    header("content-type:text/html;charset=utf-8");
/*
//建一个txt,值自增，用作命名
$countFile="count.txt";

//文件不存在则创建
if (!file_exists($countFile)) {
    fopen($countFile,"wb");
}

$handle=fopen($countFile,"rb");
$num=fgets($handle,20);

//每次增加1
$num=$num+1;
fclose($handle);

//更新$num
$handle=fopen($countFile,"wb");
fwrite($handle,$num);
fclose($handle);
*/
//获取html路径，可自定义
$phone = '18151688687';
$extend=".html";

//正式文件中处于页面隐藏获得
$user_id = '5f6c3e2f4f24042e8b76251329e2b57e';
$hr_info = Db::table('consultant')
        ->where('user_id',$user_id)
        ->find();
$headimgur = $hr_info['headimgur'];

$nickname = $hr_info['nickname'];
$career = $hr_info['career'];
$service_type = explode(" ",$hr_info['service_type']) ;
$type_long = count($service_type);
$path="HR_".$nickname.$phone.$extend;
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





//插入数据
$abc = '1';
$sql = Db::table('wxceshi')
    ->where('ceshi3',$abc)
    ->find();

/**---开始替换---**/
//打开html模板
$handle=fopen("/var/www/html/tp5/public/sifang/HR/model.html","rb");

//读取模板内容
$str=fread($handle,filesize("/var/www/html/tp5/public/sifang/HR/model.html"));
$null = NULL;
for($i=0;$i<$type_long;$i++){
    if($service_type[$i] == '面试辅导'){
        $str=str_replace("{news_mianshi}",$service_type[$i], $str);
        /*$str=str_replace("{news_zhiye}",$null, $str);
        $str=str_replace("{news_youhua}",$null, $str);
        $str=str_replace("{news_fanyi}",$null, $str);
        $str=str_replace("{news_xiezuo}",$null, $str);
        $str=str_replace("{news_zixun}",$null, $str);
        $str=str_replace("{news_paiban}",$null, $str);*/
    }
    else if($service_type[$i] == '职业规划'){
        $str=str_replace("{news_zhiye}",$service_type[$i], $str);
        /*$str=str_replace("{news_youhua}",$null, $str);
        $str=str_replace("{news_fanyi}",$null, $str);
        $str=str_replace("{news_xiezuo}",$null, $str);
        $str=str_replace("{news_zixun}",$null, $str);
        $str=str_replace("{news_paiban}",$null, $str);*/
    }
    else if($service_type[$i] == '简历优化'){
        $str=str_replace("{news_youhua}",$service_type[$i], $str);
        /*$str=str_replace("{news_fanyi}",$null, $str);
        $str=str_replace("{news_xiezuo}",$null, $str);
        $str=str_replace("{news_zixun}",$null, $str);
        $str=str_replace("{news_paiban}",$null, $str);*/
    }
    else if($service_type[$i] == '简历翻译'){
        $str=str_replace("{news_fanyi}",$service_type[$i], $str);
        /*$str=str_replace("{news_xiezuo}",$null, $str);
        $str=str_replace("{news_zixun}",$null, $str);
        $str=str_replace("{news_paiban}",$null, $str);*/
    }
    else if($service_type[$i] == '求职信写作'){
        $str=str_replace("{news_xiezuo}",$service_type[$i], $str);
        /*$str=str_replace("{news_zixun}",$null, $str);
        $str=str_replace("{news_paiban}",$null, $str);*/
    }
    else if($service_type[$i] == '求职咨询'){
        $str=str_replace("{news_zixun}",$service_type[$i], $str);
        /*$str=str_replace("{news_paiban}",$null, $str);*/
    }
    else if($service_type[$i] == '建立排版'){
        $str=str_replace("{news_paiban}",$service_type[$i], $str);
    }
 }
 for($i=0;$i<$dirction_long;$i++){
    if($service_direction[$i] == '互联网'){
        $str=str_replace("{news_hulianwang}",$service_direction[$i], $str);
        /*$str=str_replace("{news_jinrong}",$null, $str);
        $str=str_replace("{news_xiaofei}",$null, $str);
        $str=str_replace("{news_yiliao}",$null, $str);
        $str=str_replace("{news_meiti}",$null, $str);
        $str=str_replace("{news_jianzhu}",$null, $str);
        $str=str_replace("{news_jiaoyu}",$null, $str);
        $str=str_replace("{news_fuwu}",$null, $str);
        $str=str_replace("{news_yunxu}",$null, $str);
        $str=str_replace("{news_zhengfu}",$null, $str);
        $str=str_replace("{news_qita}",$null, $str);*/
    }
    else if($service_direction[$i] == '金融'){
        $str=str_replace("{news_jinrong}",$service_direction[$i], $str);
        /*$str=str_replace("{news_xiaofei}",$null, $str);
        $str=str_replace("{news_yiliao}",$null, $str);
        $str=str_replace("{news_meiti}",$null, $str);
        $str=str_replace("{news_jianzhu}",$null, $str);
        $str=str_replace("{news_jiaoyu}",$null, $str);
        $str=str_replace("{news_fuwu}",$null, $str);
        $str=str_replace("{news_yunxu}",$null, $str);
        $str=str_replace("{news_zhengfu}",$null, $str);
        $str=str_replace("{news_qita}",$null, $str);*/
    }
    else if($service_direction[$i] == '消费'){
        $str=str_replace("{news_xiaofei}",$service_direction[$i], $str);
        /*$str=str_replace("{news_yiliao}",$null, $str);
        $str=str_replace("{news_meiti}",$null, $str);
        $str=str_replace("{news_jianzhu}",$null, $str);
        $str=str_replace("{news_jiaoyu}",$null, $str);
        $str=str_replace("{news_fuwu}",$null, $str);
        $str=str_replace("{news_yunxu}",$null, $str);
        $str=str_replace("{news_zhengfu}",$null, $str);
        $str=str_replace("{news_qita}",$null, $str);*/
    }
    else if($service_direction[$i] == '医疗'){
        $str=str_replace("{news_yiliao}",$service_direction[$i], $str);
        /*$str=str_replace("{news_meiti}",$null, $str);
        $str=str_replace("{news_jianzhu}",$null, $str);
        $str=str_replace("{news_jiaoyu}",$null, $str);
        $str=str_replace("{news_fuwu}",$null, $str);
        $str=str_replace("{news_yunxu}",$null, $str);
        $str=str_replace("{news_zhengfu}",$null, $str);
        $str=str_replace("{news_qita}",$null, $str);*/
    }
    else if($service_direction[$i] == '媒体'){
        $str=str_replace("{news_meiti}",$service_direction[$i], $str);
        /*$str=str_replace("{news_jianzhu}",$null, $str);
        $str=str_replace("{news_jiaoyu}",$null, $str);
        $str=str_replace("{news_fuwu}",$null, $str);
        $str=str_replace("{news_yunxu}",$null, $str);
        $str=str_replace("{news_zhengfu}",$null, $str);
        $str=str_replace("{news_qita}",$null, $str);*/
    }
    else if($service_direction[$i] == '建筑'){
        $str=str_replace("{news_jianzhu}",$service_direction[$i], $str);
        /*$str=str_replace("{news_jiaoyu}",$null, $str);
        $str=str_replace("{news_fuwu}",$null, $str);
        $str=str_replace("{news_yunxu}",$null, $str);
        $str=str_replace("{news_zhengfu}",$null, $str);
        $str=str_replace("{news_qita}",$null, $str);*/
    }
    else if($service_direction[$i] == '教育'){
        $str=str_replace("{news_jiaoyu}",$service_direction[$i], $str);
        /*$str=str_replace("{news_fuwu}",$null, $str);
        $str=str_replace("{news_yunxu}",$null, $str);
        $str=str_replace("{news_zhengfu}",$null, $str);
        $str=str_replace("{news_qita}",$null, $str);*/
    }
    else if($service_direction[$i] == '服务'){
        $str=str_replace("{news_fuwu}",$service_direction[$i], $str);
        /*$str=str_replace("{news_yunxu}",$null, $str);
        $str=str_replace("{news_zhengfu}",$null, $str);
        $str=str_replace("{news_qita}",$null, $str);*/
    }
    else if($service_direction[$i] == '运输'){
        $str=str_replace("{news_yunxu}",$service_directione[$i], $str);
        /*$str=str_replace("{news_zhengfu}",$null, $str);
        $str=str_replace("{news_qita}",$null, $str);*/
    }
    else if($service_direction[$i] == '政府'){
        $str=str_replace("{news_zhengfu}",$service_direction[$i], $str);
        /*$str=str_replace("{news_qita}",$null, $str);*/
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
fclose($handle);

//把替换的内容写进生成的html文件
$handle1=fopen("/var/www/html/tp5/public/sifang/HR/".$path,"wb");
fwrite($handle1,$str);
fclose($handle1);
}
}
