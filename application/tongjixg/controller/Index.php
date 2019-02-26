<?php
    namespace app\tongji\controller;

    use think\Db;
    use think\Controller;
    use  think\Request;
    class Index extends controller{
        public function index(){
            $info = Db::table('order_buy')->select();
        //var_dump($info);
        // dump($info);
        $this->assign('info',$info);
        return $this->fetch();
        }
    }