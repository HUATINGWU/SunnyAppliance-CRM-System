<?php
namespace app\common\validate;
use think\Validate;
class Register extends Validate{
    protected $rule = [
        'wk_name|姓名'=>'require',
        'wk_username|用户名'=>'require|min:6',
        'wk_password|密码'=>'require|min:6|confirm:repassword',
    ];
    protected $message = [
        'wk_name.require'=>'姓名不能为空',
        'wk_username.require'=>'用户名不能为空',
        'wk_password.require'=>'密码不能为空',
        'wk_username.min'=>'用户名长度不能少于6位',
        'wk_password.min'=>'密码长度不能少于6位',
        'wk_password.confirm'=>'两次密码不一致',
    ];
}