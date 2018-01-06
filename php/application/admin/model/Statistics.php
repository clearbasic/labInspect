<?php
/**
 * Created by PhpStorm.
 * User: chingo
 * Date: 2018/1/5
 * Time: 10:55
 */

namespace app\admin\model;

use app\admin\model\Common;
use think\db;
class Statistics extends Common
{

    public function getCheckList($param)
    {
        $current_plan = model('plan')->where('current','yes')->value('plan_id');
        $plan_id = !empty($param['plan_id']) ? $param['plan_id'] : $current_plan;

        $task_list = db::name('ck_task')->where(array('plan_id'=>$plan_id))->select();
        $task_ids = db::name('ck_task')->where(array('plan_id'=>$plan_id))->column('task_id');

        $org_name = !empty($param['org_name']) ? $param['org_name'] : '';
        $map = [];
        $childIds = [];
        if ($GLOBALS['userInfo']['org_id'] != '1'){
            $childIds = model('org')->getAllChild($GLOBALS['userInfo']['org_id']);
            $childIds[]=$GLOBALS['userInfo']['org_id'];
        }
        if (!empty($childIds))$map['org.org_id'] = ['in', $childIds];
        if (!empty($org_name))$map['org.org_name'] = ['like', '%'.$org_name.'%'];;

        $org_list = db::name('dc_org')->alias('org')
            ->field('org.org_id,org.org_name,excellent.isExcellent,excellent.plan_id')
            ->join('ck_excellent excellent','org.org_id = excellent.org_id','left')
            ->where('org.org_level','lab')
            ->where($map)
            ->select();
        foreach ($org_list as $k => $v){
            if ($v['isExcellent'] != '1' || $v['plan_id'] != $plan_id)$org_list[$k]['isExcellent']='0';
        }

        $org_ids = db::name('dc_org')->alias('org')->where(array('org.org_level'=>'lab'))->where($map)->column('org_id');

        $check_list = db::name('ck_check')->alias('check')
            ->field('check.*')
            ->join('ck_task task','check.task_id = task.task_id')
            ->where(array('check.task_id'=>['in',$task_ids],'check.org_id'=>['in',$org_ids],'check.check_state'=>'finished'))
            ->order('task.task_level')
            ->select();
        $data['task_list'] = $task_list;
        $data['org_list'] = $org_list;
        $data['check_list'] = $check_list;
        return $data;

    }

    public function getExcellentList($param)
    {

        $current_plan = model('plan')->where('current','yes')->value('plan_id');
        $plan_id = !empty($param['plan_id']) ? $param['plan_id'] : $current_plan;

        $ExcellentList = db::name('ck_excellent')->alias('excellent')
            ->field('excellent.*,org.org_name,plan.plan_name')
            ->join('dc_org org','excellent.org_id = org.org_id')
            ->join('ck_plan plan','excellent.plan_id = plan.plan_id')
            ->where('excellent.isExcellent','1')
            ->where('excellent.plan_id',$plan_id)
            ->select();
        return $ExcellentList;
    }
    public function setExcellent($param)
    {
        $current_plan = model('plan')->where('current','yes')->value('plan_id');
        $plan_id = !empty($param['plan_id']) ? $param['plan_id'] : $current_plan;

        $isExcellent = !empty($param['isExcellent']) ? $param['isExcellent'] : '0';

        $org_id = !empty($param['org_id']) ? $param['org_id'] : '';
        if (empty($org_id)){
            $this->error = '请至少选择一个单位';
            return false;
        }

        $data = db::name('ck_excellent')->where(array('plan_id'=>$plan_id,'org_id'=>$org_id))->find();

        $this->startTrans();
        if (empty($data)){
            $param['plan_id'] = $plan_id;
            try {
                model('excellent')->data($param)->allowField(true)->save();
                $this->commit();
                return true;
            } catch(\Exception $e) {
                $this->rollback();
                $this->error = '设置失败';
                return false;
            }
        }else{
            try {
                db::name('ck_excellent')->where(array('plan_id'=>$plan_id,'org_id'=>$org_id))->update(['isExcellent' => $isExcellent]);
                $this->commit();
                return true;
            } catch(\Exception $e) {
                $this->rollback();
                $this->error = '设置失败';
                return false;
            }
        }
    }

    public function responTable()
    {
        $map =[];
        $childIds = [];
        if ($GLOBALS['userInfo']['org_id'] != '1'){
            $childIds = model('org')->getAllChild($GLOBALS['userInfo']['org_id']);
            $childIds[]=$GLOBALS['userInfo']['org_id'];
        }
        if (!empty($childIds))$map['org_id'] = ['in', $childIds];
        $data = db::name('dc_org')->where($map)->where('responsible','neq','')->field('responsible,org_id,org_name')->select();
        if (!empty($data)){
            foreach ($data as $k=>$v){
                $result = array();
                preg_match_all("/(?:\()(\w+)(?:\))/i",$v['responsible'], $result);

                $username= $result[1][0];
                $user_info = db::name('dc_person')->field('username,name,email,mobile')->where('username',$username)->find();
                $data[$k]=array_merge($v,$user_info);

            }
        }

        return $data;
    }
}