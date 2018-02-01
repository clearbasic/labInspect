<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Person extends Validate{

    protected   $rule = [
        ['username','require|unique:dc_person|alphaDash|length:4,25', '用户名必须填写|用户名已存在|请输入正确的用户名(为字母和数字，下划线_及破折号-)|用户名长度必须在4--20位之间'],
        ['name','require','真实姓名必须填写'],
        ['password','require|alphaDash|length:4,25', '密码不能为空|请输入正确的密码(为字母和数字，下划线_及破折号-)|密码长度必须在4--20位之间'],
        ['repassword','require|confirm:password', '确认密码必须填写|两次密码输入不一致'],
        ['org_id','require', '所属单位必须填写'],
        ['mobile','regex:1[34578]\d{9}', '请输入正确的手机号码'],
        ['email','email', '请填写正确的邮箱'],
        ];
}