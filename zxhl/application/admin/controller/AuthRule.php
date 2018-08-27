<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/3/30
 * Time: 10:46
 * 添加管理员权限
 */
namespace app\admin\controller;
use app\admin\Model\AuthRule as AuthRuleModel;
use think\Controller;

class AuthRule extends Comm{
    public  function lisAuthRule(){
        $AuthRuleModel=new AuthRuleModel();
        $AuthRuleres=$AuthRuleModel->AuthTree();
        $this->assign('AuthRuleres',$AuthRuleres);
        return view('lisauthrule');
    }

    public function addAuthRule(){
        $AuthRuleModel=new AuthRuleModel();
        if(request()->isPost()){
            $date=input('post.');
            $Authlever=db('auth_rule')->where('id',$date['pid'])->field('level')->find();
            if($Authlever){
                $date['level']=$Authlever['level']+1;
            }else{
                $data['level']=0;
            }
            $res=$AuthRuleModel->insert($date);
            if($res){
                $this->success('添加成功',url('lisAuthRule'));
            }else{
                $this->error('添加失败');
            }
        }
        $AuthRuleres=$AuthRuleModel->AuthTree();
        $this->assign('AuthRuleres',$AuthRuleres);
        return view();
    }

    public function editAuthRule(){
        $AuthRuleModel=new AuthRuleModel();
        $date=input('id');
        if(request()->isPost()){
            $date=input('post.');
            $Authlever=db('auth_rule')->where('id',$date['pid'])->field('level')->find();
            if($Authlever){
                $date['level']=$Authlever['level']+1;
            }else{
                $data['level']=0;
            }
            $res=$AuthRuleModel->update($date);
            if($res){
                $this->success('更新成功',url('lis'));
            }else{
                $this->error('更新失败');
            }
        }

        $AuthRuleres=$AuthRuleModel->AuthTree();//列表
        $AuthArr=$AuthRuleModel->find($date);
        $this->assign([
            'AuthRuleres'=>$AuthRuleres,
            'AuthArr'=>$AuthArr
        ]);
        return view();

    }


    public function delAuthRule(){
        $date=input('id');
        $AuthRuleModel=new AuthRuleModel();
        $AuthArr=$AuthRuleModel->AuthPidTree($date);
        $AuthArr[]=$date;
        if( $AuthRuleModel->destroy($AuthArr)){
            $this->success('删除成功',url('lisAuthRule'));
        }else{
            $this->error('删除失败');
        }
    }




}
