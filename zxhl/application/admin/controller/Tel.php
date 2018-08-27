<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/11
 * Time: 17:01
 */
namespace app\admin\controller;
class Tel extends Comm{
    public function index(){
        $tel=db('tel')->select();
        $telres=$tel[0];
        $this->assign('telres',$telres);
        return view('Contact/Tel');
    }

    public function editTel(){
        $data=input('post.');
        $res=db('tel')->where('id',$data['id'])->update($data);
        if($res!==false){
            $this->success('修改成功','index');
        }else{
            $this->error('修改失败');
        }
    }
}