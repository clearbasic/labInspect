<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Check extends Validate{

    protected $rule = array(
        'check_level'  		            => 'require',
        'task_id'  		                => 'require',
        'org_id'  		                => 'require',
        'dt_begin'  		            => 'require',
        'dt_end'  		                => 'require',
    );
    protected $message = array(
        'check_level.require'    	    => '检查级别必须填写',
        'task_id.require'    	        => '检查安排必须选择',
        'org_id.require'    	        => '实验室必须选择',
        'dt_begin.require'    	        => '单位开始时间必须选择',
        'dt_end.require'    	        => '单位结束时间必须选择',
    );
}