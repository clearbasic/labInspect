<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class CkRule extends Validate{

    protected $rule = array(
        'plan_id'  		            => 'require',
        'level'  		            => 'require',
    );
    protected $message = array(
        'plan_id.require'    	    => '必须选择一个期次',
        'level.require'    	        => '单位级别必须选择',
    );
}