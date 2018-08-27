<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/7
 * Time: 20:48
 */
namespace app\admin\model;
use think\Model;

class Health extends Comm{
    protected static function init()
    {
        //对图片进行存储
        Health::event('before_insert',function($health){
            if($_FILES['image']['tmp_name']){
                $file=request()->file('image');
                $info = $file->move(ROOT_PATH . 'public' . DS . 'Proman');
                if($info){
                    $img='/zxhl/' . 'public' . DS . 'Proman'.'/'.$info->getSaveName();
                    $health['image']=$img;

                }else{
                    $file->getError('获取信息失败');
                }
            }
        });


        //图片删除处理操作

        Health::event('before_delete',function($health){
            if(isset($_FILES['image'])) {
                if ($_FILES['image']['tmp_name']) {
                    $data = db('health')->find($health->id);
                    $filename = $_SERVER['DOCUMENT_ROOT'] . $data['image'];
                    if (is_file($filename)) {
                        unlink($filename);
                    }
                }
            }
        });
    }

}