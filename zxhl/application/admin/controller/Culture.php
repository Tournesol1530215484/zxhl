<?php
/**
 * Created by PhpStorm.
 * User: 123
 * Date: 2018/8/10
 * Time: 15:07
 */
namespace app\admin\controller;
class Culture extends Comm{
    public function culture(){
        $Culture=db('culture')->select();
        $this->assign('Culture',$Culture);
        return view('About/culture');
    }

    public function CultureEdit(){
        $id=1;
        if(request()->isPost()){
            $data=input('post.');
            $res=db('culture')->update($data);
            if($res!==false){
                $this->success('修改成功','CultureEdit');
            }else{
                $this->error('修改失败');
            }
        }
        $Culture=db('culture')->find($id);
        $this->assign('Culture',$Culture);
        return view('About/cultureEdit');
    }
}