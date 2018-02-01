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
        'admin/roles'		   =>'admin/roles',
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
        'admin/room'           =>'admin/room',
        'admin/zone'           =>'admin/zone',
        'admin/group'          =>'admin/group',
        'admin/check'          =>'admin/check',
        'admin/login'          =>'admin/login',
        'admin/checklogin'     =>'admin/checklogin',
        'admin/export'         =>'admin/export',
    ],

    // 【基础】登录
    'admin/base/login'      => ['admin/base/login', ['method' => 'POST']],
    // 【基础】记住登录
    'admin/base/relogin'	=> ['admin/base/relogin', ['method' => 'POST']],
    // 【基础】修改密码
    'admin/base/setInfo'    => ['admin/base/setInfo', ['method' => 'POST']],
    // 【基础】退出登录
    'admin/base/logout'     => ['admin/base/logout', ['method' => 'POST']],
    // 【基础】获取配置
    'admin/base/getConfigs' => ['admin/base/getConfigs', ['method' => 'POST']],
    // 【基础】获取配置
    'admin/base/aptitude' => ['admin/base/aptitude', ['method' => 'POST']],
    // 【基础】获取验证码
    'admin/base/getVerify'  => ['admin/base/getVerify', ['method' => 'GET']],
    // 【基础】上传图片
    'admin/upload'          => ['admin/upload/index', ['method' => 'POST']],
    // 保存系统配置
    'admin/systemConfigs'   => ['admin/systemConfigs/save', ['method' => 'POST']],
    // 保存系统配置
    'admin/SystemConfig/index'   => ['admin/systemConfigs/index', ['method' => 'POST']],


    // 【规则】菜单列表
    'admin/rules/index'   => ['admin/rules/index', ['method' => 'POST']],
    // 【规则】菜单添加
    'admin/rules/add'   => ['admin/rules/add', ['method' => 'POST']],
    // 【规则】菜单修改
    'admin/rules/edit'   => ['admin/rules/edit', ['method' => 'POST']],
    // 【规则】菜单修改
    'admin/rules/del'   => ['admin/rules/del', ['method' => 'POST']],
    // 【规则】批量删除
    'admin/rules/deletes'   => ['admin/rules/deletes', ['method' => 'POST']],
    // 【规则】批量启用/禁用
    'admin/rules/enables'   => ['admin/rules/enables', ['method' => 'POST']],

    // 【用户组】菜单列表
    'admin/groups/index'   => ['admin/groups/index', ['method' => 'POST']],
    // 【用户组】菜单添加
    'admin/groups/add'   => ['admin/groups/add', ['method' => 'POST']],
    // 【用户组】根据用户组查询用户组的规则
    'admin/groups/getRules'   => ['admin/groups/getRules', ['method' => 'POST']],
    // 【用户组】菜单修改
    'admin/groups/edit'   => ['admin/groups/edit', ['method' => 'POST']],
    // 【用户组】菜单修改
    'admin/groups/del'   => ['admin/groups/del', ['method' => 'POST']],


    // 【角色分配】角色分配列表
    'admin/roles/index'  => ['admin/roles/index', ['method' => 'POST']],
    // 【角色分配】角色分配添加
    'admin/roles/add'  => ['admin/roles/add', ['method' => 'POST']],
    // 【角色分配】角色分配删除
    'admin/roles/del'  => ['admin/roles/del', ['method' => 'POST']],

    // 【菜单】菜单列表
    'admin/menus/index'   => ['admin/menus/index', ['method' => 'POST']],
    // 【菜单】菜单添加
    'admin/menus/add'   => ['admin/menus/add', ['method' => 'POST']],
    // 【菜单】菜单修改
    'admin/menus/edit'   => ['admin/menus/edit', ['method' => 'POST']],
    // 【菜单】菜单删除
    'admin/menus/del'   => ['admin/menus/del', ['method' => 'POST']],
    // 【菜单】批量删除
    'admin/menus/deletes'   => ['admin/menus/deletes', ['method' => 'POST']],
    // 【菜单】批量启用/禁用
    'admin/menus/enables'   => ['admin/menus/enables', ['method' => 'POST']],


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
    // 【检查规则】规则复制
    'admin/rule/copy'    => ['admin/rule/copy', ['method' => 'POST']],
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

    // 【人员管理】人员列表
    'admin/person/index' => ['admin/person/index', ['method' => 'POST']],

    'admin/person/getuser' => ['admin/person/getuser', ['method' => 'POST']],
    // 【人员管理】添加人员
    'admin/person/add'   => ['admin/person/add', ['method' => 'POST']],
    // 【人员管理】修改人员
    'admin/person/edit'  => ['admin/person/edit', ['method' => 'POST']],
    // 【人员管理】删除人员
    'admin/person/del'   => ['admin/person/del', ['method' => 'POST']],
    // 【人员管理】导入人员
    'admin/person/personImport'  => ['admin/person/personImport', ['method' => 'POST']],
    // 【人员管理】导入人员操作
    'admin/person/personRunimport'   => ['admin/person/personRunimport', ['method' => 'POST']],
    // 【人员管理】人员导出操作
    'admin/person/personExport'   => ['admin/person/personExport', ['method' => 'GET']],


    // 【房间管理】房间列表
    'admin/room/index' => ['admin/room/index', ['method' => 'POST']],
    // 【房间管理】添加房间
    'admin/room/add'   => ['admin/room/add', ['method' => 'POST']],
    // 【房间管理】修改房间
    'admin/room/edit'  => ['admin/room/edit', ['method' => 'POST']],
    // 【房间管理】删除房间
    'admin/room/del'   => ['admin/room/del', ['method' => 'POST']],
    // 【房间管理】导入房间
    'admin/room/roomImport'   => ['admin/room/roomImport', ['method' => 'POST']],
    // 【房间管理】导入房间
    'admin/room/roomRunimport'   => ['admin/room/roomRunimport', ['method' => 'POST']],
    // 【房间管理】导出房间
    'admin/room/roomExport'   => ['admin/room/roomExport', ['method' => 'GET']],
    // 【房间管理】房间资质
    'admin/room/roomAptitude'   => ['admin/room/roomAptitude', ['method' => 'POST']],

    // 【房间分组管理】房间分组列表
    'admin/zone/index' => ['admin/zone/index', ['method' => 'POST']],
    // 【房间分组管理】添加房间分组
    'admin/zone/add'   => ['admin/zone/add', ['method' => 'POST']],
    // 【房间分组管理】修改房间分组
    'admin/zone/edit'  => ['admin/zone/edit', ['method' => 'POST']],
    // 【房间分组管理】删除房间分组
    'admin/zone/del'   => ['admin/zone/del', ['method' => 'POST']],

    // 【检查小组管理】房间分组列表
    'admin/group/index' => ['admin/group/index', ['method' => 'POST']],
    // 【检查小组管理】添加房间分组
    'admin/group/add'   => ['admin/group/add', ['method' => 'POST']],
    // 【检查小组管理】修改房间分组
    'admin/group/edit'  => ['admin/group/edit', ['method' => 'POST']],
    // 【检查小组管理】删除房间分组
    'admin/group/del'   => ['admin/group/del', ['method' => 'POST']],

    // 【检查工作管理】检查工作列表
    'admin/check/index' => ['admin/check/index', ['method' => 'POST']],

    'admin/check/checkindex' => ['admin/check/checkindex', ['method' => 'POST']],

    // 【检查工作管理】检查工作分配
    'admin/check/allot' => ['admin/check/allot', ['method' => 'POST']],
    // 【检查工作管理】检查工作进度
    'admin/check/progress' => ['admin/check/progress', ['method' => 'POST']],
    // 【检查工作管理】检查工作基本信息
    'admin/check/baseinfo' => ['admin/check/baseinfo', ['method' => 'POST']],
    // 【检查工作管理】检查工作操作（添加修改）
    'admin/check/handle' => ['admin/check/handle', ['method' => 'POST']],
    // 【检查工作管理】检查工作我的检查
    'admin/check/mycheck' => ['admin/check/mycheck', ['method' => 'POST']],
    // 【检查工作管理】检查工作开始检查
    'admin/check/startcheck' => ['admin/check/startcheck', ['method' => 'POST']],
    // 【检查工作管理】检查工作提交检查
    'admin/check/submitcheck' => ['admin/check/submitcheck', ['method' => 'POST']],
    // 【检查工作管理】检查工作检查结果
    'admin/check/showresult' => ['admin/check/showresult', ['method' => 'POST']],
    // 【检查工作管理】检查结果
    'admin/check/checkResult' => ['admin/check/checkResult', ['method' => 'POST']],
    // 【检查工作管理】检查反馈
    'admin/check/feedback' => ['admin/check/feedback', ['method' => 'POST']],

    // 【登录】
    'admin/login/login' => ['admin/login/login', ['method' => 'POST']],
    // 【登录】
    'admin/login/getVerify'   => ['admin/login/getVerify', ['method' => 'GET']],
    // 【登录】
    'admin/login/logout'   => ['admin/login/logout', ['method' => 'POST']],

    // 【统计功能】检查统计
    'admin/statistics/checkStatistics' => ['admin/statistics/checkStatistics', ['method' => 'POST']],
    // 【统计功能】评优结果统计
    'admin/statistics/excellentStatistics' => ['admin/statistics/excellentStatistics', ['method' => 'POST']],
    // 【统计功能】评优推荐统计
    'admin/statistics/recommendStatistics' => ['admin/statistics/recommendStatistics', ['method' => 'POST']],
    // 【统计功能】评优推荐
    'admin/statistics/setRecommend' => ['admin/statistics/setRecommend', ['method' => 'POST']],
    // 【统计功能】设置评优
    'admin/statistics/setExcellent' => ['admin/statistics/setExcellent', ['method' => 'POST']],
    // 【统计功能】责任人登记表
    'admin/statistics/responTable' => ['admin/statistics/responTable', ['method' => 'POST']],

    // MISS路由
    '__miss__'  => 'admin/base/miss',
];