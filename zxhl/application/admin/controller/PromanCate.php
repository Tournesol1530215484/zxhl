<?php
/**
 * Created by PhpStorm.
 * User: 123
 * Date: 2018/8/17
 * Time: 17:38
 */
namespace app\admin\controller;
use Catetree\Catetree;
class PromanCate extends  Comm{
    public function CateList(){
        //排序
        if(request()->isPost()){
            $date=input('post.');
            $sort=$date['sort'];
            foreach($sort as $key=>$value){
                db('promancate')->where('id',$key)->setField('sort',$value);
            }
        }
            $cate=new Catetree();
            $date=db('promancate')->order('sort asc,id')->select();
            $Cate=$cate->ChildTree($date);
            $this->assign('Cate',$Cate);
            return view('promanCate/CateList');
    }

    public function CateAdd(){
        if(request()->isPost()){
            $data=input('post.');
            $res=db('promancate')->insert($data);
            if($res){
                $this->success('添加栏目成功','CateList');
            }else{
                $this->error('添加栏目失败');
            }
        }
        $cate=new Catetree();
        $date=db('promancate')->select();
        $Cate=$cate->ChildTree($date);
        $this->assign('Cate',$Cate);
        return view('PromanCate/CateAdd');
    }

    public  function CateEdit(){
        if(request()->isPost()){
            $date=input('post.');
            if($date['pid']==$date['id']){
                $this->error('不允许成为自己的子栏目');
            }
            $res=db('promancate')->update($date);
            if($res!==false){
                $this->success('修改栏目成功','CateList');
            }else{
                $this->error('栏目修改失败');
            }
        }
        $id=input('id');
        $Catecontent=db('promancate')->find($id);
        $cate=new Catetree();
        $date=db('promancate')->field('id,cate_name,pid')->select();
        $Cate=$cate->ChildTree($date);
        $this->assign([
            'Cate'=>$Cate,
            'Catecontent'=>$Catecontent,
        ]);
        return view('promancate/CateEdit');
    }


    public  function CateDel(){
        $id=input('id');
        $catetree=new Catetree();//实例化无限极分类方法
        $cateres=db('promancate')->field('id,pid')->select();//根据id删除自己
        $cateres=$catetree->Parenttree($id,$cateres);//无限极分类（见上一篇）
        $cateres[]=$id;
        foreach($cateres as $value){
            //value是栏目
            $proman=db('proman')->field('id,pid')->select();
            foreach($proman as $k=>$v){
                //$v文章
                if($value==$v['pid']){
                    //对文章里面的文件进行删除
                    $filename=db('proman')->field('image')->find($v['id']);
                    if(isset($filename)){
                        $img=$filename['image'];
                        $file=PICTURE.$img;
                        @unlink($file);
                    }
                    //根据id删除文章
                    $delproman=db('proman')->delete($v['id']);
                    if(!$delproman){
                        $this->error('删除文章异常');
                        die;
                    }
                }
            }
            //删除栏目
            $res=db('promancate')->where('id',$value)->delete();
        }
        if($res){
            $this->success('删除成功','CateList');
        }else{
            $this->error('删除是失败');
        }
    }

}