<?php
namespace app\index\controller;

class Index extends Common
{
    public function index()
    {
        $carousel=$this->Carousel();//轮播图
        $culture=$this->Culture();//企业文化
        $cooper=$this->Cooper();//客户案例
        $comnews=$this->Comnews();//新闻查询
        $mednews=$this->Mednews();
        $link=$this->Link();//友情链接
        $system=$this->System();//系统参数
        $this->assign([
            'carousel'=>$carousel,
            'culture'=>$culture,
            'cooper'=>$cooper,
            'comnews'=>$comnews,
            'mednews'=>$mednews,
            'link'=>$link,
        ]);
        return view();
    }

}
