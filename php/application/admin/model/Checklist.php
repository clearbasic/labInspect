<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;

class Checklist extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $table = 't_ck_checklist';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'dt_create';
    protected $updateTime = false;

    /**
     * [getDataList 获取列表]
     * @linchuangbin
     * @DateTime  2017-02-10T21:07:18+0800
     * @return    [array]
     */
    public function getDataList()
    {

        $data = $this->select();
        return $data;
    }
}