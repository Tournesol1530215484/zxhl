<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/3/30
 * Time: 9:20
 */
namespace app\admin\model;
use think\Db;
use think\Model;

class AuthRule extends Model{
    //找出全部父级栏目的所有子栏目
    public function AuthTree(){
        $date=Db('auth_rule')->select();
        return  $this->FindAuthTree($date);
    }
    protected function FindAuthTree($date,$pid=0,$never=0){
        static $tree=array();
        foreach($date as $key=>$value){
            if($value['pid']==$pid){
                $value['never']=$never;
                $value['dataid']=$this->getparentid($value['id']);
                $tree[]=$value;
                $this->FindAuthTree($date,$value['id'],$never+1);
            }
        }
        return $tree;
    }

    public function AuthPidTree($id){
        $date=Db('auth_rule')->select();
        return  $this->FindAuthRuleId($id,$date);

    }
    protected function FindAuthRuleId($id,$date){
        static $tree=array();
        foreach($date as $key=>$value){
            if($value['pid']==$id){
                $tree[]=$value['id'];
                $this->FindAuthRuleId($value['id'],$date);
            }
        }
        return $tree;

    }

    public function getparentid($authRuleId){
        $AuthRuleRes=$this->select();
        return $this->_getparentid($AuthRuleRes,$authRuleId,True);
    }

    public function _getparentid($AuthRuleRes,$authRuleId,$clear=False){
        static $arr=array();
        if($clear){
            $arr=array();
        }
        foreach ($AuthRuleRes as $k => $v) {
            if($v['id'] == $authRuleId){
                $arr[]=$v['id'];
                $this->_getparentid($AuthRuleRes,$v['pid'],False);
            }
        }
        asort($arr);
        $arrStr=implode('-', $arr);
        return $arrStr;
    }

}