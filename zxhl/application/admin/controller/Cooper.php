<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/7
 * Time: 22:38
 */
namespace app\admin\controller;
use app\admin\model\Cooper as CooperModel;
use think\Db;
class Cooper extends Comm{

    //对方法进行提取
    //遍历数据
    public function LisInfm($model){
        $res=$model->select();
        return $res;
    }
    public function FindInfm($id,$model){
        $res=$model->where('id',$id)->find();
        return $res;
    }
    //添加数据
    public function AddInfm($data,$model,$url){
        $res=$model->save($data);
        if($res){
            $this->success('添加成功',$url);
        }else{
            $this->error('添加失败');
        }

    }

    //修改数据
    public function EditInfm($data,$model,$url){
        $res=$model->update($data);
        if($res!=false){
            $this->success("修改成功",$url);
        }else{
            $this->error("修改失败");
        }
    }
    //删除数据
    public function DelInfm($id,$model,$url){
        $res=$model->destroy($id);
        if($res){
            $this->success("修改成功",$url);
        }else{
            $this->error("修改失败");
        }
    }



    public function Cooper(){
       $CooperModel=new CooperModel();
        $Cooper=$this->LisInfm($CooperModel);
        $this->assign('Cooper',$Cooper);
       return view('Cooper');
    }

    public function CooperAdd(){
        if(request()->isPost()){
            $data=input('post.');
            $data['time']=strtotime(date('Y-m-d H:i:d',time()));
            if($_FILES['image']['tmp_name']){
                $data['image']=$this->upload();//图片上传
            }
            if(stripos($data['link'],'http://')===false){
                $data['link']='http://'.$data['link'];
            }
            $CooperModel=new CooperModel();
            $url='Cooper';
            $this->AddInfm($data,$CooperModel,$url);
        }
       return view('CooperAdd');
    }

    public function CooperEdit(){
        $id=input('id');
        $CooperModel=new CooperModel();
        if(request()->isPost()){
            $data=input('post.');
            $url='Cooper';
            if($_FILES['image']['tmp_name']){
                $filename=db('cooper')->field('image')->find($data['id']);
                if(isset($filename)){
                    $img=$filename['image'];
                    $file=PICTURE.$img;
                    @unlink($file);
                }
                $data['image']=$this->upload();
            }
            if(stripos($data['link'],'http://')===false){
                $data['link']='http://'.$data['link'];
            }
            $this->EditInfm($data,$CooperModel,$url);
        }
        $Cooper=$this->FindInfm($id,$CooperModel);
        $this->assign('Cooper',$Cooper);
        return view('CooperEdit');
    }

    public function CooperDel(){
        $id=input('id');
        $CooperModel=new CooperModel();
        $filename=db('cooper')->field('image')->find($id);
        if(isset($filename)){
            $img=$filename['image'];
            $file=PICTURE.$img;
            @unlink($file);
        }
        $url='Cooper';
        $this->DelInfm($id,$CooperModel,$url);
    }

    //模糊查询

    public function select(){
            $date=input('post.');
            $keywords=$date['keywords'];
            $Cooper = Db::name('cooper')->where('keywords', 'like', "%{$keywords}%")->order('sort asc,id')->paginate(7);
            $this->assign([
                'Cooper' => $Cooper,
            ]);
            return view('Cooper/cooper');
    }

    //排序
    public function bath(){
        $data = input('post.');
        $sort = $data['sort'];
        foreach ($sort as $key => $value) {
            $res= db('cooper')->where('id', $key)->setField('sort', $value);
        }
        $Cooper=Db::table('cooper')->order("sort asc,id")->paginate(7);
        $this->assign([
            'Cooper' => $Cooper,
        ]);
        return view('Cooper/cooper');
    }

    //批量删除
    public function del_all(){
        $data=input('post.');
        $data=$data['checkbox'];
        if($data!='null'){
                $ids=implode(',',$data['checkbox']);
                $filename=db('cooper')->field('image')->find($ids);
                if(isset($filename)){
                    $img=$filename['image'];
                    $file=PICTURE.$img;
                    @unlink($file);
                }
                if(db('cooper')->delete($ids)){
                    $this->success('删除文章成功','Cooper');
                }else{
                    $this->error('删除文章失败','Cooper');

                }
            }else{
                $this->error('未选中任何数据');

        }
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