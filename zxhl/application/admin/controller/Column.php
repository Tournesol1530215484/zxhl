<?php
/**
 * Created by PhpStorm.
 * User: 123
 * Date: 2018/8/10
 * Time: 14:41
 */
namespace app\admin\controller;
class Column extends Comm{
        public function column(){
            $Coumn=db('column')->select();
            $this->assign('Coumn',$Coumn);
            return view('About/column');
        }

        public function columnEdit(){
            $id=1;
            if(request()->isPost()){
                $data=input('post.');
                $res=db('column')->update($data);
                if($res!==false){
                    $this->success('修改成功','columnEdit');
                }else{
                    $this->error('修改失败');
                }
            }
            $Coumn=db('column')->find($id);
            $this->assign('Coumn',$Coumn);
            return view('About/columnEdit');
        }
}