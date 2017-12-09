<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Task extends Validate{

    protected $rule = array(
        'task_name'  		        => 'require|unique:ck_task',
        'plan_id'  		            => 'require',
        'dt_begin'                  => 'date',
        'dt_end'                    => 'date',
    );
    protected $message = array(
        'task_name.require'    	    => '安排名称必须填写',
        'task_name.unique'    	    => '安排名称已存在',
        'plan_id.require'    	    => '期次ID必须选择',
        'dt_begin.date'    	        => '开始时间不合法',
        'dt_end.date'    	        => '结束时间不合法',
    );
}