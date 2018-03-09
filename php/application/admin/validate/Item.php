<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Item extends Validate{

    protected $rule = array(
        'checklist_id'  		=> 'require',
        'item_level'            => 'require',
        'item_name'  		    => 'require|unique:ck_item',
        'item_type'             => 'require',
    );
    protected $message = array(
        'checklist_id.require'    	=> '指标类别必须填写',
        'item_level.require'    	=> '指标重要级别必须填写',
        'item_type.require'    	    => '指标类型必须填写',
        'item_name.require'    	    => '指标项必须填写',
        'item_name.unique'    	    => '指标项已存在',
    );
}