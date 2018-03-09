<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class CkGroup extends Validate{

    protected $rule = array(
        'group_name'  		            => 'require',
        'level'  		                => 'require',
        'org_id'  		                => 'require',
        'leader_name'  		            => 'require',
    );
    protected $message = array(
        'group_name.require'    	    => '小组名称必须填写',
        'level.require'    	            => '单位级别必须选择',
        'org_id.require'    	        => '单位必须选择',
        'leader_name.require'    	    => '组长必须填写',
    );
}