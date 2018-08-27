<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/7/29
 * Time: 14:20
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
class Comm extends Controller{
    public function _initialize(){
            if(!session('id')||(!session('user_name'))){
                $this->error('您尚未登录系统',url('login/index'));
            }
        //对站点标题的查找
        $favicon=db('system')->field('site_logo,site_name')->select();
        $favicon=$favicon[0];
        $this->assign('favicon',$favicon);

        //设置权限
        $Auth=new Auth();
        $request=Request::instance();
        $con=$request->controller();
        $action=$request->action();
        $name=$con.'/'.$action;
//        if(!$Auth->check($name,session('id'))){
//            $this->error('对不起，您没有权限操作',url('Index/index'));
//        }
    }


}