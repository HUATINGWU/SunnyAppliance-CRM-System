<?php
namespace app\tongji\controller;

use think\Request;
use think\Controller;
use think\Db;


class Index extends Controller{

	public function index(){
		$DB = new Db;

		$data = $DB::query("select * from worker");
		// $this->assign('select',$data);
		// dump($data);
		return view();
	}
	public function produce(){
		$data= input("post.");

		$select=Db::query('SELECT AVG(or_price),or_pro_id,or_leibie from order_buy2 WHERE or_buy_time BETWEEN "'.$data['start'].'" and "'.$data['end'].'" 
		and or_leibie = "'.$data['setkind'].'" GROUP BY or_pro_id;
		');
		$this->assign('info',$select);
		return view('tongji');

	}
	public function popular(){
		$data= input("post.");
		if($data['setkind']=="最受欢迎"){
		$select=Db::query('SELECT or_pro_id,SUM(or_number) as sum from order_buy2 GROUP BY or_pro_id ORDER BY sum DESC LIMIT 5');
		$this->assign('info',$select);
		$this->assign('data',$data);
	}else{

	$select=Db::query('SELECT or_pro_id,SUM(or_number) as sum from order_buy2 GROUP BY or_pro_id ORDER BY sum  LIMIT 5');
		$this->assign('info',$select);
		$this->assign('data',$data);
	}

		return view('tongji1');


	}
	public function quality(){
		$data= input("post.");
		if($data['setkind']=="质量最好"){
		$select=Db::query('SELECT pro_id,bad_cou from badpro ORDER BY bad_cou LIMIT 5;');
		$this->assign('info',$select);
		$this->assign('data',$data);

	}else{

	$select=Db::query('SELECT pro_id,bad_cou from badpro ORDER BY bad_cou DESC LIMIT 5');
		$this->assign('info',$select);
		$this->assign('data',$data);
	}

		return view('tongji2');

	}
}

?>