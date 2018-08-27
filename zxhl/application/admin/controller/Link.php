<?php
namespace app\admin\controller;
use app\admin\model\Link as LinkModel;
use think\Controller;
use think\Db;
use think\Request;

/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/5/29
 * Time: 14:08
 */
class Link  extends Comm{
    public function lis()
    {

		$Link=db('link')->order("id asc")->paginate(3);
		$this->assign('Link',$Link);
        return view('Link\link');
    }

    public function addlink()
    {
		if(request()->isPost()) {
			$data=input('post.');
			if($_FILES['nav_img']['tmp_name']){
				$data['nav_img']=$this->upload();//图片上传
			}
			if(stripos($data['nav_addres'],'http://')===false){
				$data['nav_addres']='http://'.$data['nav_addres'];
			}
			$res=db('link')->insert($data);
			if($res){
				$this->success('添加链接成功','lis');
			}else{
				$this->error('添加链接失败');
			}
		}

        return view('Link\addlink');
    }
	//更新操作
	public function edit(){
		$id=input('id');
		if(request()->isPost()){
			$date=input('post.');
			if($_FILES['nav_img']['tmp_name']){
				//删
				$filename=db('link')->field('nav_img')->find($date['id']);
				if(isset($filename)){
					$img=$filename['nav_img'];
					$file=PICTURE.$img;
					@unlink($file);
				}
				//改
				$date['nav_img']=$this->upload();
			}
			if(stripos($date['nav_addres'],'http://')===false){
				$date['nav_addres']='http://'.$date['nav_addres'];
			}
			$res=db('link')->where('id',$date['id'])->update($date);
			if($res!==false){
				$this->success('修改链接成功','lis');
			}else{
				$this->error('修改链接失败');
			}
		}
		$Link=db('link')->find($id);
		$this->assign('Link',$Link);
		return view('Link\editlink');
		}

	public function del(){
		$id=input('id');
		$filename=db('link')->field('nav_img')->find($id);
		if(isset($filename)){
			$img=$filename['nav_img'];
			$file=PICTURE.$img;
			@unlink($file);
		}
		$res =db('link')->delete($id);
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}

	}

//ajax函数
//看懂了么   就是由后台拼接而不是前台
	public  function Linkres(){
		$data = input('key');
		if(request()->isAjax()){
			$Lin = Db::table('link')->where('nav_name', 'like', '%' . $data . '%')->order('nav_pai asc')->select();
			$l = '';
			foreach($Lin as $k=>$v){
				$name=$v['nav_name'];
				$type=$v['nav_type'];
				$addres=$v['nav_addres'];
				$sort=$v['nav_pai'];
				$img=$v['nav_img'];
				$id=$v['id'];
				$l .="<tr>";
				$l .="<td><label><input class='checkboxes'  type='checkbox'  name='check' id='check' ><span class='text'></span></label></td>";
				$l .="<td style='text-align: center'  width='15%'>$name</td>";
				$l .="<td style='text-align: center' width='20%'>$addres</td>";
				$l .="<td style='text-align: center' width='20%'>
					<img src='#' style='width=100px; height=40px'>
				</td>";
				$l .="<td style='text-align: center' width='7.5%'>1</td>";
				$l .="<td style='text-align: center' width='7.5%'>2</td>";
				$l.="<td style='text-align: center' width='10%'>
						<input type='text' value='$sort' name='sort[$id]' style='width:30px;height: 30px;text-align: center'>
					</td>";
				$l.=" <td style='text-align: center' width='20%'>
                       <a href='del ? id = $id'><button class='btn shiny btn-danger' type='button'>删除</button></a>
                       <a href='edit ? id = $id'><button class='btn shiny btn-blue ' type='button'>编辑</button></a>
                       </td>";
				$l .="</tr>";

		}

			$l.="<tr>";
			$l.="<td colspan='8' height='20px'>
					<button class='btn shiny btn-yellow' type='submit' tooltip='排序' style='margin-left: 74%'>排序</button>
					</td>";
			$l.="</tr>";

			return $l;
		}
	}

	//批量修改操作
	public  function batch(){
		$data=input('post.');
		if($data['action']=='del_all'){
			$ids=implode(',',$data['checkbox']);
			$filename=db('link')->field('nav_img')->find($ids);
			if(isset($filename)){
				$img=$filename['nav_img'];
				$file=PICTURE.$img;
				@unlink($file);
			}
			$res=db('link')->delete($ids);
			if($res){
				$this->success('批量删除成功','lis');
			}else{
				$this->error('删除失败');
			}

		}elseif($data['action']=='category_move'){
			foreach($data['checkbox'] as $key=>$value){
				$Art=db('link')->where("id"==$value)->select();
				foreach($Art as $k=>$v){
					$Article=new LinkModel();
					$res=$Article->update(['id'=>$value,'nav_type'=>$data['new_cat_id']]);
				}

			}if($res){
				$this->success('转移成功','lis');
			}else{
				$this->error('转移失败','lis');

			}
		}
		else{

			$sort=$data['sort'];
			foreach($sort as $key=>$value){
				db('link')->where('id',$key)->setField('nav_pai',$value);
			}
			$Link=db('link')->order("nav_pai asc,id")->paginate(10);
			$this->assign('Link',$Link);
			return view('Link\link');
		}
	}

	//文件上传
	public function upload(){
		$file = request()->file('nav_img');
		$info = $file->move(ROOT_PATH . 'public' .DS.'static'.DS.'uploads');
		if($info){
			return $info->getSaveName();
		}else{
			echo $file->getError();
			die;
		}
	}
}