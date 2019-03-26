<?php
namespace app\ceshi\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;


class Getinfo extends Controller{
    //历史记录存储
    public function history(){
        $number_last = Db::table('i_history')->count();
        $number = $number_last+1;
        $car_info  = session::get('shuju');
        $date = $car_info[0][0]['start_time'];
        //运行情况
        $info = input ('post.info');
        Db::table('i_history')
        ->insert([
            'number' => $number,
            'info' => $info,
            'json' => $car_info,
            'date' => $date,
        ]);
        return;
    }
    //添加人员
    public function body(){
        //巡视人员，管理维护人员，数据处理人员，领导
        $number = input('post.$number');
        $bool = Db::table('i_user')
        ->where('number',$number)
        ->find();
        $account = null;
        $password = null;
        if($bool == null){
            $name = input('post.name');
            $old =  input('post.old');
            $office = input('post.office');
            $account = input('post.account');
            $password = input('post.password');
            $iphone = input('post.iphone');
            $body_info = array(
                [
                    'number' => $number,
                    'name' => $name,
                    'old' => $old,
                    'office' => $office,
                    'account' =>  $account,
                    'password' => $password,
                ]
            );
            Db::table('i_user')
            ->insert([
                'number' => $number,
                'name' => $name,
                'old' => $old,
                'office' => $office,
                'account' =>  $account,
                'password' => $password,
                'iphone' => $iphone,
            ]);
        }else{
            echo '编号已存在，请重新输入。';
        }
    }
    //添加车辆
    public function car(){
        $number = input('post.number');
        $car_name = input('post.carname');
        $weight = input('post.weight');
        // $number = 8;
        // $car_name =  1;
        // $weight = 1;
        $bool = Db::table('i_car')
        ->where('number',$number)
        ->find();
        if($bool == null){
            $car_info = array(
                [
                    'number' => $number,
                    'changjia' => $car_name,
                    'zhongliang' => $weight,
                ]
            );
        $car_info_i = Db::table('i_car')->insert([
            'number' => $number,
            'changjia' => $car_name,
            'zhongliang' => $weight,
        ]);
        }else{
            echo '该编号已存在，请重新输入。';
        }
    }
    //人员登录
    public function login(){
        $account = input('post.account');
        $password = input('post.password');
        $office = input('post.office');
        // $account = 'l123456';
        // $password = 'l123456';
        // $office = '巡视人';
        $bool = Db::table('i_user')
                ->where('account',$account)
                ->find();
        if($bool == null){
            echo '账号不存在，请寻找管理员注册账号。';
        }else{
            if($password == $bool['password']){
                if($office == $bool['office']){
                    $this->redirect('http://www.baidu.com');
                }else{
                    echo '请选择正确的职位登录';
                }
                
            }else{
                echo '密码不正确,请重新输入';
            }
        }
    }

    //数据读取
    public function car_history(){
        $car_history = Db::table('i_history')
                ->select();
        $json = json_encode($car_history);
        return $car_history;
    }
    //用于回放的数据读取
    public function car_history_back(){
        $car_history = Db::table('i_history')
              ->value('json');
        echo $car_history;
    }
    //人员读取
    public function personnel(){
        $personnel = Db::table('i_user')
          ->select();
        $json = json_encode($personnel);
        return $json;
    }
    //车辆读取
    public function car_info(){
        //分别标识为 changjia、zhongliang、number
        $car_info = Db::table('i_car')
           ->select();
        $json = json_encode($car_info);
        return $json;
    }
}