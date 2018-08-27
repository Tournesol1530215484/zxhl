<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/9
 * Time: 20:06
 */
namespace app\admin\controller;
use think\Db;
class Mednews extends Comm{
    public function MednewsLis(){
        $medews=db('mednews')->order('sort desc')->paginate(6);
        $this->assign('medews',$medews);
        return view('News/mednews');
    }

    public function MednewsAdd(){
            if(request()->isPost()){
                $data=input('post.');
                $data['time']=strtotime(date('Y:m:d H:s:m'));
                //添加排序
                $count=db('medews')->count();
                if($count){
                    $data['sort']=$count+1;
                }else{
                    $data['sort']=1;
                }
                if($_FILES['image']['tmp_name']){
                       $data['image']=$this->upload();//图片上传
                   }
                $res=db('mednews')->insert($data);
                if($res){
                    $this->success('添加新闻成功','MednewsLis');
                }else{
                    $this->error('添加新闻失败');
                }

            }

            return view('News/MednewsAdd');
        }
    public function MednewsEdit(){
        $id=input('id');
        if(request()->isPost()){
            $date=input('post.');
            if($_FILES['image']['tmp_name']){
                //删
                $filename=db('mednews')->field('image')->find($date['id']);
               if(isset($filename)){
                   $img=$filename['image'];
                   $file=PICTURE.$img;
                   @unlink($file);
               }
                //改
                $date['image']=$this->upload();
            }
            $res=db('mednews')->where('id',$date['id'])->update($date);
            if($res!==false){
                $this->success('修改新闻成功','MednewsLis');
            }else{
                $this->error('修改新闻失败');
            }
        }
        $mednews=db('mednews')->find($id);
        $this->assign('mednews',$mednews);
        return view('News/MednewsEdit');
    }

    public function MednewsDEl(){
        $id=input('id');
        //对图片进行删除
        $filename=db('mednews')->field('image')->find($id);
        if(isset($filename)){
            $img=$filename['image'];
            $file=PICTURE.$img;
            @unlink($file);
        }

        //把所有小于sort的数据找出来
        $thesort=db('mednews')->field('sort')->where('id',$id)->find();
        $sortres=db('mednews')->field('id,sort')->where('sort','>',$thesort['sort'])->select();
        foreach($sortres as $key=>$value){
            db('mednews')->where('id',$value['id'])->setField('sort',$value['sort']-1);
        }
        
        $res =db('mednews')->delete($id);
        if($res){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    //排序
    public function bath(){
        $data = input('post.');
        $sort = $data['sort'];
        foreach ($sort as $key => $value) {
            $res= db('mednews')->where('id', $key)->setField('sort', $value);
        }
        $medews=Db::table('mednews')->order("sort asc,id")->paginate(7);
        $this->assign([
            'medews' => $medews,
        ]);
        return view('News/mednews');
    }

    //向上移动
    public  function upsort(){
        $data=input('post.');
        $sort=$data['pai'];//要修改的sort
        $mydate=db('mednews')->where('sort','=',$sort)->find();
        $id=$mydate['id'];
        //上一条数据
        $topone=$sort+1;
        $toponeres=db('mednews')->where('sort','=',$topone)->find();
        if($toponeres){
            //取出上一条数据的id和sort
            $toponeid=$toponeres['id'];
            $toponesort=$toponeres['sort'];
            //进行更新
            db('mednews')->where('id',$id)->setField('sort',$toponesort);
            //把要更改的sort放到前面
            db('mednews')->where('id',$toponeid)->setField('sort',$sort);

        }else{
            echo "<script>alert('已经在最顶上');</script>";

        }
        $this->redirect('/zxhl/public/admin/mednews/mednewslis.html');

    }


    //向下移动
    public  function dmsort(){
        $data=input('post.');
        $sort=$data['pai'];//要修改的sort
        $mydate=db('mednews')->where('sort','=',$sort)->find();
        $id=$mydate['id'];
        //上一条数据
        $topone=$sort-1;
        $toponeres=db('mednews')->where('sort','=',$topone)->find();
        if($toponeres){
            //取出上一条数据的id和sort
            $toponeid=$toponeres['id'];
            $toponesort=$toponeres['sort'];
            //进行更新
            db('mednews')->where('id',$id)->setField('sort',$toponesort);
            //把要更改的sort放到前面
            db('mednews')->where('id',$toponeid)->setField('sort',$sort);

        }else{
            echo "<script>alert('已经在底部了');</script>";

        }
        $this->redirect('/zxhl/public/admin/mednews/mednewslis.html');
    }

    //置顶
    public  function uploadsort(){
        //思路：获取数据库所有数据的总和，取出最后一条数据，然后进行交换
        $data=input('post.');
        $sort=$data['pai'];//要修改的sort
        $mydate=db('mednews')->where('sort','=',$sort)->find();
        $id=$mydate['id'];  //备用
        //计算出数据库所有的数据
        $count=db('mednews')->count();
        if($id==$count){
            echo "<script>alert('已经在在最底部了');</script>";
        }else{
            $mydate=db('mednews')->where('sort','=',$count)->find();
            $lastid=$mydate['id'];
            $lastsort=$mydate['sort'];
            //进行交换
            db('mednews')->where('id',$id)->setField('sort',$lastsort);
            //把要更改的sort放到前面
            db('mednews')->where('id',$lastid)->setField('sort',$sort);
        }

        $this->redirect('/zxhl/public/admin/mednews/mednewslis.html');

    }

    //置尾
    public  function domnupsort(){
        //直接和soet=1 进行交换
        $data=input('post.');
        $sort=$data['pai'];//要修改的sort
        $mydate=db('mednews')->where('sort','=',$sort)->find();
        $id=$mydate['id'];  //备用
        if($id==1){
            echo "<script>alert('已经在在最顶部了');</script>";
        }else{
            $mydate=db('mednews')->where('sort','=',1)->find();
            $firstid=$mydate['id'];
            $firstsort=$mydate['sort'];
            //进行交换
            db('mednews')->where('id',$id)->setField('sort',$firstsort);
            //把要更改的sort放到前面
            db('mednews')->where('id',$firstid)->setField('sort',$sort);
        }
        $this->redirect('/zxhl/public/admin/mednews/mednewslis.html');


    }

    //批量删除
    public function del_all(){
        $data=input('post.');
        if($data!='null'){
            $ids=implode(',',$data['checkbox']);
            $filename=db('mednews')->field('image')->find($ids);
            if(isset($filename)){
                $img=$filename['image'];
                $file=PICTURE.$img;
                @unlink($file);
            }
            $ids=explode(",",$ids);
            //获取id的个数
            for($i=0;$i<count($ids);$i++){
                $thesort=db('mednews')->field('sort')->where('id',$ids[$i])->find();//自身的sort
                $sortres=db('mednews')->field('id,sort')->where('sort','>',$thesort['sort'])->select();
                foreach($sortres as $key=>$value){
                    db('mednews')->where('id',$value['id'])->setField('sort',$value['sort']-1);
                }
            }
            if(db('mednews')->delete($ids)){
                $this->success('删除新闻成功','MednewsLis');
            }else{
                $this->error('删除新闻失败');

            }
        }else{
            $this->error('未选中任何数据');

        }
    }

    //查询
    public function select(){
        $date=input('post.');
        $keywords=$date['keywords'];
        $medews = Db::name('mednews')->where('keywords', 'like', "%{$keywords}%")->order('sort asc,id')->paginate(7);
        $this->assign([
            'medews' => $medews,
        ]);
        return view('News/mednews');
    }

        //文件上传
        public function upload(){
            $file = request()->file('image');
            $info = $file->move(ROOT_PATH . 'public' .DS.'static'.DS.'uploads');
            if($info){
                return $info->getSaveName();
            }else{
                echo $file->getError();
                die;
            }
        }

}