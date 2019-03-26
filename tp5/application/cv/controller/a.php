<?php
namespace app\cv\controller;
class a extends Controller{
	public function a(){
	//$id=$_SERVER["QUERY_STRING"];
	header("Content-type: text/html; charset=gbk");
	$url="http://www.hnntv.cn/m2o/channel/channel_info.php";
	$info=file_get_contents($url);
	$json = json_decode($info,true);
	//preg_match('/"m3u8":"%"/i',$info,$m);
	echo $json[0]["m3u8"];
	//var_dump($json);
	//header('location:'.urldecode($m[1]));}
}	