<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/7/31
 * Time: 20:52
 */
namespace app\admin\model;
use think\Db;
use think\Model;

class Server extends Comm{
    public function Find(){
        $data=Db('server a')->field('a.title,a.image,a.pid')->join('sercate b','a.pid=b.eid')->select();
        return $data;
    }
}