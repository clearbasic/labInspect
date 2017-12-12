<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Task extends Validate{

    protected $rule = array(
        'task_name'  		        => 'require',
        'plan_id'  		            => 'require',
        'dt_begin'                  => 'date',
        'dt_end'                    => 'date',
        'dt_end'                    => 'after:dt_begin',
    );
    protected $message = array(
        'task_name.require'    	    => '安排名称必须填写',
        'plan_id.require'    	    => '期次ID必须选择',
        'dt_begin.date'    	        => '开始时间不合法',
        'dt_end.date'    	        => '结束时间不合法',
        'dt_end.after'    	        => '结束时间不能早于开始时间',
    );
}