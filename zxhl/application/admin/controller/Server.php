<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Server as ServerModel;
use think\Db;
use Catetree\Catetree;
class Server extends Comm
{
    //模糊查询
    public function SelectServer()
    {
        if (request()->isPost()) {

            $date = input('post.');
            $pid = $date['pid'];
            $keywords = $date['keywords'];
            $limit = 10;
            $page = 1;
            $Server = Db::name('server')->alias('s')->where('pid', $pid)->where('keywords', 'like', "%{$keywords}%")->order('id desc')->paginate(7);
            $cate = new Catetree();
            $date = db('servercate')->field('id,pid,cate_name')->select();
            $Cate = $cate->ChildTree($date);

            $this->assign([
                'Server' => $Server,
                'Cate' => $Cate
            ]);
            return view('Server/server');
        }

    }

    //列表显示

    public function server()
    {
        $limit = 10;
        $page = 1;
        //服务列表
       // $Server = Db::table('server')->alias('s')->field('s.*,c.cate_name')->join('servercate c', 's.pid=c.id', 'left')->group('id')->limit($limit)->page($page)->select();
        $Server=Db::table('server')->order("sort asc,id")->paginate(7);
        //所属栏目列表
        $cate = new Catetree();
        $date = db('servercate')->field('id,pid,cate_name')->select();
        $Cate = $cate->ChildTree($date);

        $this->assign([
            'Server' => $Server,
            'Cate' => $Cate
        ]);
        return view('Server/server');
    }

    //添加服务
    public function serverAdd()
    {
        $cate = new Catetree();
        $date = db('servercate')->order('sort asc,id')->select();
        $Cate = $cate->ChildTree($date);
        $this->assign('Cate', $Cate);
        if (request()->isPost()) {
            $date = input('post.');
            $date['time'] = strtotime(date('Y-m-d', time()));
            if ($_FILES['image']['tmp_name']) {
                $date['image'] = $this->upload();//图片上传
            }
            $res = db('server')->insert($date);
            if ($res) {
                $this->success('添加成功', 'server');
            } else {
                $this->error('添加失败');
            }
        }
        return view('Server/serveradd');
    }

    //服务修改
    public function serverEdit(){
        if(request()->isPost()){
            $url='crede';
            $date=input('post.');
            if($_FILES['image']['tmp_name']){
                $filename=db('server')->field('image')->find($date['id']);
                if(isset($filename)){
                    $img=$filename['image'];
                    $file=PICTURE.$img;
                    @unlink($file);
                }
                $date['image'] = $this->upload();//图片上传
            }
            $res=db('server')->update($date);
            if($res){
                $this->success('修改服务成功','server');
            }else{
                $this->error('修改服务失败');
            }
        }

        $id=input('id');
        //所属栏目
        $cate = new Catetree();
        $date = db('servercate')->field('id,pid,cate_name')->select();
        $Cate = $cate->ChildTree($date);
        //具体内容
        $ServerRes=db('server')->find($id);
        $this->assign([
            'Cate' => $Cate,
            'ServerRes'=>$ServerRes,
        ]);
        return view('Server/serveredit');

    }


    //服务删除

    public function serverDel(){
        $id=input('id');
        $filename=db('server')->field('image')->find($id);
        if($_FILES['image']['tmp_name']){
            if(isset($filename)){
                $img=$filename['image'];
                $file=PICTURE.$img;
                @unlink($file);
            }
        }
        $res=db('server')->delete($id);
        if($res){
            $this->success('删除服务成功','server');
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
            $filename = db('server')->field('image')->find($ids);
            if (isset($filename)) {
                $img = $filename['image'];
                $file = PICTURE . $img;
                @unlink($file);
            }
            $res = db('server')->delete($ids);
            if ($res) {
                $this->success('批量删除成功', 'server');
            } else {
                $this->error('删除失败');
            }

        } elseif ($data['action'] == 'category_move') {
            foreach ($data['checkbox'] as $key => $value) {
                $Art = db('server')->where("id" == $value)->select();
                foreach ($Art as $k => $v) {
                    $res = db('server')->update(['id' => $value, 'pid' => $data['new_cat_id']]);
                }

            }
            if ($res!==false) {
                $this->success('转移成功', 'server');
            } else {
                $this->error('转移失败', 'server');

            }
        } else {
            $sort = $data['sort'];
            foreach ($sort as $key => $value) {
               $res= db('server')->where('id', $key)->setField('sort', $value);
            }

            $cate = new Catetree();
            $date = db('servercate')->field('id,pid,cate_name')->select();
            $Cate = $cate->ChildTree($date);
            $Server=Db::table('server')->order("sort asc,id")->paginate(7);
            $this->assign([
                'Server' => $Server,
                'Cate' => $Cate
            ]);
            return view('Server/server');
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
