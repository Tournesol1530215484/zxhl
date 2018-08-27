<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/11
 * Time: 13:34
 */
namespace app\index\controller;
use Catetree\Catetree;
class Service extends Common{

    public function test(){
       $cateid=db('servercate')->where(array('pid'=>0))->select();
        foreach($cateid as $key=>$value){
            $chlid=db('servercate')->where(array('pid'=>$value['id']))->select();
            if($chlid){
                $cateid['key']['value']=$cateid;
            }else{
                $cateid['key']['value']=0;
            }
        }
        $this->assign('cateid',$cateid);
    }

    public function server(){
        $id=input('cateid');
    }

    public function index(){
        //找出所有一级栏目
        $cateid=db('servercate')->where(array('pid'=>0))->select();
        foreach($cateid as $key=>$value){
            $chlid=db('servercate')->where(array('pid'=>$value['id']))->select();
            if($chlid){
                $cateid[$key]['chlid']=$chlid;
            }else{
                $cateid[$key]['chlid']=0;
            }

        }
        $this->assign('cateid',$cateid);
        $id=input('id');
        //获取该栏目下的所有子文章
        $crede= $Crede=db('server')->where(array('pid'=>$id))->paginate(6);
        $this->assign([
            'crede'=>$crede,
        ]);
        return view('Service/service');
    }
}
