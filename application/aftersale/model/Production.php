<?PHP
    namespace app\aftersale\model;
    
    use think\Model;
   
    class Production extends Model{
        // $after=Db::query('select order_service.*,order_buy.*,`user`.* from order_service left join order_buy on order_service.sv_or_id = order_buy.or_id left join user on order_buy.or_user_id = user_id where sv_wk_id="'.$wkid[0]['wk_id'].'" ORDER BY case  when user_status = "vip" then 0 else 1 END,case when sv_status="办理中" THEN 0 else 1 END;');

       

    }