<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;

class Item extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $name = 'ck_item';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'dt_create';
    protected $updateTime = false;
    protected $insert = [
        'creator' => 'chingo',
    ];
    public function profile()
    {
        return $this->hasOne('Checklist');
    }
    /**
     * [getDataList 获取列表]
     * @return    [array]
     */
    public function getDataList($cklist_id,$keywords, $page, $limit)
    {
        if (empty($cklist_id)) {
            $this->error = '请至少选择一个指标类别';
            return false;
        }
        $map = [];
        $map['checklist_id'] = $cklist_id;
        if ($keywords) {
            $map['item_name'] = ['like', '%'.$keywords.'%'];
        }

        $list = $this->where($map);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list
            ->order('item_order')
            ->select();
        $data = $list;
        return $data;
    }
    /**
     * 创建指标体系
     * @param  array   $param  [description]
     */
    public function createData($param)
    {
        // 验证
        $validate = validate('Item');
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        $this->startTrans();
        try {
            $this->data($param)->allowField(true)->save();
            $this->commit();
            return true;
        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '添加失败';
            return false;
        }
    }
    /**
     * 通过id修改指标体系
     * @param  array   $param  [description]
     */
    public function updateDataById($param, $id)
    {
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = '暂无此数据';
            return false;
        }
        $this->startTrans();
        try {
            $this->allowField(true)->save($param, ['id' => $id]);
            $this->commit();
            return true;

        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '编辑失败';
            return false;
        }
    }
}