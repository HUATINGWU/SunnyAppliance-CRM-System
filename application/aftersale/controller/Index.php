<?php
namespace app\aftersale\controller;

use think\Db;
use think\Controller;
use  think\Request;
use think\Cookie;
use app\aftersale\model\Order_buy;
use app\aftersale\model\Order_service;
use app\aftersale\model\Production;
use app\aftersale\model\Order_feedback;
class Index extends Controller
{
    public function index ()
    {   
            // if($request->isAjax()){

            // }      
            
        $mname= session('wk_username'); 
        $wkid=Db::query('select * from worker where wk_username="'.$mname.'"');
        // $this->assign('mname',$mname);

        // $after = After::paginate(5);
        // $page =$after ->render();
        // $this->assign('after',$after);
        // $this->assign('page',$page);
        // $show[]
        // $c = $service->alias('S')->join('Order_buy B', 'S.sv_or_id=B.or_id')->join('Production P', 'P.pro_id=B.or_pro_id')
        // ->field('S.*,B.*,P.*')->where("sv_wk_id" =>$wkid[0]['wk_id'])
        // ->order(case  when user_status = "vip" then 0 else 1 END,case when sv_status="办理中" THEN 0 else 1 END)->paginate(5);
        // $buy=new Order_buy();
        // $service=new Order_service();
        // $production=new Production();
        // $a=$buy->where
        $after=Db::query('select order_service.*,order_buy.*,`user`.* from order_service left join order_buy on order_service.sv_or_id = order_buy.or_id 
        left join user on order_buy.or_user_id = user_id where sv_wk_id="'.$wkid[0]['wk_id'].'"
         ORDER BY case  when user_status = "vip" then 0 else 1 END,case when sv_status="办理中" THEN 0 else 1 END;');
        // $b=$after->paginate(5);
        // $page= $c ->render();
        $this->assign('after',$after);
        // $this->assign('page',$page);

        //  Cookie::get('name');
        $feed = Order_feedback::paginate(5);
        $page=$feed->render();
        // $feed=Db::query('select * from order_feedback');
        $this->assign('feed',$feed);
        $this->assign('page',$page);
       
        return view();

    }
    public function searchid(Request $request){
 
        $set = input("post.");
     
        $orinfo=Db::query('select * from order_buy where or_id="'.$set['id'].'"');
        $check=Db::query('select * from order_service where sv_or_id="'.$set['id'].'"');
        if($orinfo == true){

        

       if($check == true){
           
        return 0;
       


       } else{
           $data = [
            "sv_id"=>"2",
            "sv_or_id"=>input("post.id"),
            "sv_type"=>input("post.kind"),
            "sv_info"=>input("post.details"),
            "sv_wk_id"=>"13",
            "sv_status"=>"办理中"
        ];
        $mname= session('wk_username'); 
        $wkid=Db::query('select * from worker where wk_username="'.$mname.'"');
        // $sql = 'select * from order_service';
        // $wk=$wkid[0]['wk_id'];
        $sql = 'insert into order_service(sv_id,sv_or_id,sv_type,sv_info,sv_wk_id,sv_status) 
        values (get_trans_num(),"'.$data['sv_or_id'].'","'.$data['sv_type'].'","'.$data['sv_info'].'","'.$wkid[0]['wk_id'].'","办理中")';
        $res = Db::execute($sql);
        $afternew=Db::query('select * from order_service where sv_or_id="'.$set['id'].'"');
        // if ($res) {
        //     return 1;
        // } else {
        //     return 0;
        // }        

        // Db::table("order_service")->insert($insert);


        // Db::execute('insert into order_service (sv_id,sv_or_id,sv_type,sv_info,sv_wk_id,sv_status) values (?,?,?,?,?,?)',["2",$set.id,$set.kind,$set.details,"123","办理中"]);

            // "1","'.$set['id'].'","'.$set['kind'].'","'.$set['details'].'","123","办理中"")');
        $fd=['sd'];
        $data = [
            "d" =>$afternew,
            "s" =>$orinfo
        ];
        return $data;
        // dump($ornumber);
       
         }
    } else{
        return 6;
    }
    }
    public function feedback(Request $request){
        $back = input("post.");
        $service=Db::query('select * from order_service where sv_id="'.$back['id'].'"');
        if($service[0]["sv_status"] == "办理中"){

        $order=Db::query('select * from order_buy where or_id= "'.$service[0]['sv_or_id'].'"');
        
        $product=Db::query('select * from production where pro_id= "'.$order[0]['or_pro_id'].'" ');

        $data = [
            "a" =>$service,
            "b" =>$order,
            "c"=>$product

        ];
        return  $data;
        }else{
            return 0;
        }
     
        

    }
    public function submitback(Request $request){
        $submit = input("post.");
        $service=Db::query('select * from order_service where sv_id="'.$submit['id'].'"');
        
        if(empty($submit['charge'])){
            return 0;
        }else if(empty($submit['deal'])){
            return 8;

        }else{

        $sql = 'insert into order_feedback(fd_id,fd_sv_id,fd_type,fd_info,fd_wk_id,fd_cost,fd_status) 
        values (get_trans_num2(),"'.$submit['id'].'","'.$service[0]['sv_type'].'","'.$submit['deal'].'","'.$service[0]['sv_wk_id'].'","'.$submit['charge'].'","'.$submit['verify'].'")';
        $res = Db::execute($sql);

        if($res == true){

            $updateservice='update order_service set sv_status="已办结" where sv_id= "'.$submit['id'].'" ';
            $resupdate=Db::execute($updateservice);
            if($resupdate == true){
                $number=Db::query('select * from order_feedback where fd_sv_id="'.$submit['id'].'"');
                return $number;
            }

        };
        }

   
    }
    public function zhuxiao(){
        session(null);
        return $this->success('注销成功','index/Home/index');
    }
//     public function jiazai(){

//     	$view=new \think\View;
//     	return $view->fetch();
//     	// return view();
//     }
// //查询数据
//     //1.
//         public function data(){
//     	//实例化系统数据库类
//     	$DB=  new Db;//先use
//     	//法1：查询数据
//     	//$data=$DB::table("user")->select();
//     	//法儿2：使用sql语句
//     	$data=$DB::query("select * from user");




//     	dump($data);
//     }

}
