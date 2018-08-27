<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/2
 * Time: 21:01
 */
namespace app\admin\model;
use think\Model;

class Comm extends Model{
    protected static function init()
    {
        //对图片进行存储
        Server::event('before_insert',function($server){
            if($_FILES['image']['tmp_name']){
                $file=request()->file('image');
                $info = $file->move(ROOT_PATH . 'public' . DS . 'Server');
                if($info){
                    $img='/zxhl/' . 'public' . DS . 'Server'.'/'.$info->getSaveName();
                    $server['image']=$img;

                }else{
                    $file->getError('获取信息失败');
                }
            }
        });


        //图片删除处理操作

        Server::event('before_delete',function($server){
            if(isset($_FILES['image'])) {
                if ($_FILES['image']['tmp_name']) {
                    $data = db('server')->find($server->id);
                    $filename = $_SERVER['DOCUMENT_ROOT'] . $data['image'];
                    if (is_file($filename)) {
                        unlink($filename);
                    }
                }
            }
        });
    }

}