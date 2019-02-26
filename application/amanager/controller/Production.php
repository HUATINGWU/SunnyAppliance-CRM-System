<?php
namespace app\amanager\controller;
use think\Controller;
use think\Request;
use app\common\model\Worker as WorkerModel;
use app\common\model\Production as ProModel;
class Production extends  Controller
{
    public function production()
    {
        $username= session('wk_username');
        $worker = new WorkerModel();
        $worker_name = [
            'wk_username' => $username,
        ];
        $result = $worker->where($worker_name)->value('wk_branch');
        $branch = new ProModel();
        $br_name = [
            'pro_branch' => $result,
        ];
        $data = $branch->where($br_name)->paginate(2);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
        
    }


    
    
}