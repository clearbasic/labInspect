<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;
use think\db;

class CkRule extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $name = 'ck_rule';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
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
     * 创建检查规则
     * @param  array   $param  [description]
     */
    public function createData($param)
    {
        // 验证
        $validate = validate('CkRule');
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        $plan_score = model('Plan')->where('plan_id',$param['plan_id'])->value('plan_score');
        $arr = [];
        $i = 0;
        $score = 0;
        foreach ($param['checklist'] as $k => $v){
            $arr[$i]= $param;
            unset($arr[$i]['checklist']);
            $arr[$i]['checklist_id'] = $k;
            $arr[$i]['score'] = $v['score'];
            $score += $v['score'];
            $i++;
        }
        if ($score != $plan_score){
            $this->error = '设置总分不等于配置总分';
            return false;
        }
        $this->startTrans();
        try {
            $this->allowField(true)->saveAll($arr);
            $this->commit();
            return true;
        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '规则已重复，添加失败';
            return false;
        }
    }
    /**
     * 创建检查规则
     * @param  array   $param  [description]
     */
    public function copyData($param)
    {
        $plan_id = !empty($param['plan_id'])? $param['plan_id'] : '';
        $old_level = !empty($param['old_level'])? $param['old_level'] : '';
        $level = !empty($param['level'])? $param['level'] : '';
        if (!$plan_id || !$level || !$old_level){
            $this->error = '缺少参数';
            return false;
        }
        $rule_list = db::name('ck_rule')->field('rule_id,creator,dt_create',true)->where(array('plan_id'=>$plan_id,'level'=>$old_level))->select();
        $arr=[];
        foreach ($rule_list as $k=>$v){
            $arr[$k]=$v;
            $arr[$k]['level']=$level;
        }
        $this->startTrans();
        try {
            $this->allowField(true)->saveAll($arr);
            $this->commit();
            return true;
        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '规则已重复，添加失败';
            return false;
        }
    }


    /**
     * 通过id修改指标体系
     * @param  array   $param  [description]
     */
    public function updateData($param)
    {

        $plan_id = !empty($param['plan_id']) ? $param['plan_id']: '';
        $level = !empty($param['level']) ? $param['level']: '';
        $college_id = !empty($param['college_id']) ? $param['college_id']: '0';
        $lab_id = !empty($param['lab_id']) ? $param['lab_id']: '0';
        if ($plan_id === '') {
            $this->error = '缺少期次id';
            return false;
        }
        if ($level === '') {
            $this->error = '缺少单位级别';
            return false;
        }
        $plan_score = model('Plan')->where('plan_id',$plan_id)->value('plan_score');
        $where = [];
        $where['plan_id'] = $plan_id;
        $where['level']   = $level;
        $where['college_id'] = $college_id;
        $where['lab_id']  = $lab_id;

        $arr = [];
        $i = 0; $score = 0;
        foreach ($param['checklist'] as $k => $v){
            $arr[$i]= $param;
            unset($arr[$i]['checklist']);
            $arr[$i]['checklist_id'] = $k;
            $arr[$i]['score'] = $v['score'];
            $score += $v['score'];
            $i++;
        }
        if ($score > $plan_score){
            $this->error = '设置总分大于配置总分';
            return false;
        }

        $this->startTrans();
        try {
            $this->where($where)->delete();
            $this->allowField(true)->saveAll($arr);
            $this->commit();
            return true;

        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '编辑失败';
            return false;
        }
    }
    /**
     * 删除指标规则
     * @param  array   $param  [description]
     */
    public function delData($param)
    {

        $plan_id = !empty($param['plan_id']) ? $param['plan_id']: '';
        $level = !empty($param['level']) ? $param['level']: '';
        $college_id = !empty($param['college_id']) ? $param['college_id']: '0';
        $lab_id = !empty($param['lab_id']) ? $param['lab_id']: '0';
        if ($plan_id === '') {
            $this->error = '缺少期次id';
            return false;
        }
        if ($level === '') {
            $this->error = '缺少单位级别';
            return false;
        }
        $where = [];
        $where['plan_id'] = $plan_id;
        $where['level']   = $level;
        $where['college_id'] = $college_id;
        $where['lab_id']    = $lab_id;
        $this->startTrans();
        try {
            $this->where($where)->delete();
            $this->commit();
            return true;

        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '删除失败';
            return false;
        }
    }

}