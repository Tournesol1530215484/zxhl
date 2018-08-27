<?php
namespace app\admin\model;
use think\Model;
class Column extends Model{

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
                $this->_ChiTree($date,$value['id'],$lever+1);
            }
        }
        return $tree;

    }

    //无限极分类，对所有的子栏目进行查找，方便删除
    public function FinChldTree($id){
        $data=$this->select();
        return $this->_FinChldTree($data,$id);
    }
    protected function _FinChldTree($data,$id){
        static $tree=array();
        foreach($data as $key=>$value){
            if($value['pid']==$id){
                $tree[]=$value['id'];
                $this->_FinChldTree($tree,$value['id']);
            }

        }
        return $tree;
    }


}