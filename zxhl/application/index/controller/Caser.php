<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/11
 * Time: 13:59
 */

namespace app\index\controller;
class Caser extends Common  {
    public function index(){
        $case=db('cooper')->paginate(6);
        $page = $case->render();
        $this->assign([
            'case'=>$case,
            'page'=>$page,
        ]);
        return view('Caser/caser');
    }
}