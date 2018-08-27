<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/11
 * Time: 15:06
 */
namespace app\index\controller;
class dynamic extends Common{
    public function index(){
        $comnews=db('comnews')->paginate(6);
        $page = $comnews->render();
        $this->assign([
            'comnews'=>$comnews,
            'page'=>$page,
        ]);
        return view('Dynamic/dynamic');
    }

    public function Nametwo(){
        $comnews=db('mednews')->paginate(6);
        $page = $comnews->render();
        $this->assign([
            'comnews'=>$comnews,
            'page',$page,
        ]);
        return view('Dynamic/dynamic_mednews');
    }




}