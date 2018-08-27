<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/11
 * Time: 10:49
 */
namespace app\index\controller;
 class About extends Common{
     public function index(){
         $column=$this->Column();//企业介绍
         $this->assign('column',$column);
         return view('about');
     }
     public function about_a(){
         //企业文化
         $culture=$this->Culture();//企业介绍
         $this->assign('culture',$culture);
         return view('about_a');

     }
 }