<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/5/31
 * Time: 14:03
 */
namespace app\admin\model;
use think\Model;
 class System extends Model{

 protected static function init()
  {
 //图片修改处理操作
   System::event('before_update',function($System){
    //先对文件进行删除
     if ($_FILES['site_logo']['tmp_name']) {
      $data = db('system')->find($System->id);
      $filename = $_SERVER['DOCUMENT_ROOT'] . $data['site_logo'];
      if (is_file($filename)) {
       unlink($filename);
      }
      //在对文件进行添加
      $file = request()->file('site_logo');
      $info = $file->move(ROOT_PATH . 'public' . DS . 'system');
      if ($info) {
       $img = '/zxhl/' . 'public' . DS . 'system' . '/' . $info->getSaveName();
       $System['site_logo'] = $img;
      } else {
       $file->getError('获取图片名字失败');
      }
     }
   });
  }


  public function edit($date){
       $System=new System();
       $res= $System->allowField(true)->save($date,['id' => $date['id']]);
      return $res;
  }

  public  function  LisSystem(){
   $result=db('system')->paginate(1);
   return $result;
  }

 }