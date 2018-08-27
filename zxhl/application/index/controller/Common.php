<?php
namespace app\index\controller;
use think\Controller;

class Common extends Controller{
    //获取联系方式
    public  function _initialize(){
        //站点标志
        $favicon=db('system')->field('site_logo,site_name')->select();
        $favicon=$favicon[0];
        $this->assign('favicon',$favicon);
        //获取qq
        $res=db('system')->field('qq')->select();
        $res=$res[0]['qq'];
        $var=explode(",",$res);
        $tel=db('tel')->select();


        //获取版权信息
        $copyright=db('copyright')->where('id',1)->find();
        $telres=$tel[0];
        $this->assign([
            'telres'=>$telres,
            'favicon'=>$favicon,
            'qq'=>$var,
            'Right'=>$copyright
        ]);
    }

    //轮播图的查询
    public function Carousel(){
        $carousel=db('carousel')->select();
        return $carousel;
    }
    //企业文化查询
    public function Culture(){
        $cul=db('culture')->select();
        return $cul[0];
    }
    //企业介绍
    public function Column(){
        $cul=db('column')->select();
        return $cul[0];
    }
    //客户案例查询
    public function Cooper(){
        $cooper=db('cooper')->select();
        return $cooper;
    }
    //获取新闻资讯
    public function Comnews(){
        $Comnews=db('comnews')->order(['click','id'=>'asc'])->limit(6)->select();
        return $Comnews;
    }
    public function Mednews(){
        $Mednews=db('mednews')->order(['click','id'=>'asc'])->limit(6)->select();
        return $Mednews;
    }
    //获取友情链接
    public function Link(){
        $Link=db('link')->select();
        return $Link;
    }
    //获取系统参数
    public function System(){
        $System=db('system')->select();
        return $System;
    }








}