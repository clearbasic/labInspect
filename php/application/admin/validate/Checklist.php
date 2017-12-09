<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Checklist extends Validate{

    protected $rule = array(
        'name'  		=> 'require|unique:ck_checklist',
    );
    protected $message = array(
        'name.require'    	=> '指标类别必须填写',
        'name.unique'    	=> '指标类别已存在',
    );
}