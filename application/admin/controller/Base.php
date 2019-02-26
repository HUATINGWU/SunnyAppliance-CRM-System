<?php
namespace app\admin\controller;
use think\Controller;
class Base extends Controller{

    public function _initialize(){
        if(!session('sys_name')){
            $this->error('请先完成登录操作','index/Home/index');
        }
    }
}