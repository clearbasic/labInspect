<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Plan extends Validate{

    protected  $rule = [
        ['plan_name','require|unique:ck_plan', '期次名称必须填写|期次名称已存在'],
        ['plan_score','require|number', '总分必须填写|请填写正确的总分'],
    ];

}