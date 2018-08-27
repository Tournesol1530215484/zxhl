<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/5/31
 * Time: 13:15
 */

namespace app\admin\controller;
use think\Controller;
use think\db;
use app\admin\model\System as SystemModel;
class System extends Comm{

    public function LisInfm($model){
        $res=$model->select();
        return $res;
    }

    public function lis(){
        $System=new SystemModel();
        $system=$this->LisInfm($System);
       $this->assign('system',$system);
        return view('index/system');
    }

    public function updateSytem(){

        $date=input('post.');
        if($_FILES['site_logo']['tmp_name']){
            $filename=db('system')->field('site_logo')->find($date['id']);
            if(isset($filename)){
                $img=$filename['site_logo'];
                $file=FAVICON.$img;
                @unlink($file);
            }
            $date['site_logo']=$this->upload();
        }
        $System=new SystemModel();
        if($System->edit($date)!==false){
         $this->success('修改成功','lis');
        }else{
            $this->error('修改失败');
        }

    }

    //文件上传
    public function upload(){
        $file = request()->file('site_logo');
        $info = $file->move(ROOT_PATH . 'public','favicon.ico');
        if($info){
           $name=$info->getExtension();;
            $logo= str_replace($name,$name,'ico');
            $upame=$info->getSaveName();
            return $upame;

        }else{
            echo $file->getError();
            die;
        }
    }
}