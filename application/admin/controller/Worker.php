<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;
use app\common\model\Worker as WorkerModel;
class Worker extends  Base
{
    public function aftersaleman()
    {
        
        $position = [
            'wk_position' => '售后人员',
        ];
        $data = WorkerModel::where($position)->paginate(2);
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
        $data = WorkerModel::where($position)->paginate(2);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }

    public function manager() {
        $position = [
            'wk_position' => '部门经理',
        ];
        $data = WorkerModel::where($position)->paginate(2);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();

    }

    public function add(){
        return $this->fetch();
    }
    //存储信息
    public function save(){
        $data = input('post.');
        // $submit = new WorkerModel($data);
        // $res = $submit->save($data);

        $sql = 'insert into worker(wk_id,wk_name,wk_position,wk_branch) 
        values(get_trans_num3(),"'.$data['wk_name'].'","'.$data['wk_position'].'","'.$data['wk_branch'].'")';
        $ret = Db::execute($sql);

        if($ret) {
            $this->success('新增成功');
        }else {
            $this->error('新增失败');
        }
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
    //删除信息
    public function delete() {
        $wk_id = input('get.wk_id');
        $ret = WorkerModel::destroy($wk_id,true);
        if($ret){
            $this->success('删除成功');
        }else {
            $this->error('删除失败');
        }
    }
}
