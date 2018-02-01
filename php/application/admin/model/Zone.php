<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;
use think\db;
class Zone extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $name = 'dc_zone';
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

        //获取登录用户的子单位ID组
        $childIds = getChildOrgIds($GLOBALS['userInfo']['org_id']);
        if (!empty($childIds)) {
            $map['zone.org_id'] = ['in', $childIds];
        }

        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        if ($keywords) {
            $map['username|name'] = ['like', '%'.$keywords.'%'];
        }

        $list = $this->alias('zone')->where($map);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list
            ->join('dc_org org', 'zone.org_id=org.org_id', 'LEFT')
            ->field('zone.*,org.org_name as org_name')
            ->order('zone.zone_order')
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

        $zone_order = !empty($param['zone_order']) ? $param['zone_order']: '';
        $this->startTrans();
        try {
            $this->data($param)->allowField(true)->save();
            $id = $this->zone_id;
            if ($zone_order == ''){
                $this->allowField(['zone_order'])->save([
                    'zone_order'  => $id,
                ], ['zone_id' => $id]);
            }
            $this->commit();
            return '房间分组添加成功';
        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '房间分组有重复，添加失败';
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
            $this->allowField(true)->save($param, ['zone_id' => $id]);
            $this->commit();
            return true;

        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '编辑失败';
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
            $this->where($this->getPk(), $id)->delete();
            if ($delSon && is_numeric($id)) {
                // 删除子孙
                $childIds = $this->getAllChild($id);
                if($childIds){
                    $this->where($this->getPk(), 'in', $childIds)->delete();
                }
            }
            $this->commit();
            return true;
        } catch(\Exception $e) {
            $this->error = '删除失败';
            $this->rollback();
            return false;
        }
    }

}