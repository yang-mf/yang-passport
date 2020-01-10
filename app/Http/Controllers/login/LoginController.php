<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\login\u_user;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $pwd1=$request->header('pwd1');
        $pwd2=$request->header('pwd2');
        if($pwd1!=$pwd2)
        {
            echo '密码有误';
        }
        $name=$request->header('name');
        $email=$request->header('email');
        $tel=$request->header('tel');
        $token=$request->header('token');
        if(!empty($email)){
            $data=[
                'name'      =>$name,
                'email'     =>$email,
                'pwd'       =>$pwd1,
                'token'     =>$token
            ];
        }else{
            $data=[
                'name'      =>$name,
                'tel'     =>$tel,
                'pwd'       =>$pwd1,
                'token'     =>$token
            ];
        }

        $uid=u_user::insert($data);
        if($uid){
            echo '注册成功';
        }

    }

    //登录
    public function login(Request $request)
    {
        $pwd1=$request->header('pwd1');
        $pwd2=$request->header('pwd2');
        if($pwd1!=$pwd2)
        {
            echo '密码有误';
        }
        $name=$request->header('name');
        if(strstr($name, '@')){
            $where=['email'=>$name];
        }else{
            $where=['name'=>$name];
        }
        $token=$request->header('token');
        $u_info=u_user::where($where)->get()->toArray();
        if($token!=$u_info[0]['token']){
            echo 'token有误';
        }
        $redis_key='token';
        Redis::set($redis_key,$token);
        Redis::expire($redis_key,5);

        $to=Redis::get($redis_key);
        echo $to;
    }

    public function redistest(){
        $redis_key='token';
        $to=Redis::get($redis_key);
        echo $to;
    }

    //获取信息
    public function getinfo(Request $request){
        $token=$request->header('token');
        $where=[
            'token'     =>$token
        ];
        $info=u_user::where($where)->get();
        print_r($info);
    }
}
