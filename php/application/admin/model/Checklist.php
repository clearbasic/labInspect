<?php
// +----------------------------------------------------------------------
// | Description: �û���
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;

class Checklist extends Common
{
    /**
     * Ϊ�����ݿ�����࣬ͬʱ�ֲ�Ӱ��Model��Controller������
     */
    protected $table = 't_ck_checklist';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'dt_create';
    protected $updateTime = false;

    /**
     * [getDataList ��ȡ�б�]
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