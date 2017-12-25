<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;
use think\db;
class CkGroup extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $name = 'ck_group';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'dt_create';
    protected $updateTime = false;
    protected $insert = [
        'creator'
    ];
    protected function setCreatorAttr()
    {
        return $GLOBALS['userInfo']['username'];
    }
    /**
     * [getDataList 获取列表]
     * @return    [array]
     */
    public function getDataList($param, $page, $limit)
    {
        $map = [];
        $map['group.org_id'] = $GLOBALS['userInfo']['org_id'];

        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        if ($keywords) {
            $map['group.group_name'] = ['like', '%'.$keywords.'%'];
        }

        $list = $this->alias('group')->where($map);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list
            ->join('dc_org org', 'group.org_id=org.org_id', 'LEFT')
            ->field('group.*,org.org_name as org_name')
            ->where('group.deleted','no')
            ->order('group.group_order')
            ->select();
        $data = $list;
        return $data;
    }

    /**
     * 通过id修改指标体系
     * @param  array   $param  [description]
     */
    public function createData($param)
    {

        // 验证
        $validate = validate('CkGroup');
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        $group_order = !empty($param['group_order']) ? $param['group_order']: '';
        $this->startTrans();
        try {
            $this->data($param)->allowField(true)->save();
            $id = $this->group_id;
            if ($group_order == ''){
                $this->allowField(['group_order'])->save([
                    'group_order'  => $id,
                ], ['group_id' => $id]);
            }
            $this->commit();
            return '检查分组添加成功';
        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '检查分组有重复，添加失败';
            return false;
        }
    }
    /**
     * 通过id修改用户信息
     * @param  array   $param  [description]
     */
    public function updateDataById($param, $id)
    {
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = '暂无此分组信息';
            return false;
        }
        $this->startTrans();
        try {
            $this->allowField(true)->save($param, ['group_id' => $id]);
            $this->commit();
            return true;

        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '该检查分组已存在，编辑失败';
            return false;
        }
    }

    /**
     * 通过id修改用户信息
     * @param  array   $param  [description]
     */
    public function delById($id,$delSon = false)
    {
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = '暂无此分组信息';
            return false;
        }
        $this->startTrans();
        try {
            $this->allowField(['deleted'])->save([
                'deleted'  => 'yes',
            ], ['group_id' => $id]);

            $this->commit();
            return true;
        } catch(\Exception $e) {
            $this->error = '删除失败';
            $this->rollback();
            return false;
        }
    }

}