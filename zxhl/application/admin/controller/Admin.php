<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/7/29
 * Time: 8:41
 */
namespace app\admin\controller;
use app\admin\model\Admin as AdminModel;
use app\admin\model\AuthGroup as AuthGroupModel;
class Admin extends Comm{
    //添加管理员
    public function addAdmin(){
        if(request()->isPost()){
            $date=input('post.');
            $date['time']=date('Y:m:d H:s:m');           //添加注册时间
            $AdminModel=new AdminModel();
            if($AdminModel->AddAdmin($date)){
                $this->success('添加管理员成功','lisAdmin');
            }else{
                $this->error('添加管理员失败');
            }

        }
        $AuthGroup=new AuthGroupModel();
        $AuthG=$AuthGroup->select();
        $this->assign('AuthG',$AuthG);
        return view('addAdmin');
    }
    //管理员列表
    public function lisAdmin(){
        $auth=new Auth();
        $AdminModel= new AdminModel();
        $AdminRes=$AdminModel->lisAdmin();
        foreach($AdminRes as $k=>$v){
            $groupTitle=$auth->getGroups($v['id']);
            $_groupTitle=$groupTitle[0]['title'];
            $v['groupTitle']=$_groupTitle;
        }
        $this->assign('AdminRes',$AdminRes);
        return view('lisAdmin');
    }
    //修改管理员
    public function editAdmin($id){
        //找到要修改的管理员
        $admin=db('admin')->find($id);
        //判断修改后和修改前的差别
        if(request()->isPost()){
            $data=input('post.');
            $Admin=new AdminModel();
            $res=$Admin->editAdmin($data,$admin);
            if($res=='2'){
                $this->error('管理员名字不得为空');
            }else if($res!==false){
                $this->success('修改管理员成功',url('lisAdmin'));
            }else{
                $this->error('修改管理员失败');
            }
            return ;
        }
        if(!$admin){
            $this->error('该管理员不存在');
        }
        else{
            $this->assign('AdminRes',$admin);

        }
        $Auth_group=db('auth_group_access')->where(array('uid'=>$id))->find();
        $authGroup=db('auth_group')->select();
        $this->assign('authGroup',$authGroup);
        $this->assign('groupID',$Auth_group['group_id']);
        return view('editAdmin');
    }
    //删除管理员
    public function delAdmin(){
        $AdminModel=new AdminModel();
        if($AdminModel->delAdmin(input('id'))){
            $this->success('删除管理员成功','lisAdmin');
        }else{
            $this->error('删除管理员失败');
        }
    }
}