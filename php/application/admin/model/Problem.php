<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;
use think\db;
class Problem extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $name = 'ck_problem';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'dt_finish';
    protected $insert = [
        'staff'
    ];
    protected function setStaffAttr()
    {
        return $GLOBALS['userInfo']['username'];
    }

}