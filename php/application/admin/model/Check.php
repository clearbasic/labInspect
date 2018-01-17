<?php
// +----------------------------------------------------------------------
// | Description: 检查工作
// +----------------------------------------------------------------------
// | Author: chenkq <chenkq@chingo.cn>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;
use think\db;
class Check extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $name = 'ck_check';
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

    public function  baseinfo($param){
        $task_id = !empty($param['task_id'])? $param['task_id']: '';
        if (!$task_id) {
            $this->error = '检查安排ID不能为空';
            return false;
        }

        $college_id = !empty($param['college_id'])? $param['college_id']: '';
        if (!$college_id) {
            $this->error = '学院ID不能为空';
            return false;
        }

        //获取登录用户的子单位ID组
        $map =[];
        $childIds = [];
        if ($GLOBALS['userInfo']['org_id'] != '1'){
            $childIds = model('org')->getAllChild($GLOBALS['userInfo']['org_id']);
            $childIds[]=$GLOBALS['userInfo']['org_id'];
        }
        if (!empty($childIds))$map['org_id'] = ['in', $childIds];

        $lab_ids = model('org')->where('pid',$college_id)->where($map)->column('org_id');

        $checklist['task'] = model('task')->field(['creator', 'dt_create'], true)->where('task_id', $task_id)->find();

        $checklist['college_info'] = model('org')->field('org_id,org_name,org_state')->where('org_id', $college_id)->find();

        $checklist['plan'] = model('plan')->field(['creator', 'dt_create'], true)->where('plan_id', $checklist['task']['plan_id'])->find();

        $checklist['org_list'] = model('org')->field('org_id,org_name,org_state')->where('org_id',['in',$lab_ids])->select();

        $checklist['zone_list'] = model('zone')->field(['creator', 'dt_create'], true)->where(array('org_id' => ['in', $lab_ids]))->select();

        $checklist['room_list'] = model('room')->field(['creator', 'dt_create'], true)->where(array('lab_id' => ['in', $lab_ids]))->select();

        $checklist['group_list'] = model('CkGroup')->field(['creator', 'dt_create'], true)->where(array('deleted' => 'no'))->select();

        return $checklist;
    }
    /**
     * @param  array   $param  [description]
     */
    public function allot($param)
    {

        $task_id = !empty($param['task_id'])? $param['task_id']: '';
        if (!$task_id) {
            $this->error = '检查安排ID不能为空';
            return false;
        }

        $college_id = !empty($param['college_id'])? $param['college_id']: '';

        if (!$college_id) {
            $this->error = '学院ID不能为空';
            return false;
        }


        //获取登录用户的子单位ID组
        $map =[];
        $childIds = [];
        if ($GLOBALS['userInfo']['org_id'] != '1'){
            $childIds = model('org')->getAllChild($GLOBALS['userInfo']['org_id']);
            $childIds[]=$GLOBALS['userInfo']['org_id'];
        }
        if (!empty($childIds))$map['org_id'] = ['in', $childIds];

        $lab_ids = model('org')->where('pid',$college_id)->where($map)->column('org_id');


        $checklist = $this->field('check_id,check_level,org_id,task_id,dt_begin,dt_end,check_state')
            ->where(array('task_id'=>$task_id,'org_id'=>['in',$lab_ids]))
            ->order('org_id')
            ->select();
        foreach ($checklist as $k => $v){
            $arr=[];
            $checklist[$k]['org_name'] =model('org')->where('org_id',$v['org_id'])->value('org_name');
            $checklist[$k]['check_state'] = $v['check_state'];

            $room_ids = model('room')->where('lab_id',$v['org_id'])->column('room_id');
            $room_zone_ids = db::name('ck_effort')->where('check_id',$v['check_id'])->where(['room_id'=>['in',$room_ids]])->group('zone_id')->column('zone_id');

            foreach ($room_zone_ids as $key => $value){
                $zone_id = model('zone')->where('zone_id',$value)->field(['creator','dt_create'],true)->value('zone_id');;

                $group_id = db::name('ck_effort')->where('check_id',$v['check_id'])->where(['room_id'=>['in',$room_ids]])
                    ->where('zone_id',$value)->value('group_id');

                $arr[$zone_id]['zone_id'] = $zone_id;
                $arr[$zone_id]['group_id'] = $group_id;

            }

            $checklist[$k]['zone_list'] = $arr;
        }

        return $checklist;
    }

    /**
     * 检查工作列表
     * @param  array   $param  [description]
     */
    public function checkindex($param)
    {
        $current_plan_id = db::name('ck_plan')->where('current','yes')->value('plan_id');

        $plan_id = !empty($param['plan_id'])? $param['plan_id']: $current_plan_id;
        $college_id = !empty($param['college_id'])? $param['college_id']: '';

        if (!$college_id) {
            $this->error = '学院ID不能为空';
            return false;
        }
        $data['plan'] =db::name('ck_plan')->where('plan_id',$plan_id)->find();

        $data['check_list'] = db::name('ck_task')->where('plan_id',$plan_id)->order('task_level,dt_begin')->select();

        $lab_ids = model('org')->where(array('pid'=>$college_id,'org_level'=>'lab'))->column('org_id');

        foreach ($data['check_list'] as $k=>$v){
            $data['check_list'][$k]['sum'] = $this->where(array('task_id'=>$v['task_id'],'org_id'=>['in',$lab_ids]))->count();
            $data['check_list'][$k]['finished'] = $this->where(array('task_id'=>$v['task_id'],'org_id'=>['in',$lab_ids],'check_state'=>'finished'))->count();
            $data['check_list'][$k]['state'] = $data['check_list'][$k]['finished'].'/'.$data['check_list'][$k]['sum'];
        }
        return $data;
    }

    /**
     * 分配的添加修改操作
     * @param  array   $param  [description]
     */
    public function handleData($param)
    {
        // 验证
        $validate = validate('Check');
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        $check_id = !empty($param['check_id'])? $param['check_id']: '';

        $zone_list = !empty($param['zone_list'])? $param['zone_list']: [];
        sort($zone_list);

        //$check_id不存在为添加，否则为修改
        if (!$check_id){

            $this->startTrans();
            try {

                $this->data($param)->allowField(true)->save();
                $check_id = $this->check_id;
                foreach ($zone_list as $k => $v){
                    if (empty($v))continue;
                    $room_list = model('room')->where('zone_id',$v['zone_id'])->column('room_id');
                    foreach ($room_list as $key => $value){
                        $arr[$key]['room_id']=$value;
                        $arr[$key]['zone_id']=$v['zone_id'];
                        $arr[$key]['group_id']=$v['group_id'];
                        $arr[$key]['check_id']=$check_id;
                        $arr[$key]['effort_state']='pending';
                    }
                }
                model('effort')->allowField(true)->saveAll($arr);
                $this->commit();
                return '检查任务添加成功';
            } catch(\Exception $e) {
                $this->rollback();
                $this->error = '添加失败';
                return false;
            }
        }else{
            foreach ($zone_list as $k => $v){

                $room_list = model('Effort')->where('zone_id',$v['zone_id'])
                    ->where('check_id',$check_id)
                    ->select();

                foreach ($room_list as $key => $value){
                    $value['group_id']=$v['group_id'];
                    $arr[]=$value;
                }
            }
            $this->startTrans();
            try {
                $this->allowField(true)->save($param, ['check_id' => $check_id]);
                model('effort')->allowField(true)->saveAll($arr);
                $this->commit();
                return '修改成功';
            } catch(\Exception $e) {
                $this->rollback();
                $this->error = '修改失败';
                return false;
            }
        }

    }

    public function progressData($param)
    {
        $task_id = !empty($param['task_id'])? $param['task_id']: '';
        if (!$task_id) {
            $this->error = '检查安排ID不能为空';
            return false;
        }

        $college_id = !empty($param['college_id'])? $param['college_id']: '';

        if (!$college_id) {
            $this->error = '学院ID不能为空';
            return false;
        }
        //获取登录用户的子单位ID组
        $map =[];
        $childIds = [];
        if ($GLOBALS['userInfo']['org_id'] != '1'){
            $childIds = model('org')->getAllChild($GLOBALS['userInfo']['org_id']);
            $childIds[]=$GLOBALS['userInfo']['org_id'];
        }
        if (!empty($childIds))$map['org_id'] = ['in', $childIds];

        $lab_ids = model('org')->where('pid',$college_id)->where($map)->column('org_id');

        $checklist = $this->alias('check')
            ->field('check.*,org.org_name as org_name')
            ->join('dc_org org','check.org_id=org.org_id','LEFT')
            ->where(array('check.task_id'=>$task_id,'check.org_id'=>['in',$lab_ids]))
            ->order('check.org_id')
            ->select();
        $arr=[];
        foreach ($checklist as $k => $v){
            $arr[$k]['org_id']=$v['org_id'];
            $arr[$k]['org_name']=$v['org_name'];
            $arr[$k]['dt_begin']=$v['dt_begin'];
            $arr[$k]['dt_end']=$v['dt_end'];

            //一个检查ID对应一个实验室的一次检查任务
            $ckgroup = model('effort')->where(array('check_id'=>$v['check_id']))->group('group_id')->column('group_id');
            foreach ($ckgroup as $key=>$val){
                $arr[$k]['group_list'][$key]=model('CkGroup')->field('group_id,group_name,leader_id,phone,members')->where(array('group_id'=>$val))->find();
                $arr[$k]['group_list'][$key]['leader_name']=model('Person')->where(array('username'=>$arr[$k]['group_list'][$key]['leader_id']))->value('name');
                $arr[$k]['group_list'][$key]['sum'] = model('effort')->where(array('check_id'=>$v['check_id'],'group_id'=>$val))->count();
                $arr[$k]['group_list'][$key]['finished'] = model('effort')->where(array('check_id'=>$v['check_id'],'effort_state'=>'finished','group_id'=>$val))->count();
                $arr[$k]['group_list'][$key]['progress'] = $arr[$k]['group_list'][$key]['finished'].'/'.$arr[$k]['group_list'][$key]['sum'];
            }
        }
        return $arr;
    }

    public function resultData($param){
        $task_id = !empty($param['task_id'])? $param['task_id']: '';
        if (!$task_id) {
            $this->error = '检查安排ID不能为空';
            return false;
        }

        $college_id = !empty($param['college_id'])? $param['college_id']: '';

        if (!$college_id) {
            $this->error = '学院ID不能为空';
            return false;
        }

        //获取登录用户的子单位ID组
        $childIds = model('org')->getAllChild($GLOBALS['userInfo']['org_id']);
        $childIds[]=$GLOBALS['userInfo']['org_id'];

//        $lab_ids = model('org')->where('pid',$college_id)->where('org_id',['in',$childIds])->column('org_id');
        $lab_ids = $this->alias('check')->where('check.task_id',$task_id)
            ->join('dc_org org','check.org_id = org.org_id')
            ->where('org.pid',$college_id)
            ->where('check.check_state','finished')
            ->field('org.org_id')
            ->select();
        $data=[];
        if (!empty($lab_ids)){
            $i = 0;
            foreach ($lab_ids as $v){
                $data[$i] = model('org')->where('org_id',$v['org_id'])->find();
                $room_ids = model('room')->where('lab_id',['in',$v['org_id']])->column('room_id');

                $data[$i]['check'] = $this->where('org_id',$v['org_id'])
                    ->where('task_id',$task_id)
                    ->find();
                $check_id = $data[$i]['check']['check_id'];
                $data[$i]['problem_list'] = model('Problem')
                    ->alias('problem')->field('problem.*,room.room_name')
                    ->join('dc_room room','problem.room_id = room.room_id','left')
                    ->where(array('problem.check_id'=>$check_id,'problem.room_id'=>['in',$room_ids],'problem.problem_level'=>'no'))
                    ->select();
                if ($check_id){
                    $data[$i]['result'] = model('Effort')
                        ->alias('effort')->field('effort.*,room.room_name')
                        ->join('dc_room room','effort.room_id = room.room_id','left')
                        ->where('effort.check_id',$check_id)
                        ->where('effort.room_id',['in',$room_ids])
                        ->select();
                    if (!empty($data[$i]['result'])){
                        foreach ($data[$i]['result'] as $key=>$val){
                            $data[$i]['result'][$key]['problem_fatal'] = model('Problem')->where(array('effort_id'=>$val['effort_id'],'item_level'=>'fatal','problem_level'=>'no'))->count();
                            $data[$i]['result'][$key]['problem_common'] = model('Problem')->where(array('effort_id'=>$val['effort_id'],'item_level'=>'common','problem_level'=>'no'))->count();
                        }
                    }
                }
                $i++;

            }
        }
        return $data;
    }


    public function  mycheck(){

        //获取登录用户的子单位ID组
        $username = $GLOBALS['userInfo']['username'];

        $group_ids = model('CkGroup')->where('leader_id',$username)->where('deleted','no')->column('group_id');
        $data = [];
        if (empty($group_ids)) {
            return $data;
        }
        $check_ids = model('effort')->where('group_id',['in',$group_ids])->group('check_id')->select();
        $data['plan'] = model('plan')->where('current','yes')->find();
        $data['task_list'] = $this->alias('check')
            ->field('check.org_id,org.org_name as org_name,check.dt_begin,check.dt_end,task.task_id,task.task_name,task.task_level')
            ->join('ck_task task','check.task_id=task.task_id')
            ->join('ck_plan plan','task.plan_id=plan.plan_id')
            ->join('ck_effort effort','check.check_id=effort.check_id')
            ->join('dc_org org','check.org_id=org.org_id')
            ->where('effort.group_id',['in',$group_ids])
            ->where('plan.current','yes')
            ->group('check.org_id')
            ->order('task.task_level,task.task_id')
            ->select();

        foreach ($data['task_list'] as $k=>$v){
            $data['task_list'][$k]['room_list'] = $this->alias('check')
                ->field('effort.*,room.room_name,room.lab_id,room.dept_id,org1.org_name as lab_name,org2.org_name as dept_name')
                ->join('ck_task task','check.task_id=task.task_id')
                ->join('ck_plan plan','task.plan_id=plan.plan_id')
                ->join('ck_effort effort','check.check_id=effort.check_id')
                ->join('dc_room room','room.room_id=effort.room_id')
                ->join('dc_org org1','room.lab_id=org1.org_id')
                ->join('dc_org org2','room.dept_id=org2.org_id')
                ->where('effort.group_id',['in',$group_ids])
                ->where('check.task_id',$v['task_id'])
                ->where('check.org_id',$v['org_id'])
                ->where('plan.current','yes')
                ->select();
        }
        return $data;
    }

    public function startcheck($param)
    {
        $plan_id = !empty($param['plan_id'])? $param['plan_id']: '';
        $level = !empty($param['task_level'])? $param['task_level']: '';
        $college_id = !empty($param['dept_id'])? $param['dept_id']: '';
        $lab_id = !empty($param['lab_id'])? $param['lab_id']: '';
        $check_id = !empty($param['check_id'])? $param['check_id']: '';
        $room_id = !empty($param['room_id'])? $param['room_id']: '';


        $choose = [
            ['plan_id'=>$plan_id,'level'=>$level,'lab_id'=>$lab_id],
            ['plan_id'=>$plan_id,'level'=>$level,'college_id'=>$college_id,'lab_id'=>'0'],
            ['plan_id'=>$plan_id,'level'=>$level,'college_id'=>'0','lab_id'=>'0']
        ];

        foreach ($choose as $k=>$v){
            $checklist = model('CkRule')->where($v)->select();
            if (!empty($checklist)){
                break;
            }
        }
        if (empty($checklist)){
            return '规则为空';
        }
        foreach ($checklist as $k => $v){
            $data[$k] =  model('Checklist')->field(['creator','dt_create'],true)->where('id',$v['checklist_id'])->find();
            $data[$k]['score'] =  $v['score'];
            $data[$k]['checkList']=model('item')->where('checklist_id',$v['checklist_id'])->select();
            foreach ($data[$k]['checkList'] as $key=>$val){
                $grade = model('problem')
                    ->where(array('check_id'=>$check_id,'item_id'=>$val['id'],'room_id'=>$room_id))
                    ->value('problem_level');
                $grade= !empty($grade) ? $grade:'yes';
                $data[$k]['checkList'][$key]['grade'] = $grade;

                $intro = model('problem')
                    ->where(array('check_id'=>$check_id,'item_id'=>$val['id'],'room_id'=>$room_id))
                    ->value('intro');
                $intro= !empty($intro) ? $intro:'';
                $data[$k]['checkList'][$key]['intro'] = $intro;
            }
        }

        return $data;
    }

    public function submitcheck($param){
        $flag = !empty($param['flag']) ? $param['flag']:'';
        $check_id = !empty($param['check_id']) ? $param['check_id']:'';
        $effort_id = !empty($param['effort_id']) ? $param['effort_id']:'';
        $room_id = !empty($param['room_id']) ? $param['room_id']:'';
        if (!$check_id) {
            $this->error = '实验室检查ID不能为空';
            return false;
        }
        if (!$effort_id) {
            $this->error = '房间检查ID不能为空';
            return false;
        }

        if (!$room_id) {
            $this->error = '房间ID不能为空';
            return false;
        }
        $data = [];
        $data['check_id'] = $check_id;
        $data['effort_id'] = $effort_id;
        $data['room_id'] = $room_id;
        $checklist = $param['checkList'];

        $this->startTrans();
        try {

            model('Problem')->where($data)->delete();

            foreach ($checklist as $k=>$v){

                if (!empty($v['checkList'])){
                    $arr= [];
                    foreach ($v['checkList'] as $key=>$val){

                        if ($val['grade'] !== 'yes'){
                            $arr[$key] = $data;
                            $arr[$key]['item_id'] = $val['id'];
                            $arr[$key]['item_name'] = $val['item_name'];
                            $arr[$key]['item_level'] = $val['item_level'];
                            $arr[$key]['problem_level'] = $val['grade'];
                            $arr[$key]['intro'] = !empty($val['intro']) ? $val['intro']:'';

                        }
                    }

                    model('Problem')->allowField(true)->saveAll($arr);
                }
            }


            if ($flag && $flag == 'submit'){
                $data['score'] = !empty($param['score']) ? $param['score']:'0';
                $data['effort_state'] = 'finished';
                $data['staff'] = $GLOBALS['userInfo']['username'];
                $data['dt_finish'] = time();

                model('Effort')->allowField(true)->save($data,['effort_id' => $effort_id]);

                $lab_finished = model('Effort')
                    ->where('check_id',$check_id)
                    ->where(function ($query) {
                        $query->where('effort_state', null)->whereor('effort_state', 'pending');
                    })
                    ->column('effort_id');
                if (empty($lab_finished)){
                    $effort_sum =  $this->alias('check')
                        ->field('count(effort.effort_id) as effort_count,sum(effort.score) as effort_sum')
                        ->join('ck_effort effort','effort.check_id=check.check_id')
                        ->where('check.check_id',$check_id)
                        ->find();
                    $updateData['check_score'] = $effort_sum['effort_sum']/$effort_sum['effort_count'];

                    $updateData['problem_fatal'] = model('Problem')
                        ->where('check_id',$check_id)
                        ->where('item_level','fatal')
                        ->where('problem_level','no')
                        ->count();

                    $updateData['problem_common'] = model('Problem')
                        ->where('check_id',$check_id)
                        ->where('item_level','common')
                        ->where('problem_level','no')
                        ->count();
                    $updateData['dt_finish'] = date("Y-m-d H:i:s",time());
                    $updateData['check_state'] = 'finished';
                    $this->allowField(true)->save($updateData,['check_id' => $check_id]);
                }
            }


            $this->commit();
            return '检查提交成功';
        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '检查提交失败';
            return false;
        }

    }

    /**
     * 检查反馈
     * @param  array   $param  [description]
     */
    public function  feedback($param){
        $check_id = !empty($param['check_id']) ? $param['check_id'] : '';
        if (empty($check_id)){ $this->error = '缺少检查ID';return false;}
        $review = !empty($param['review']) ? $param['review'] : '';

        $reply = !empty($param['reply']) ? $param['reply'] : '';

        $review_state = !empty($param['review_state']) ? $param['review_state'] : '';
        if (!$review)$review_state = 'no-need';


        if ($review_state != 'no-need'){
            $param['review_staff'] = $GLOBALS['userInfo']['username'];
            $param['dt_inform'] = date("Y-m-d H:i:s",time());
            $param['reply_staff'] = $GLOBALS['userInfo']['username'];
            $param['dt_reply'] = date("Y-m-d H:i:s",time());
        }

        $this->startTrans();
        try {
            $this->allowField(true)->save($param,['check_id' => $check_id]);
            $this->commit();
            return '整改提交成功';
        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '整改提交失败';
            return false;
        }


    }


    /**
     * 通过id删除用户信息
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