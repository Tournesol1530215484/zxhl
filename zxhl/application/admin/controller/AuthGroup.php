<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/3/30
 * Time: 9:11
 */
namespace app\admin\controller;
use app\admin\model\AuthGroup as AuthGroupModel;
use app\admin\model\AuthRule as AuthRuleModel;
use think\Controller;

class AuthGroup extends Comm{
    public function lisAuthGroup(){
        $AuthGroup=new AuthGroupModel();
        $AuthGroupres=$AuthGroup->paginate(5);
        $this->assign('AuthGroupres',$AuthGroupres);
        return view('lisAuthGroup');
    }

    public function addAuthGroup(){
        if(request()->isPost()){
            $date=input('post.');
            $AuthGroup=new AuthGroupModel();
            if($date['rules']){
                $date['rules']=implode(',', $date['rules']);
            }
            $res=$AuthGroup->save($date);
            if($res){
                $this->success('添加用户成功',url('lisAuthGroup'));
            }else{
                $this->error('添加用户失败');
            }
        }
        $AuthRuleModel=new AuthRuleModel();
        $AuthRuleres=$AuthRuleModel->AuthTree();
        $this->assign('AuthRuleres',$AuthRuleres);
        return view('addAuthGroup');
    }

    public function editAuthGroup(){
        $AuthGroup=new AuthGroupModel();
        if(request()->isPost()){
            $date=input('post.');
            if($date['rules']){
                $date['rules']=implode(',', $date['rules']);
            }
            $_date=array();
            foreach ($date as $k => $v) {
                $_date[]=$k;
            }
            if(!in_array('status', $_date)){
                $date['status']=0;
            }
            $save=db('auth_group')->update($date);
            if($save){
                $this->success('修改成功',url('lisAuthGroup'));
            }else{
                $this->error('修改失败');
            }
        }
        $AuthGroupres=$AuthGroup->find(input('id'));
        $this->assign('AuthGroupres',$AuthGroupres);
        $AuthRuleModel=new AuthRuleModel();
        $AuthRuleres=$AuthRuleModel->AuthTree();
        $this->assign('AuthRuleres',$AuthRuleres);
        return view('editAuthGroup');
    }

    public function delAuthGroup(){
        $date=input('id');
        $AuthGroup=new AuthGroupModel();
        $res=$AuthGroup->destroy($date);
        if($res){
            $this->success('删除成功',url('lisAuthGroup'));
        }else{
            $this->error('删除失败');
        }
        return view('lisAuthGroup');
    }
}