<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Person extends Validate{

    protected $rule = array(
        'username'  		=> 'require|unique:dc_person',
        'name'  		    => 'require',
        'password'      	=> 'require',
        'repassword'        => 'require|confirm:password',
        'org_id'      	    => 'require',
        'mobile'      	    => 'require',
        'email'      	    => 'email',
    );
    protected $message = array(
        'username.require'    	=> '用户名必须填写',
        'username.unique'    	=> '用户名已存在',
        'name.require'    	    => '真实姓名必须填写',
        'password.require'    	=> '密码必须填写',
        'repassword.require'    => '确认密码必须填写',
        'repassword.confirm'    => '两次密码输入不一致',
        'org_id.require'    	=> '所属单位必须填写',
        'mobile.require'    	=> '联系电话必须填写',
        'email.email'    	    => '请填写正确的邮箱',
    );
}