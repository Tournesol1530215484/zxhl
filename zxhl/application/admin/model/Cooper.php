<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/7
 * Time: 22:38
 */
namespace app\admin\model;
use think\Model;

class Cooper extends Model{
    protected static function init()
    {
        //对图片进行存储
        Cooper::event('before_insert',function($cooper){
            if($_FILES['image']['tmp_name']){
                $file=request()->file('image');
                $info = $file->move(ROOT_PATH . 'public' . DS . 'Cooper');
                if($info){
                    $img='/zxhl/' . 'public' . DS . 'Cooper'.'/'.$info->getSaveName();
                    $cooper['image']=$img;

                }else{
                    $file->getError('获取信息失败');
                }
            }
        });


        //文件修改

        Cooper::event('before_update',function($cooper){

            if(isset($_FILES['image'])) {
                if ($_FILES['image']['tmp_name']) {
                    $data = db('cooper')->find($cooper->id);
                    $filename = $_SERVER['DOCUMENT_ROOT'] . $data['image'];
                    if (is_file($filename)) {
                        unlink($filename);
                    }

                    $file=request()->file('image');
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'Cooper');
                    if($info){
                        $img='/zxhl/' . 'public' . DS . 'Cooper'.'/'.$info->getSaveName();
                        $cooper['image']=$img;

                    }else{
                        $file->getError('获取信息失败');
                    }
                }
            }

        });



        //图片删除处理操作

        Cooper::event('before_delete',function($cooper){
            if(isset($_FILES['image'])) {
                if ($_FILES['image']['tmp_name']) {
                    $data = db('cooper')->find($cooper->id);
                    $filename = $_SERVER['DOCUMENT_ROOT'] . $data['image'];
                    if (is_file($filename)) {
                        unlink($filename);
                    }
                }
            }
        });
    }

}