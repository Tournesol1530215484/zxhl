<?php
/**
 * Created by PhpStorm.
 * User: 123
 * Date: 2018/8/10
 * Time: 17:44
 */
namespace app\admin\controller;
class Recruit  extends Comm{
    public function recruit(){
        $Recruit=db('recruit')->select();
        $this->assign('Recruit',$Recruit);
        return view('Contact/recruit');
    }

    public function recruitAdd(){
        if(request()->isPost()){
            $data=input('post.');
            $data['time']=strtotime(date('Y-m-d', time()));
            $res=db('recruit')->insert($data);
            if($res){
                $this->success('招聘信息添加成功','recruit');
            }else{
                $this->error('招聘信息添加失败');
            }
        }
        return view('Contact/recruitAdd');
    }

    public function recruitEdit(){
        $id=input('id');
        if(request()->isPost()){
            $data=input('post.');
            $res=db('recruit')->where('id',$data['id'])->update($data);
            if($res){
                $this->success('招聘信息修改成功','recruit');
            }else{
                $this->error('招聘信息修改失败');
            }
        }
        $Recruit=db('recruit')->find($id);
        $this->assign('Recruit',$Recruit);
        return view('Contact/recruitEdit');
    }
    public function recruitDel(){
        $id=input('id');
       if(db('recruit')->delete($id)){
           $this->success('招聘信息删除成功','recruit');
       }else{
           $this->error('招聘信息删除失败');
       }
    }

    public function del_all(){
        $data=input('post.');
        if($data!='null'){
            $ids=implode(',',$data['checkbox']);
            if(db('recruit')->delete($ids)){
                $this->success('删除招聘信息成功','recruit');
            }else{
                $this->error('删除招聘信息失败');

            }
        }else{
            $this->error('未选中任何数据');

        }
    }
}