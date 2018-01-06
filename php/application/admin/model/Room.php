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
        return 'chingo';
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

        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        if ($keywords) {
            $map['username|name'] = ['like', '%'.$keywords.'%'];
        }
        $zone_id = !empty($param['zone_id']) ? $param['zone_id']: '';
        if ($zone_id) {
            $map['zone_id'] = $zone_id;
        }
        $list = $this->alias('room')->where($map);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
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
     * 通过id修改指标体系
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
     * 通过id修改用户信息
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

}