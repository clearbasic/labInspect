<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Org extends Validate{

    protected $rule = array(
        'pid'  		=> 'require',
        'org_name'  		    => 'require|unique:dc_org',
        'org_level'             => 'require',
    );
    protected $message = array(
        'pid.require'    	        => '缺少父级ID',
        'org_level.require'    	    => '单位级别必须选择',
        'org_name.require'    	    => '单位名称必须填写',
        'org_name.unique'    	    => '单位名称已存在',
    );
}