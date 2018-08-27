<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Server as ServerModel;
use think\Db;
use Catetree\Catetree;
class Proman extends Comm
{
    //模糊查询
    public function SelectProman()
    {
        if (request()->isPost()) {

            $date = input('post.');
            $pid = $date['pid'];
            $keywords = $date['keywords'];
            $limit = 10;
            $page = 1;
            $Proman = Db::name('proman')->alias('s')->where('pid', $pid)->where('keywords', 'like', "%{$keywords}%")->order('id desc')->paginate(7);
            $cate = new Catetree();
            $date = db('promancate')->field('id,pid,cate_name')->select();
            $Cate = $cate->ChildTree($date);

            $this->assign([
                'Proman' => $Proman,
                'Cate' => $Cate
            ]);
            return view('Proman/proman');
        }

    }

    //列表显示

    public function proman()
    {
        $limit = 10;
        $page = 1;
        //服务列表
       // $Server = Db::table('proman')->alias('s')->field('s.*,c.cate_name')->join('promancate c', 's.pid=c.id', 'left')->group('id')->limit($limit)->page($page)->select();
        $Proman=Db::table('proman')->order("sort asc,id")->paginate(7);
        //所属栏目列表
        $cate = new Catetree();
        $date = db('promancate')->field('id,pid,cate_name')->select();
        $Cate = $cate->ChildTree($date);

        $this->assign([
            'Proman' => $Proman,
            'Cate' => $Cate
        ]);
        return view('Proman/proman');
    }

    //添加服务
    public function promanAdd()
    {
        $cate = new Catetree();
        $date = db('promancate')->order('sort asc,id')->select();
        $Cate = $cate->ChildTree($date);
        $this->assign('Cate', $Cate);
        if (request()->isPost()) {
            $date = input('post.');
            $date['time'] = strtotime(date('Y-m-d', time()));
            if ($_FILES['image']['tmp_name']) {
                $date['image'] = $this->upload();//图片上传
            }
            $res = db('proman')->insert($date);
            if ($res) {
                $this->success('添加成功', 'proman');
            } else {
                $this->error('添加失败');
            }
        }
        return view('Proman/promanadd');
    }

    //服务修改
    public function promanEdit(){
        if(request()->isPost()){
            $date=input('post.');
            if($_FILES['image']['tmp_name']){
                $filename=db('proman')->field('image')->find($date['id']);
                if(isset($filename)){
                    $img=$filename['image'];
                    $file=PICTURE.$img;
                    @unlink($file);
                }
                $date['image'] = $this->upload();//图片上传
            }
            $res=db('proman')->update($date);
            if($res){
                $this->success('修改服务成功','proman');
            }else{
                $this->error('修改服务失败');
            }
        }

        $id=input('id');
        //所属栏目
        $cate = new Catetree();
        $date = db('promancate')->field('id,pid,cate_name')->select();
        $Cate = $cate->ChildTree($date);
        //具体内容
        $PromanRes=db('proman')->find($id);
        $this->assign([
            'Cate' => $Cate,
            'PromanRes'=>$PromanRes,
        ]);
        return view('Proman/promanedit');

    }


    //服务删除

    public function promanDel(){
        $id=input('id');
        $filename=db('proman')->field('image')->find($id);
        if($_FILES['image']['tmp_name']){
            if(isset($filename)){
                $img=$filename['image'];
                $file=PICTURE.$img;
                @unlink($file);
            }
        }
        $res=db('proman')->delete($id);
        if($res){
            $this->success('删除服务成功','proman');
        }else{
            $this->error('删除服务失败');
        }

    }

    //批量操做
    public function batch()
    {
        $data = input('post.');
        if ($data['action'] == 'del_all') {
            $ids = implode(',', $data['checkbox']);
            $filename = db('proman')->field('image')->find($ids);
            if (isset($filename)) {
                $img = $filename['image'];
                $file = PICTURE . $img;
                @unlink($file);
            }
            $res = db('proman')->delete($ids);
            if ($res) {
                $this->success('批量删除成功', 'proman');
            } else {
                $this->error('删除失败');
            }

        } elseif ($data['action'] == 'category_move') {
            foreach ($data['checkbox'] as $key => $value) {
                $Art = db('proman')->where("id" == $value)->select();
                foreach ($Art as $k => $v) {
                    $res = db('proman')->update(['id' => $value, 'pid' => $data['new_cat_id']]);
                }

            }
            if ($res!==false) {
                $this->success('转移成功', 'proman');
            } else {
                $this->error('转移失败', 'proman');

            }
        } else {
            $sort = $data['sort'];
            foreach ($sort as $key => $value) {
               $res= db('proman')->where('id', $key)->setField('sort', $value);
            }

            $cate = new Catetree();
            $date = db('promancate')->field('id,pid,cate_name')->select();
            $Cate = $cate->ChildTree($date);
            $Proman=Db::table('proman')->order("sort asc,id")->paginate(7);
            $this->assign([
                'Proman' => $Proman,
                'Cate' => $Cate
            ]);
            return view('Proman/proman');
        }
    }


        //文件上传
        public
        function upload()
        {
            $file = request()->file('image');
            $info = $file->move(ROOT_PATH . 'public' . DS . 'static' . DS . 'uploads');
            if ($info) {
                return $info->getSaveName();
            } else {
                echo $file->getError();
                die;
            }
        }


    }
