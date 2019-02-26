<?php
namespace app\amanager\controller;
use think\Controller;
use think\Request;
use app\common\model\Worker;
class Index extends Controller
{
    public function index()
    {
        $username= session('wk_username');
        $worker = new Worker();
        $worker_name = [
            'wk_username' => $username,
        ];
        $head_image = $worker->where($worker_name)->value('wk_head_image');
        $this->assign('head_image',$head_image);
        return $this->fetch();
    }
    public function welcome(){
        return $this->fetch();
    }
    public function upload(){
        // $wk_head_image = input('post.');
        
        $file = request()->file('wk_head_image');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            if($info){
                $filetype = $info->getExtension();//文件类型
                
                $filepath =  $info->getSaveName();//文件路径
                
                $filename =  $info->getFilename(); //文件名

                $username= session('wk_username');
                $worker = new Worker();
                $worker_name = [
                      'wk_username' => $username,
                ];
                $path = ['wk_head_image'=>$filepath];
                $head_image = $worker->save($path,$worker_name);
                $this->success('更换成功');
            }else{
                echo $file->getError();
            }
        }
        

    }
}