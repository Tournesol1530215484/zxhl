<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/11
 * Time: 15:32
 */
namespace app\index\controller;

class Project extends Common{
    public function index(){
        $temp=db('health')->select();
        $health=$temp[0];
        $this->assign('health',$health);
        return view('Project/project');
    }

    public function Bdata(){
        $Bdata=db('bdata')->select();
        $this->assign('Bdata',$Bdata);
        return view('Project/project_a');
    }
}