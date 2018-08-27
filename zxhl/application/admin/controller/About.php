<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Column as ColumnModel;
use app\admin\model\Carousel as CarouselModel;
class About extends Comm
{

    //------------------------栏目操作-------------------------

    //前置操作，用来删除该栏目下的所有字栏目
    protected $beforeActionList=[
        'DelChiled' =>['only' => 'delcolumn']
    ];

    //栏目管理
    public function column()
    {
        $CateModel=new ColumnModel();
        $Column=$CateModel->ChiTree();
        $this->assign('Column',$Column);
        return view('About\column');
    }
    //添加栏目
    public function addcolumn(){
        $ColumnModel=new ColumnModel();
        if(request()->isPost()){
            $date=input('post.');
            $Cate=$ColumnModel->save($date);
            if($Cate!=false){
                $this->success('添加栏目成功',url('column'));
            }else{
                $this->error('添加栏目失败');
            }
        }
        $Column=$ColumnModel->ChiTree();
        $this->assign('Column',$Column);
        return view('About/addcolumn');
    }

    //修改栏目
    public function editcolumn(){
            $id=input('id');
            $CateModel=new ColumnModel();
            if(request()->isPost()){
                $res=$CateModel->update(input('post.'));
                if($res!=false){
                    $this->success('修改栏目成功',url('column'));
                }else{
                    $this->error('修改栏目失败');
                }
             }
           $pid= $CateModel->ChiTree();
            //找出改要修改的内容
            $column=$CateModel->find($id);
            $this->assign([
                'pid'=>$pid,
                'column'=>$column
            ]);
             return view('editcolumn');
    }

    //删除栏目
    public function delcolumn(){
        //再删除之前要将所有的子栏目都要删除
            $id=input('id');
            $ColumnModel=new ColumnModel();
            $res=$ColumnModel->destroy($id);
            if($res){
                $this->success('删除栏目成功',url('column'));
            }else{
                $this->error('删除栏目失败');
            }

    }

    //删除所有的子栏目
    public function DelChiled(){
        $id=input('id');
        $ColumnModel=new ColumnModel();
        $Catesonid=$ColumnModel->FinChldTree($id);
        if($Catesonid){
            db('column')->delete($Catesonid);
        }
    }

    //------------------------文章操作-------------------------

    //---------文章列表-----------
    public function culture()
    {
        return view('About\culture');
    }

    //文章编辑
    public function Editarticle(){
        return view('editarticle');
    }


    //---------文章添加-----------

    public  function Addarticle(){
        if(request()->isPost()){
            $date=input('post.');
            $date['time']=date('Y-m-d H:s:m',time());
            //对文章进行添加操作
            $ColumnModel=new ColumnModel();
            if($ColumnModel->save($date)){
                $this->success('添加文章成功','lis');
            }else{
                $this->error('添加文章失败');
            }
        }
        $CateModel=new CarouselModel();
        $Column=$CateModel->ChiTree();
        $this->assign('Column',$Column);
        $this->assign([
            'Column'=>$Column,
        ]);
        return view('addarticle');

    }


}
