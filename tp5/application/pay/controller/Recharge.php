<?php
namespace app\pay\controller;

use think\Controller;
use think\Db;
use think\Session;

class Recharge extends controller{



	public function recharge_judge(){
        $out_trade_no = session::get('out_trade_no_recharge');
        $pay_id = Db::table('product')
                ->where('out_trade_no',$out_trade_no)
                ->value('pay_id');
        if($pay_id=='支付成功'){
            $pay = '支付成功';
            echo $pay;
        }else{
            $pay = '支付尚未成功';
            echo $pay;
        } 
    }
}