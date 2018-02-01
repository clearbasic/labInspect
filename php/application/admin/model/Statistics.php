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

        $childIds = getChildOrgIds($GLOBALS['userInfo']['org_id']);
        if (!empty($childIds))$map['org.org_id'] = ['in', $childIds];

        if (!empty($org_name))$map['org.org_name'] = ['like', '%'.$org_name.'%'];

        $org_list = db::name('dc_org')->alias('org')
            ->field('org.org_id,org.org_name,excellent.isExcellent,excellent.plan_id')
            ->join('ck_excellent excellent','org.org_id = excellent.org_id','left')
            ->where('org.org_level','lab')
            ->where($map)
            ->select();

        foreach ($org_list as $k => $v){
            if ($v['plan_id']!= null && $v['plan_id'] != $plan_id) {
                unset($org_list[$k]);
                continue;
            }elseif ($v['isExcellent'] == null){
                $org_list[$k]['isExcellent'] = '0';
                $org_list[$k]['plan_id'] = $plan_id;
            }
        }
        sort($org_list);
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

    //评优推荐名单   院系管理本单位下实验室设置评优推荐  学校管理院系推荐的实验室设置评优
    public function getRecommendList($param)
//    public function getCheckList($param)
    {
        $current_plan = model('plan')->where('current','yes')->value('plan_id');
        $plan_id = !empty($param['plan_id']) ? $param['plan_id'] : $current_plan;

        $task_list = db::name('ck_task')->where(array('plan_id'=>$plan_id))->select();
        $task_ids = db::name('ck_task')->where(array('plan_id'=>$plan_id))->column('task_id');

        $map = [];    $childIds = [];
        $org_name = !empty($param['org_name']) ? $param['org_name'] : '';
        if (!empty($org_name))$map['org.org_name'] = ['like', '%'.$org_name.'%'];

        //登录用户角色ID   2代表校级管理员 3代表学院管理员
        $group_id  = $GLOBALS['group_id'];

        if ($group_id <= 2){
            //获取评优推荐名单 推荐状态为2
            $childIds =  db::name('ck_excellent')->alias('excellent')
                ->where('excellent.isExcellent',['in',['1','2']])
                ->where('excellent.plan_id',$plan_id)
                ->column('org_id');
        }elseif ($group_id == 3){
            $childIds = model('org')->getAllChild($GLOBALS['userInfo']['org_id']);
            $childIds[]=$GLOBALS['userInfo']['org_id'];

        }else{
            $this->error = '没有权限';
            return false;
        }

        if (!empty($childIds))$map['org.org_id'] = ['in', $childIds];

        $org_list = db::name('dc_org')->alias('org')
            ->field('org.org_id,org.org_name,excellent.isExcellent,excellent.plan_id')
            ->join('ck_excellent excellent','org.org_id = excellent.org_id','left')
            ->where('org.org_level','lab')
            ->where($map)
            ->select();
        foreach ($org_list as $k => $v){
            if ($v['plan_id']!= null && $v['plan_id'] != $plan_id) {
                unset($org_list[$k]);
                continue;
            }elseif ($v['isExcellent'] == null){
                $org_list[$k]['isExcellent'] = '0';
                $org_list[$k]['plan_id'] = $plan_id;
            }
        }
        sort($org_list);
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

    //评优结果名单
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

    public function setRecommend($param)
    {
        $current_plan = model('plan')->where('current','yes')->value('plan_id');
        $plan_id = !empty($param['plan_id']) ? $param['plan_id'] : $current_plan;

        $isExcellent = !empty($param['isExcellent']) ? $param['isExcellent'] : '0';

        if ($isExcellent == '1'){
            $this->error = '该单位已经评优，无法更改';
            return false;
        }

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

    public function responTable($param)
    {
        $map =[];

        $childIds = getChildOrgIds($GLOBALS['userInfo']['org_id']);
        if (!empty($childIds))$map['org_id'] = ['in', $childIds];
        //按照院系 实验室 房间  进行查询

        $org_level = !empty($param['org_level']) ? $param['org_level'] : '';

        if (!empty($org_level)){
            if ($org_level == 'room'){
                $where = []; $arr= [];
                if (!empty($childIds))$where['lab_id|dept_id'] = ['in', $childIds];
                $where['agent_id'] = ['neq',''];
                $data = db::name('dc_room')->where($where)->field('agent_id,agent_name,room_name as org_name')->select();
                foreach ($data as $k => $v){
                    $user_info = db::name('dc_person')->field('username,name,email,mobile')->where('username',$v['agent_id'])->find();
                    $arr[$k]=array_merge($v,$user_info);
                }
                return $arr;
            }else{
                $map['org_level'] =  $org_level;
            }
        }

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