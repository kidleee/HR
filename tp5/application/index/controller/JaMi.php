<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class JaMi extends Controller{
public function index($index){
$jiami=md5($index);
return $jiamin;
}
}