<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;
use think\db;
class Plan extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $name = 'ck_plan';
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
     * [getDataList 获取期次列表]
     * @return    [array]
     */
    public function getDataList($param, $page, $limit)
    {
        $map = [];

        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        if ($keywords) {
            $map['plan_name'] = ['like', '%'.$keywords.'%'];
        }

        $plan_id = !empty($param['plan_id']) ? $param['plan_id']: '';
        if ($plan_id) {
            $map['plan_id'] = $plan_id;
        }

        $current = !empty($param['current']) ? $param['current']: '';
        if ($current == '1') {
            $map['current'] = 'yes';
            $list = $this->where($map)->find();
            return $list;
        }

        $list = $this->where($map);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list->select();
        foreach ($list as &$v){
            $data[]=$v->toArray();
        }
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
     * [delDataById 根据id删除数据]
     * @linchuangbin
     * @DateTime  2017-02-11T20:57:55+0800
     * @param     string                   $id     [主键]
     * @param     boolean                  $delSon [是否删除子孙数据]
     * @return    [type]                           [description]
     */
    public function delDataById($id = '', $delSon = false)
    {

        $count = db::view('ck_check')
            ->view('ck_task','','ck_check.task_id = ck_task.task_id')
            ->where('ck_task.plan_id',$id)
            ->count();
        if ($count > 0){
            $this->error = '该期次下已有检查任务进行，不能删除';
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
        //检查期次信息
        $plan = $this->where('plan_id',$plan_id)->find()->toArray();
        $res['plan'] = $plan;

        //检查安排信息
        $task_list = $this
            ->alias('plan')
            ->join('ck_task task', 'task.plan_id=plan.plan_id', 'LEFT')
            ->where('plan.plan_id',$plan_id)
            ->field('task.*')
            ->order('task.dt_begin,task.task_name,task.task_level')
            ->select();

        foreach ($task_list as $v){
            $res['task_list'][] = $v->toArray();
        }

        //检查规则信息
        $rule_list = $this
            ->alias('plan')
            ->join('ck_rule rule', 'rule.plan_id=plan.plan_id', 'LEFT')
            ->where('plan.plan_id',$plan_id)
            ->field('rule.level,rule.college_id,rule.lab_id,rule.plan_id')
            ->group('rule.level,rule.college_id,rule.lab_id')
            ->select();

        foreach ($rule_list as $v){
            $res['rule_list'][] = $v->toArray();
        }

        foreach ($res['rule_list'] as &$v){
            $rules = db::name('ck_rule')
                //字段排除
                ->field(['plan_id','rule_id','level','college_id','lab_id','creator','dt_create'],true)
                ->where(array('level'=>$v['level'],'college_id'=>$v['college_id'],'lab_id'=>$v['lab_id'],'plan_id'=>$plan_id))
                ->select();
            foreach ($rules as $vv){
                $v['checklist'][$vv['checklist_id']] = $vv;
            }

        }

        $res['today'] =  time();
        return $res;
    }
}