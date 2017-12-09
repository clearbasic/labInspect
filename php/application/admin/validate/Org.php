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
        'org_level'            => 'require',
    );
    protected $message = array(
        'pid.require'    	=> '缺少父级ID',
        'item_level.require'    	=> '指标重要级别必须填写',
        'item_type.require'    	    => '指标类型必须填写',
        'item_name.require'    	    => '指标项必须填写',
        'item_name.unique'    	    => '指标项已存在',
    );
}