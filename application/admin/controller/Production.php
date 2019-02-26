<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;
use app\common\model\Production as ProModel;
class Production extends  Base
{
    
	public function dianshi() {

		$pro_branch = [
            'pro_branch' => '电视事业部',
        ];
        $data = ProModel::where($pro_branch)->paginate(2);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
		return $this->fetch();

	}
    public function kongtiao() {

		$pro_branch = [
            'pro_branch' => '空调事业部',
        ];
        $data = ProModel::where($pro_branch)->paginate(2);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
		return $this->fetch();
	}

	public function xiyiji() {

		$pro_branch = [
            'pro_branch' => '洗衣机事业部',
        ];
        $data = ProModel::where($pro_branch)->paginate(2);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
		return $this->fetch();
	}
	public function diannao(){

		$pro_branch = [
            'pro_branch' => '电脑事业部',
        ];
        $data = ProModel::where($pro_branch)->paginate(2);
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
        // $submit = new ProModel($data);
        // $res = $submit->save($data);
        $sql = 'insert into production(pro_id,pro_name,pro_branch,pro_info,pro_cost,pro_seal,pro_unit,pro_color,pro_buy_way) 
        values(get_trans_num4(),"'.$data['pro_name'].'","'.$data['pro_branch'].'","'.$data['pro_info'].'","'.$data['pro_cost'].'","'.$data['pro_seal'].'","'.$data['pro_unit'].'","'.$data['pro_color'].'","'.$data['pro_buy_way'].'")';
        $ret = Db::execute($sql);
        
        if($ret) {
            $this->success('新增成功');
        }else {
            $this->error('新增失败');
        }
    }
    //编辑信息
    public function edit() {
        $pro_id = input('get.pro_id');
        $data = ProModel::get($pro_id);
        $this->assign('data',$data);
        return $this->fetch();
        
    }
    // 更新信息
    public function update() {
		$data = input('post.');
		$pro_id = input('post.pro_id');
		
        $update = new ProModel();
        $res = $update->save($data,['pro_id'=>$pro_id]);
        if($res) {
            $this->success('修改成功');
        }else {
            $this->error('修改失败');
        }
        
    }
    //删除信息
    public function delete() {
        $pro_id = input('get.pro_id');
        $ret = ProModel::destroy($pro_id,true);
        if($ret){
            $this->success('删除成功');
        }else {
            $this->error('删除失败');
        }
    }

}
