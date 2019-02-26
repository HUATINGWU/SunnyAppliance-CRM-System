<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\common\model\Worker;
use app\common\model\Admin;
use app\common\validate\Register as WorkerValidate;

class Home extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function login()
    {
        return $this->fetch();
    }
    public function register()
    {
        return $this->fetch();
    }
    //退出登录
    public function logout(){
        session(null);
        $this->success('退出登录成功','index/Home/index');
    }
    public function check()
    {
        date_default_timezone_set("Asia/Shanghai");
        

        $data = input('post.');
        $admin = new Admin();
        $worker = new Worker();
        $result = $worker->where('wk_username',$data['wk_username'])->find();
        $res = $admin->where('sys_name',$data['wk_username'])->find();
        if($result){
            if($result['wk_password'] === md5($data['wk_password'])){
                session('wk_username',$data['wk_username']);
            }else{
                //登陆三次验证:
                $now = date('Y-m-d H:i:s');
                $frequency = $result['wk_loginFrequency'];
                $time = $result['wk_time'];
                $difference = ceil((strtotime($now)-strtotime($time))/60);//相差分钟数
                if(!empty($result)){
                    //时间间隔大于30
                    if ($difference>=30){

                        //更新时间和尝试次数
                        $result->wk_loginFrequency=0;
                        $result->wk_time=$now;
                        $frequency=0;
                        $result->save();
                    }
                    //时间间隔小于30，并且尝试次数大于等于3
                    if ($difference<=30&&$frequency>=3){
                        //登陆失败
                        $request = Request::instance();
                        $ip = $request->ip();
                        if($ip == $result['wk_ip']){
                            return $this->error("密码错误次数超过3次，请30分钟后重新尝试或者换台电脑登录！");
                        }else{
                            $result->wk_loginFrequency=0;
                            $result->wk_time=$now;
                            $frequency=0;
                            $result->save();
                        }
                         
                    }
                    //密码错误，尝试次数+1，时间更新为当前时间
                    $result->wk_loginFrequency=$frequency+1;
                    $result->wk_time=$now;
                    $result->save();
                    return $this->error("密码不正确");
                } 
                
            }
        }else{
            if($res){
                if($res['sys_password'] === md5($data['wk_password'])){
                     session('sys_name',$data['wk_username']);
                }else{
                    //登陆三次验证:

                     $this->error("密码不正确");
                }
            }else{
                $this->error("系统管理员用户名不存在");
            
            }
            if($res['sys_position'] == $data['wk_position']){
                
                $request = Request::instance();
                $current_ip = $request->ip();
                $current_time = time();
                $datetime = date('Y-m-d H:i:s',$current_time);
    
                $sys_ip = ['sys_ip'=>$current_ip,];
                $sys_time = ['sys_last_loginTime'=>$datetime,];
                $update = new Admin();
                $res_ip = $update->save($sys_ip,['sys_name'=>$data['wk_username']]);
                $res_time = $update->save($sys_time,['sys_name'=>$data['wk_username']]);

                $this->success('登录成功！系统管理员','admin/index/index');
            
            }else{
                $this->error("职位错误");
            
            }
            $this->error("用户名不存在");
            
        }
        if($result['wk_position'] == $data['wk_position']){

            $request = Request::instance();
            $current_ip = $request->ip();
            $current_time = time();
            $datetime = date('Y-m-d H:i:s',$current_time);

            $wk_ip = ['wk_ip'=>$current_ip,];
            $wk_time = ['wk_recent_loginTime'=>$datetime,];
            $update = new Worker();
            $res_ip = $update->save($wk_ip,['wk_username'=>$data['wk_username']]);
            $res_time = $update->save($wk_time,['wk_username'=>$data['wk_username']]);
            
            //浏览器
            $brower = $_SERVER['HTTP_USER_AGENT']; 
    if(preg_match('/360SE/', $brower)){ 
        $brower = "360se"; 
    } 
    elseif (preg_match('/Maxthon/', $brower)){ 
        $brower = "Maxthon"; 
    } 
    elseif (preg_match('/Tencent/', $brower)){ 
        $brower = "Tencent Brower"; 
    } 
    elseif (preg_match('/Green/', $brower)){ 
        $brower = "Green Brower"; 
    } 
    elseif (preg_match('/baidu/', $brower)){ 
        $brower = "baidu"; 
    } 
    elseif (preg_match('/TheWorld/', $brower)){ 
        $brower = "The World"; 
    } 
    elseif (preg_match('/MetaSr/', $brower)){ 
        $brower = "Sogou Brower"; 
    } 
    elseif (preg_match('/Firefox/', $brower)){ 
        $brower = "Firefox"; 
    } 
    elseif (preg_match('/MSIE\s6\.0/', $brower)){ 
        $brower = "IE6.0"; 
    } 
    elseif (preg_match('/MSIE\s7\.0/', $brower)){ 
        $brower = "IE7.0"; 
    } 
    elseif (preg_match('/MSIE\s8\.0/', $brower)){ 
        $brower = "IE8.0"; 
    } 
    elseif (preg_match('/MSIE\s9\.0/', $brower)){ 
        $brower = "IE9.0"; 
    } 
    elseif (preg_match('/Netscape/', $brower)){ 
        $brower = "Netscape"; 
    } 
    elseif (preg_match('/Opera/', $brower)){ 
        $brower = "Opera"; 
    } 
    elseif (preg_match('/Chrome/', $brower)){ 
        $brower = "Chrome"; 
    } 
    elseif (preg_match('/Gecko/', $brower)){ 
        $brower = "Gecko"; 
    } 
    elseif (preg_match('/Safari/', $brower)){ 
        $brower = "Safari"; 
    } 
    else{ 
        $brower = "Unknow browser"; 
    }
    $wk_loginBrower = ['wk_loginBrower'=>$brower,];
    $res = $update->save($wk_loginBrower,['wk_username'=>$data['wk_username']]);
    
            if($data['wk_position'] == "售后人员"){

                $now = date('Y-m-d H:i:s');
                $result->wk_loginFrequency=0;
                $result->wk_time=$now;
                $frequency=0;
                $result->save();

                $this->success('登录成功！售后人员','aftersale/index/index');
            }else if($data['wk_position'] == "销售人员"){

                $now = date('Y-m-d H:i:s');
                $result->wk_loginFrequency=0;
                $result->wk_time=$now;
                $frequency=0;
                $result->save();

                $this->success('登录成功！销售人员','asaleman/index/index');
            }else if($data['wk_position'] == "部门经理"){

                $now = date('Y-m-d H:i:s');
                $result->wk_loginFrequency=0;
                $result->wk_time=$now;
                $frequency=0;
                $result->save();

                $this->success('登录成功！部门经理','amanager/index/index');
            }
        }else{
            $this->error("职位错误");
            
        }
        
    }
    public function registerSave(){
        $data = input('post.');
        $val = new WorkerValidate;
        if(!$val->check($data)){
            $this->error($val->getError());
            exit;
        }
        $user = new Worker($data);
        // $ret = $user->save($data);

        $sql = 'insert into worker(wk_id,wk_name,wk_username,wk_password,repassword,wk_position,wk_branch) 
        values(get_trans_num3(),"'.$data['wk_name'].'","'.$data['wk_username'].'","'.$data['wk_password'].'","'.$data['repassword'].'","'.$data['wk_position'].'","'.$data['wk_branch'].'")';
        $ret = Db::execute($sql);

        if($ret) {
            $md5_pwd = md5($user['wk_password']);
            $md5_repwd = md5($user['repassword']);
            $wk_password = ['wk_password'=>$md5_pwd];
            $repassword =  ['repassword'=>$md5_repwd];
            $res_pwd = $user->save($wk_password,['wk_username'=>$data['wk_username']]);
            $res_repwd = $user->save($repassword,['wk_username'=>$data['wk_username']]);

            $file = request()->file('wk_head_image');
            if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            if($info){
                $filetype = $info->getExtension();//文件类型               
                $filepath =  $info->getSaveName();//文件路径        
                $filename =  $info->getFilename(); //文件名
                $imagePath = "__HEAD__\\".$filepath;
                $worker_name = [
                      'wk_username' => $data['wk_username'],
                ];
                $path = ['wk_head_image'=>$imagePath];
                $worker = new Worker();
                $head_image = $worker->save($path,$worker_name);
            }else{
                echo $file->getError();
            }
            }

            $this->success('注册成功','Home/login');
        }else {
            $this->error('注册失败');
        }
        
        
    }
}