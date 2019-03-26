<?php
namespace app\user\controller;

use think\Session;
use think\Db;
use think\Controller;
use think\Request;

Class ModelInfo extends Controller{
    public function ModelCheck()
    {
        $uid = Session::get('unionid');
        $allmodel = Db::table('model_buy')
                    ->where('unionid',$uid)
                    ->column('modelid');
        echo json_encode($allmodel);
    }
}