<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Room extends Validate{

    protected $rule = array(
        'dept_id'  		     => 'require',
        'lab_id'             => 'require',
        'zone_id'             => 'require',
        'room_name'             => 'require',
//        'agent_name'             => 'require',
    );
    protected $message = array(
        'dept_id.require'    	    => '所属学院必须填写',
        'lab_id.require'    	    => '所属实验室必须填写',
        'zone_id.require'    	    => '所属分组必须填写',
        'room_name.require'    	    => '房间名称必须填写',
//        'agent_name.require'    	=> '安全责任人必须填写',
    );
}