<?php
namespace app\amanager\controller;
use think\Controller;
use think\Request;
use app\common\model\Worker as WorkerModel;
use app\amanager\model\Branch as BranchModel;
class Worker extends  Controller
{
    public function aftersaleman()
    {
        
        $position = [
            'wk_position' => '售后人员',
        ];

        $username= session('wk_username');
        $worker = new WorkerModel();
        $worker_name = [
            'wk_username' => $username,
        ];
        $result = $worker->where($worker_name)->value('wk_branch');

        $br_name = [
            'wk_branch' => $result,
        ];
        
        $data = $worker->where($position)->where($br_name)->paginate(2);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
       
        return $this->fetch();
        
    }

    public function salesman()
    {

        $position = [
            'wk_position' => '销售人员',
        ];
        $username= session('wk_username');
        $worker = new WorkerModel();
        $worker_name = [
            'wk_username' => $username,
        ];
        $result = $worker->where($worker_name)->value('wk_branch');

        $br_name = [
            'wk_branch' => $result,
        ];
        
        $data = $worker->where($position)->where($br_name)->paginate(2);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }

    
    //编辑信息
    public function edit() {
        $wk_id = input('get.wk_id');
        $data = WorkerModel::get($wk_id);
        $this->assign('data',$data);
        return $this->fetch();
        
    }
    // 更新信息
    public function update() {
        $data = input('post.');
        $wk_id = input('post.wk_id');
        $update = new WorkerModel();
        $res = $update->save($data,['wk_id'=>$wk_id]);
        if($res) {
            $this->success('修改成功');
        }else {
            $this->error('修改失败');
        }
        
    }
    
}
