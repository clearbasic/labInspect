<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;

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
        $arr = [];
        $i = 0;
        foreach ($param['checklist'] as $k => $v){
            $arr[$i]= $param;
            unset($arr[$i]['checklist']);
            $arr[$i]['checklist_id'] = $k;
            $arr[$i]['score'] = $v['score'];
            $i++;
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
        $where = [];
        $where['plan_id'] = $plan_id;
        $where['level']   = $level;
        $where['college_id'] = $college_id;
        $where['lab_id']  = $lab_id;
        $this->startTrans();
        try {
            $this->where($where)->delete();
            $arr = [];
            $i = 0;
            foreach ($param['checklist'] as $k => $v){
                $arr[$i]= $param;
                unset($arr[$i]['checklist']);
                $arr[$i]['checklist_id'] = $k;
                $arr[$i]['score'] = $v['score'];
                $i++;
            }
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