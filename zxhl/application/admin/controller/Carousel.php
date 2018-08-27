<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/6/1
 * Time: 20:50
 */
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Carousel as CarouselModel;
use Catetree\Catetree;
class Carousel extends Comm{
    //图片显示
    public function index(){
        if(request()->isPost()){
            $date=input('post.');
            //更新数据库
            $sort=$date['sort'];

            foreach($sort as $key=>$value){
                db('carousel')->where('id',$key)->setField('sort',$value);
            }
        }
        $CarouselRes=db('carousel')->order('sort asc,id')->paginate(10);
        $this->assign('CarouselRes',$CarouselRes);
        return view('Carousel/carousel');
    }

    //图片添加
    public function addCarousel(){
        if(request()->isPost()){
            $data=input('post.');
            if($_FILES['show_img']['tmp_name']){
                $data['show_img']=$this->upload();//图片上传
            }
            if(stripos($data['show_link'],'http://')===false){
                $data['show_link']='http://'.$data['show_link'];
            }
            $res=db('carousel')->insert($data);
            if($res){
                $this->success('添加链接成功','index');
            }else{
                $this->error('添加链接失败');
            }
        }
        return view('Carousel/carouselAdd');
    }

    public function EditCarousel(){
        if(request()->isPost()){
            $data=input('post.');
            if($_FILES['show_img']['tmp_name']){
                $filename=db('carousel')->field('show_img')->find($data['id']);
                if(isset($filename)){
                    $img=$filename['show_img'];
                    $file=PICTURE.$img;
                    @unlink($file);
                }
                $data['show_img']=$this->upload();//图片上传
            }
            if(stripos($data['show_link'],'http://')===false){
                $data['show_link']='http://'.$data['show_link'];
            }
            $res=db('carousel')->update($data);
            if($res!==false){
                $this->success('修改链接成功','index');
            }else{
                $this->error('修改链接失败');
            }
        }
        $id=input('id');
        $CarouselRes=db('carousel')->find($id);
        $this->assign('CarouselRes',$CarouselRes);
        return view('Carousel/carouselEdit');
    }

    //图片删除
    public function delCarousel(){
        $id=input('id');
        $filename=db('carousel')->field('show_img')->find($id);
        if(isset($filename)){
            $img=$filename['show_img'];
            $file=PICTURE.$img;
            @unlink($file);
        }
        $res =db('carousel')->delete($id);
        if($res){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }

    }
    //文件上传
    public function upload(){
        $file = request()->file('show_img');
        $info = $file->move(ROOT_PATH . 'public' .DS.'static'.DS.'uploads');
        if($info){
            return $info->getSaveName();
        }else{
            echo $file->getError();
            die;
        }
    }



}