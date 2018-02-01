<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;

class Task extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $name = 'ck_task';
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

    //方法一
    public function getPlanIdAttr($value,$data)
    {
        return $this->belongsTo('Plan','plan_id','plan_id')->where('plan_id',$value)->value('plan_name');
    }
    //方法二
//    public function getPlanIdAttr($value,$data)
//    {
//        return $this->belongsTo('Plan','plan_id','plan_id')->where('plan_id',$data['plan_id'])->value('plan_name');
//    }

    /**
     * [getDataList 获取列表]
     * @return    [array]
     */
    public function getDataList($plan_id,$keywords, $page, $limit)
    {
        $map = [];
        if (empty($plan_id)) {
            $this->error = '请至少选择一个期次';
            return false;
        }
        $map['plan_id'] = $plan_id;
        if ($keywords) {
            $map['task_name'] = ['like', '%'.$keywords.'%'];
        }

        $list = $this->where($map);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list
            ->order('task_level,task_name,dt_begin')
            ->select();
        $data = $list;
        return $data;
    }
    /**
     * 创建检查安排
     * @param  array   $param  [description]
     */
    public function createData($param)
    {
        // 验证
        $validate = validate('task');
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
            $this->allowField(true)->save($param, ['task_id' => $id]);
            $this->commit();
            return true;

        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '编辑失败';
            return false;
        }
    }
}