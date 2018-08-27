<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/7/31
 * Time: 20:52
 */
namespace app\admin\model;
use think\Model;

class Sercate extends Model{
    //找出全部父级栏目的所有子栏目
    public function ChiTree(){
        $date=$this->select();
        return $this->_ChiTree($date);
    }

    public function _ChiTree($date,$pid=0,$lever=0){
        static $tree=array();
        foreach($date as $key=>$value){
            if($value['pid']==$pid){
                $value['lever']=$lever;
                $tree[]=$value;
                $this->_ChiTree($date,$value['eid'],$lever+1);
            }
        }
        return $tree;

    }

}