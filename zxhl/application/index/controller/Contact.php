<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/11
 * Time: 15:49
 */
namespace app\index\controller;

class Contact extends Common{
    public function index(){
        $Contact=db('recruit')->select();
        $this->assign('Contact',$Contact);
       return view('Contact/contact');
    }

    public function zhaopin(){
        $Contact=db('recruit')->select();
        $this->assign('Contact',$Contact);
        return view('Contact/contact_a');

    }



}