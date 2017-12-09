<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Plan extends Validate{

    protected $rule = array(
        'plan_name'  		     => 'require|unique:ck_plan',
        'plan_score'             => 'require',
    );
    protected $message = array(
        'plan_name.require'    	    => '期次名称必须填写',
        'plan_name.unique'    	    => '期次名称已存在',
        'plan_score.require'    	    => '总分必须填写',
    );
}