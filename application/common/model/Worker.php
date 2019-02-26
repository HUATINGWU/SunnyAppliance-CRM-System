<?php
namespace app\common\model;
use think\Model;

class Worker extends Model
{
    protected $auto = ['wk_password'];
    
    // protected function setRepasswordAttr($value){
    //     return md5($value);
    // }
    
}