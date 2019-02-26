<?php
namespace app\statistic\controller;
use think\Controller;
use think\Db;
class Index extends Controller
{
    public function index()
    {
        $select=Db::query('SELECT or_wk_id ,SUM(or_price * or_number) as sum from order_buy2 GROUP BY or_wk_id ORDER BY sum desc');
     $this ->assign("select",$select);

    // $select=db::name(user_id)->find();
    // $this->assign('select',$select);

    //  dump($select);
        return $this->fetch('wk');
    }

    public function to_productions()
    {

        $select=Db::query('SELECT or_pro_id ,SUM(or_number) as sum from order_buy2 GROUP BY or_pro_id');
        $this ->assign("select",$select);
           return $this->fetch('products');    

    }


    public function by_or_id()
    {

        $select=Db::query('select * from order_buy2 order by or_id');
        $this ->assign("select",$select);
           return $this->fetch('index');    

    }
    public function by_or_pro_id()
    {

        $select=Db::query('select * from order_buy2 order by or_pro_id');
        $this ->assign("select",$select);
           return $this->fetch('index');    

    }
    public function by_or_user_id()
    {

        $select=Db::query('select * from order_buy2 order by or_user_id');
        $this ->assign("select",$select);
           return $this->fetch('index');    

    }
    public function by_or_wk_id()
    {

        $select=Db::query('SELECT or_wk_id ,SUM(or_price * or_number) as sum from order_buy2 GROUP BY or_wk_id');
        $this ->assign("select",$select);
           return $this->fetch('wk');    

    }
    public function by_or_buy_time()
    {

        $select=Db::query('select * from order_buy2 order by or_buy_time');
        $this ->assign("select",$select);
           return $this->fetch('index');    

    }
    public function by_or_number()
    {

        $select=Db::query('select * from order_buy2 order by or_number');
        $this ->assign("select",$select);
           return $this->fetch('index');    


    }
    public function by_or_price()
    {

        $select=Db::query('select * from order_buy2 order by or_price');
        $this ->assign("select",$select);
           return $this->fetch('index');    

    }
    public function by_count()
    {
 
        $select=Db::query('select * from order_buy2 order by or_number * or_price');
        $this ->assign("select",$select);
           return $this->fetch('index');    

    }




    //guolv

    public function bt_time()
    {
        $data1=input('post.');
        // $data2 = input('pset.table2');

        $select=Db::query('select * from order_buy2 where or_buy_time between "'.$data1['table1'].'" and "'.$data1['table2'].'"');
        // dump($select);
        $this ->assign("select",$select);
           return $this->fetch('index');    

    }
    public function bt_or_id()
    {
        $data1=input('post.');
        // $data2 = input('pset.table2');

        $select=Db::query('select * from order_buy2 where or_id between "'.$data1['table1'].'" and "'.$data1['table2'].'"');
        // dump($select);
        $this ->assign("select",$select);
           return $this->fetch('index');    

    }
    public function count_sum()
    {
        $data1=input('post.');
        // $data2 = input('pset.table2');

        $select=Db::query('SELECT or_wk_id ,SUM(or_price * or_number) as sum from order_buy2 GROUP BY or_wk_id HAVING sum>"'.$data1['table1'].'" and sum <"'.$data1['table2'].'"');
        // dump($select);
        $this ->assign("select",$select);
           return $this->fetch('wk');    

    }
    


    

    public function tbs()
    {
 
        $select=Db::query('SELECT or_pro_id ,SUM(or_number) as sum from order_buy2 GROUP BY or_pro_id ORDER BY sum desc LIMIT 5');
        $this ->assign("select",$select);
           return $this->fetch('products');    

    }
    public function tls()
    {
 
        $select=Db::query('SELECT or_pro_id ,SUM(or_number) as sum from order_buy2 GROUP BY or_pro_id ORDER BY sum LIMIT 5');
        $this ->assign("select",$select);
           return $this->fetch('product1');    

    }


    public function tlw()
    {
 
        $select=Db::query('select pro_id,bad_cou from badpro ORDER BY bad_cou limit 5;');
        $this ->assign("select",$select);
           return $this->fetch('product2');    

    }
    public function tws()
    {
 
        $select=Db::query('select pro_id,bad_cou from badpro ORDER BY bad_cou desc limit 5;');
        $this ->assign("select",$select);
           return $this->fetch('product3');    

    }




}