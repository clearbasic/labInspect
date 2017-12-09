<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;
use think\db;
class Org extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $name = 'ck_org';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'dt_create';
    protected $updateTime = false;
    protected $insert = [
        'creator' => 'chingo',
    ];
    /**
     * [getDataList 获取列表]
     * @return    [array]
     */
    public function getDataList($keywords, $page, $limit)
    {
        $map = [];
        if ($keywords) {
            $map['plan_name'] = ['like', '%'.$keywords.'%'];
        }

        $list = $this->where($map);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list
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
        $validate = validate('Plan');
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        $this->startTrans();
        try {
            $this->data($param)->allowField(true)->save();
            $plan_id = $this->plan_id;
            $current = $this->current;
            if ($current == 'yes'){
                $this->where(['plan_id'=>['not in',$plan_id]])->setField('current','no');
            }
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
            $this->allowField(true)->save($param, ['plan_id' => $id]);
            $current = $this->current;
            if ($current == 'yes'){
                $this->where(['plan_id'=>['not in',$id]])->setField('current','no');
            }
            $this->commit();
            return true;

        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '编辑失败';
            return false;
        }
    }
    /**
     * 通过id修改指标体系
     * @param  array   $param  [description]
     */
    public function setcurrentById($id)
    {
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = '暂无此数据';
            return false;
        }
        $this->startTrans();
        try {
            $this->where(['plan_id'=>['not in',$id]])->setField('current','no');
            $this->where(['plan_id'=>$id])->setField('current','yes');
            $this->commit();
            return true;

        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '设置当前期次失败';
            return false;
        }
    }
    public function planData($plan_id)
    {
        $checkData = $this->get($plan_id);
        if (!$checkData) {
            $this->error = '暂无此期次ID';
            return false;
        }
        $data['plan'] = $this->where('plan_id',$plan_id)->find();
        $data['task_list'] = $this
            ->alias('plan')
            ->join('ck_task task', 'task.plan_id=plan.plan_id', 'LEFT')
            ->where('plan.plan_id',$plan_id)
            ->field('task.*')
            ->select();

        $data['rule_list'] = $this
            ->alias('plan')
            ->join('ck_rule rule', 'rule.plan_id=plan.plan_id', 'LEFT')
            ->where('plan.plan_id',$plan_id)
            ->field('rule.*')
            ->group('rule.level,rule.college_id,rule.lab_id')
            ->select();
        foreach ($data['rule_list'] as $k=>$v){
            $v['checklist'] = db::name('ck_rule')
                //字段排除
                //->field(['plan_id','rule_id','level','college_id','lab_id','creator','dt_create'],true)
                ->where(array('level'=>$v['level'],'college_id'=>$v['college_id'],'lab_id'=>$v['lab_id']))
                ->select();
        }
        return $data;
    }
}