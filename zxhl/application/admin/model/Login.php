<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/3/19
 * Time: 11:33
 */
namespace app\admin\Model;
use think\Model;
use think\Db;
class Login extends  Model{
    //登录验证
    public function LogAdmin($data){
        $admin=$user = Admin::getByUser_name($data['user_name']);
        if($admin){
            if($admin['password']==md5($data['password'])){
                //采用session来进行传递值
                session('id',$admin['id']);
                session('user_name',$admin['user_name']);
                return 3;
                //成功登录
            }else{
                return 2;
                //用户密码不正确

            }
        }else{
            return 1;
            //用户名不存在
        }
    }
}