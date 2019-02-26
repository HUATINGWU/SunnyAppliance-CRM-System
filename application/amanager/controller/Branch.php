<?php
namespace app\amanager\controller;
use think\Controller;
use think\Request;
use app\common\model\Worker;
use app\amanager\model\Branch as BranchModel;
class Branch extends Controller
{
    public function branch()
    {
        $username= session('wk_username');
        $worker = new Worker();
        $worker_name = [
            'wk_username' => $username,
        ];
        $result = $worker->where($worker_name)->value('wk_branch');
        $branch = new BranchModel();
        $br_name = [
            'br_name' => $result,
        ];
        $data = $branch->where($br_name)->paginate(2);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }

    //编辑信息
    public function edit() {
        $br_name = input('get.br_name');
        $branch = new BranchModel();
        $worker_name = [
            'br_name' => $br_name,
        ];
        $data = $branch->where($worker_name)->find();
        $this->assign('data',$data);
        return $this->fetch();
        
    }
    // 更新信息
    public function update() {
        $data = input('post.');
        $br_name = input('post.br_name');
        $update = new BranchModel();
        $res = $update->save($data,['br_name'=>$br_name]);
        if($res) {
            $this->success('修改成功');
        }else {
            $this->error('修改失败');
        }
        
    }
    
}