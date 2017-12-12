<?php
// +----------------------------------------------------------------------
// | Description: 基础框架路由配置文件
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honghaiweb.com>
// +----------------------------------------------------------------------

return [
    // 定义资源路由
    '__rest__'=>[
        'admin/rules'		   =>'admin/rules',
        'admin/groups'		   =>'admin/groups',
        'admin/users'		   =>'admin/users',
        'admin/menus'		   =>'admin/menus',
        'admin/structures'	   =>'admin/structures',
        'admin/posts'          =>'admin/posts',

        'admin/checklist'      =>'admin/checklist',
        'admin/item'           =>'admin/item',
        'admin/plan'           =>'admin/plan',
        'admin/task'           =>'admin/task',
        'admin/rule'           =>'admin/rule',
        'admin/org'            =>'admin/org',
        'admin/person'         =>'admin/person',
    ],

    // 【基础】登录
    'admin/base/login' => ['admin/base/login', ['method' => 'POST']],
    // 【基础】记住登录
    'admin/base/relogin'	=> ['admin/base/relogin', ['method' => 'POST']],
    // 【基础】修改密码
    'admin/base/setInfo' => ['admin/base/setInfo', ['method' => 'POST']],
    // 【基础】退出登录
    'admin/base/logout' => ['admin/base/logout', ['method' => 'POST']],
    // 【基础】获取配置
    'admin/base/getConfigs' => ['admin/base/getConfigs', ['method' => 'POST']],
    // 【基础】获取验证码
    'admin/base/getVerify' => ['admin/base/getVerify', ['method' => 'GET']],
    // 【基础】上传图片
    'admin/upload' => ['admin/upload/index', ['method' => 'POST']],
    // 保存系统配置
    'admin/systemConfigs' => ['admin/systemConfigs/save', ['method' => 'POST']],
    // 【规则】批量删除
    'admin/rules/deletes' => ['admin/rules/deletes', ['method' => 'POST']],
    // 【规则】批量启用/禁用
    'admin/rules/enables' => ['admin/rules/enables', ['method' => 'POST']],
    // 【用户组】批量删除
    'admin/groups/deletes' => ['admin/groups/deletes', ['method' => 'POST']],
    // 【用户组】批量启用/禁用
    'admin/groups/enables' => ['admin/groups/enables', ['method' => 'POST']],
    // 【用户】批量删除
    'admin/users/deletes' => ['admin/users/deletes', ['method' => 'POST']],
    // 【用户】批量启用/禁用
    'admin/users/enables' => ['admin/users/enables', ['method' => 'POST']],
    // 【菜单】批量删除
    'admin/menus/deletes' => ['admin/menus/deletes', ['method' => 'POST']],
    // 【菜单】批量启用/禁用
    'admin/menus/enables' => ['admin/menus/enables', ['method' => 'POST']],
    // 【组织架构】批量删除
    'admin/structures/deletes' => ['admin/structures/deletes', ['method' => 'POST']],
    // 【组织架构】批量启用/禁用
    'admin/structures/enables' => ['admin/structures/enables', ['method' => 'POST']],
    // 【部门】批量删除
    'admin/posts/deletes' => ['admin/posts/deletes', ['method' => 'POST']],
    // 【部门】批量启用/禁用
    'admin/posts/enables' => ['admin/posts/enables', ['method' => 'POST']],

    // 【检查指标体系】类别列表
    'admin/checklist/index' => ['admin/checklist/index', ['method' => 'POST']],
    // 【检查指标体系】类别添加
    'admin/checklist/add' => ['admin/checklist/add', ['method' => 'POST']],
    // 【检查指标体系】类别修改
    'admin/checklist/edit' => ['admin/checklist/edit', ['method' => 'POST']],
    // 【检查指标体系】类别修改
    'admin/checklist/del' => ['admin/checklist/del', ['method' => 'POST']],

    // 【检查指标体系】指标项列表
    'admin/item/index' => ['admin/item/index', ['method' => 'POST']],
    // 【检查指标体系】指标项添加
    'admin/item/add' => ['admin/item/add', ['method' => 'POST']],
    // 【检查指标体系】指标项修改
    'admin/item/edit' => ['admin/item/edit', ['method' => 'POST']],
    // 【检查指标体系】指标项修改
    'admin/item/del' => ['admin/item/del', ['method' => 'POST']],

    // 【检查期次】
    'admin/plan/index'  => ['admin/plan/index', ['method' => 'POST']],
    // 【检查期次】
    'admin/plan/add'    => ['admin/plan/add', ['method' => 'POST']],
    // 【检查期次】
    'admin/plan/edit'   => ['admin/plan/edit', ['method' => 'POST']],
    // 【检查期次】
    'admin/plan/del'    => ['admin/plan/del', ['method' => 'POST']],
    // 【检查期次】
    'admin/plan/getdata' => ['admin/plan/getdata', ['method' => 'POST']],

    // 【检查安排】安排列表
    'admin/task/index'  => ['admin/task/index', ['method' => 'POST']],
    // 【检查安排】安排添加
    'admin/task/add'    => ['admin/task/add', ['method' => 'POST']],
    // 【检查安排】安排修改
    'admin/task/edit'   => ['admin/task/edit', ['method' => 'POST']],
    // 【检查安排】安排修改
    'admin/task/del'    => ['admin/task/del', ['method' => 'POST']],

    // 【检查规则】规则添加
    'admin/rule/add'    => ['admin/rule/add', ['method' => 'POST']],
    // 【检查规则】规则修改
    'admin/rule/edit'   => ['admin/rule/edit', ['method' => 'POST']],
    // 【检查规则】规则修改
    'admin/rule/del'    => ['admin/rule/del', ['method' => 'POST']],

    // 【组织机构】组织机构列表
    'admin/org/index'   => ['admin/org/index', ['method' => 'POST']],
    // 【组织机构】组织机构删除
    'admin/org/del'     => ['admin/org/del', ['method' => 'POST']],
    // 【组织机构】组织机构修改
    'admin/org/handle'  => ['admin/org/handle', ['method' => 'POST']],

    // 【人员列表】人员列表
    'admin/person/index' => ['admin/person/index', ['method' => 'POST']],
    // 【人员列表】添加人员
    'admin/person/add'   => ['admin/person/add', ['method' => 'POST']],
    // 【人员列表】修改人员
    'admin/person/edit'  => ['admin/person/edit', ['method' => 'POST']],
    // 【人员列表】删除人员
    'admin/person/del'   => ['admin/person/del', ['method' => 'POST']],
    // MISS路由
    '__miss__'  => 'admin/base/miss',
];