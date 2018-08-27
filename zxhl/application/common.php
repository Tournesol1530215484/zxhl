<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//截取字符串
function cut_text($obj,$len){
    if(mb_strlen($obj,'utf-8')>$len){
        return (mb_substr($obj,0,$len,'utf-8').'.....');
    }else{
        return $obj;
    }
}

//查找出子栏目
function getsub($pid){
    $pids=db('servercate')->where(array('pid'=>$pid))->select();
    return $pids;
}




