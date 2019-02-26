<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\common\model\User as UserModel;
class User extends  Base
{
    public function user()
    {
       
        $data = UserModel::paginate(2);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
       
        return $this->fetch();
        
    }

    public function add(){
        return $this->fetch();
    }

    
    
    //删除信息
    public function delete() {
        $user_id = input('get.user_id');
        $ret = UserModel::destroy($user_id,true);
        if($ret){
            $this->success('删除成功');
        }else {
            $this->error('删除失败');
        }
    }
}