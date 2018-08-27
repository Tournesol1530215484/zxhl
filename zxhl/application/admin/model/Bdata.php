<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/7
 * Time: 21:54
 */
namespace app\admin\model;

class Bdata extends Comm{

    protected static function init()
    {
        //对图片进行存储
        Bdata::event('before_insert',function($bdata){
            if($_FILES['image']['tmp_name']){
                $file=request()->file('image');
                $info = $file->move(ROOT_PATH . 'public' . DS . 'Proman');
                if($info){
                    $img='/zxhl/' . 'public' . DS . 'Proman'.'/'.$info->getSaveName();
                    $bdata['image']=$img;

                }else{
                    $file->getError('获取信息失败');
                }
            }
        });


        //图片删除处理操作

        Bdata::event('before_delete',function($bdata){
            if(isset($_FILES['image'])) {
                if ($_FILES['image']['tmp_name']) {
                    $data = db('bdata')->find($bdata->id);
                    $filename = $_SERVER['DOCUMENT_ROOT'] . $data['image'];
                    if (is_file($filename)) {
                        unlink($filename);
                    }
                }
            }
        });
    }

}