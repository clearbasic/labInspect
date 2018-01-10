<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;
use think\db;
class Room extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $name = 'dc_room';
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
        if ($GLOBALS['userInfo']['org_id'] != '1'){
            $childIds = model('org')->getAllChild($GLOBALS['userInfo']['org_id']);
            $childIds[]=$GLOBALS['userInfo']['org_id'];
            if (!empty($childIds))$map['room.lab_id'] = ['in', $childIds];
        }

        $college = !empty($param['college_id']) ? $param['college_id']: '';
        if ($college) $map['room.dept_id'] = $college;

        $lab = !empty($param['lab_id']) ? $param['lab_id']: '';
        if ($lab) $map['room.lab_id'] = $lab;

        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        if ($keywords) $map['room_name'] = ['like', '%'.$keywords.'%'];

        $zone_id = !empty($param['zone_id']) ? $param['zone_id']: '';
        if ($zone_id) $map['room.zone_id'] = $zone_id;

        $list = $this->alias('room')->where($map);
        // 若有分页
        if ($page && $limit) $list = $list->page($page, $limit);
        $list = $list
            ->join('dc_org org1', 'room.dept_id=org1.org_id', 'LEFT')
            ->join('dc_org org2', 'room.lab_id=org2.org_id', 'LEFT')
            ->field('room.*,org1.org_name as dept_name,org2.org_name as lab_name')
            ->order('room.room_order')
            ->select();
        $data = $list;
        return $data;
    }

    /**
     * 创建新房间
     * @param  array   $param  [description]
     */
    public function createData($param)
    {
        // 验证
        $validate = validate('Room');
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        $room_order = !empty($param['room_order']) ? $param['room_order']: '';
        $zone_id = !empty($param['zone_id']) ? $param['zone_id']: '';
        $arr = [];
        $effort = [];
        $i = 0;
        foreach ($param['room_name'] as $k => $v){
            $arr[$i]= $param;
            $arr[$i]['room_name'] = $v;
            $arr[$i]['room_order'] = $room_order+$i;
            $i++;
        }

        $check = model('Effort')->where('zone_id',$zone_id)->column('check_id,group_id');

        $this->startTrans();
        try {
            $new = $this->allowField(true)->saveAll($arr);
            foreach ($new as $k=> $v){
                $effort[] = $v['room_id'];
            }
            if (!empty($check)){
                $i = 0;
                foreach ($effort as $k=>$v){
                    foreach ($check as $key => $val){
                        $insertEffort[$i]['check_id'] = $key;
                        $insertEffort[$i]['zone_id'] = $zone_id;
                        $insertEffort[$i]['group_id'] = $val;
                        $insertEffort[$i]['room_id'] = $v;
                        $insertEffort[$i]['effort_state'] = 'pending';
                        $i++;
                    }
                }
                model('effort')->allowField(true)->saveAll($insertEffort);
            }

            $this->commit();
            return '房间信息添加成功';
        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '房间信息有重复，添加失败';
            return false;
        }
    }
    /**
     * 通过房间id修改房间信息
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
            $this->allowField(true)->save($param, ['room_id' => $id]);
            if ($param['zone_id'] != $checkData['zone_id'] ){
                model('Effort')->where('room_id',$id)->delete();
                $check = model('Effort')->where('zone_id',$param['zone_id'])->column('check_id,group_id');
                if (!empty($check)){
                    $i = 0;
                    $insertEffort= [];
                    foreach ($check as $key => $val){
                        $insertEffort[$i]['check_id'] = $key;
                        $insertEffort[$i]['zone_id'] = $param['zone_id'];
                        $insertEffort[$i]['group_id'] = $val;
                        $insertEffort[$i]['room_id'] = $id;
                        $i++;
                    }
                    model('effort')->allowField(true)->saveAll($insertEffort);
                }
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
     * 通过id删除房间
     * @param  array   $param  [description]
     */
    public function delById($id,$delSon = false)
    {
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = '暂无此数据';
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
            model('Effort')->where('room_id',$id)->delete();
            $this->commit();
            return true;
        } catch(\Exception $e) {
            $this->error = '删除失败';
            $this->rollback();
            return false;
        }
    }

    /**
     * 导入xls的信息
     * @param  array   $param  [description]
     */
    public function importData($param)
    {
        $SaveName = !empty($param['SaveName']) ? $param['SaveName'] : '';
        if (!$SaveName){
            $this->error = '文件不存在，读取失败';
            return false;
        }
        $res = read ( 'uploads'. DS .$SaveName );
        $res = array_splice($res,1);
        foreach ($res as $k => $v){
            if (empty($v['3']))unset($res[$k]);
        }
        //学院 实验室 房间分组  房间名 所在楼  责任人ID  责任人姓名
        $titles=array('college','lab','zone','room_name','building_name','agent_id','agent_name');

        $add_count = 0;
        $jump_count = 0;
        $this->startTrans();
        try {
            $insert = array();
            foreach ( $res as $k => $v ) {

                $data = array();
                foreach ($titles as $ColumnIndex => $title) {
                    $data[$title] = $v[$ColumnIndex];
                }
                if(empty($data['room_name'])){
                    continue;
                }

                //判断院系信息是否存在 不存在需要插入院系信息
                if ($data['college']){
                    $dept_id = model('org')->where(array('org_name'=>$data['college'],'org_level'=>'college'))->value('org_id');
                    if (!$dept_id){
                        $arr1 = [];
                        $arr1['org_level'] = 'college';
                        $arr1['pid'] = 1;
                        $arr1['org_name'] = $data['college'];
                        $arr1['org_state'] = 'yes';
//                    $arr['creator'] = $GLOBALS['userInfo']['username'];
//                    $arr['dt_create'] = time();
                        $org = new Org();
                        $org->allowField(true)->save($arr1);
                        $dept_id = $org->org_id;
                    }
                }

                //判断实验室信息是否存在 不存在需要插入实验室信息
                if ($data['lab']){
                    $lab_id = model('org')->where(array('org_name'=>$data['lab'],'org_level'=>'lab'))->value('org_id');
                    if (!$lab_id){
                        $arr2 = [];
                        $arr2['org_level'] = 'lab';
                        $arr2['pid'] = $dept_id;
                        $arr2['org_name'] = $data['lab'];
                        $arr2['org_state'] = 'yes';
//                    $arr['creator'] = $GLOBALS['userInfo']['username'];
//                    $arr['dt_create'] = time();
                        $org = new Org();
                        $org->allowField(true)->save($arr2);
                        $lab_id = $org->org_id;
                    }
                }

                //判断房间分组信息是否存在
                if ($data['zone']){
                    $zone_id = model('zone')->where(array('zone_name'=>$data['zone'],'org_id'=>$lab_id))->value('zone_id');
                    if (!$zone_id){
                        $arr3 = [];
                        $arr3['org_id'] = $lab_id;
                        $arr3['zone_name'] = $data['zone'];
                        $Zone = new Zone();
                        $Zone->allowField(true)->save($arr3);
                        $zone_id = $Zone->zone_id;
                    }
                }

                //判断人员信息是否存在
                if ($data['agent_id'] || $data['agent_name']){
                    $map = [];
                    if (!empty($data['agent_id']))$map['username']=$data['agent_id'];
                    if (!empty($data['agent_name']))$map['name']=$data['agent_name'];
                    $id = model('person')->where($map)->value('id');
                    if (!$id){
                        $arr4 = [];
                        $arr4['name'] = $data['agent_name'];
                        $arr4['username'] = $data['agent_id'];
                        $arr4['org_id'] = $GLOBALS['userInfo']['org_id'];
                        $arr4['password'] = config('default_password');
                        $arr4['password_salt']=random(10);
                        $arr4['password']=encrypt_password($arr4['password'],$arr4['password_salt']);
                        $Person = new Person();
                        $Person->allowField(true)->save($arr4);
                    }
                }
                //判断房间是否已经存在
                if ($data['room_name']){
                    $room_id = $this->where(array('room_name'=>$data['room_name'],'dept_id'=>$dept_id,'lab_id'=>$lab_id))->value('room_id');
                }
                $data['dept_id'] = $dept_id;
                $data['lab_id']  = $lab_id;
                $data['zone_id']  = $zone_id;

                if (!$room_id){
                    $insert[$k] = $data;
                    $add_count++;
                }else{
                    $jump_count++;
                }
            }
            $end =  $this->allowField(true)->saveAll($insert);
            $result['add'] = count($end);
            $result['jump'] = $jump_count;
            $this->commit();
            return $result;
        } catch(\Exception $e) {
            $this->error = '一次导入数据量过大，请分批导入。';
            $this->rollback();
            return false;
        }
    }
}