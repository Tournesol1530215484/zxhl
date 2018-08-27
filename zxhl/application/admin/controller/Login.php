<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/7/29
 * Time: 14:20
 */
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Login as LoginModel;

class Login extends Controller{
    public function index(){
        return view('Login/login');
    }

    public function log(){
        if(request()->isPost()) {
            $Admin=new LoginModel();
            $res=$Admin->LogAdmin(input('post.'));
            if($res==1){
                $this->error('用户名不存在',url('log'));
            }
            if($res==2){
                $this->error('账号密码不匹配',url('log'));
            }
            if($res==3){
                $this->success('正在登录',url('Index/index'));

            }
        }
        return view('login');

    }
}