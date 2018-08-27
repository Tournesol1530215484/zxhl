<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/22
 * Time: 19:46
 */
namespace app\admin\controller;

class Copyright extends Comm {
    public function copyindex(){
        $id=1;
        if(request()->isPost()){
            $data=input('post.');
            $res=db('copyright')->update($data);
            if($res!==false){
                $this->success('修改成功','copyindex');
            }else{
                $this->error('修改失败');
            }
        }
        $Copy=db('copyright')->find($id);
        $this->assign('Copy',$Copy);
        return view('index/CopyList');
    }
}