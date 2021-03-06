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
    protected $name = 'dc_org';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'dt_create';
    protected $updateTime = false;
    protected $insert = [
        'creator'
    ];
    //修改器
    protected function setCreatorAttr($value,$data)
    {

        return $GLOBALS['userInfo']['username'] ? $GLOBALS['userInfo']['username'] : 'admin';
    }
    //获取器
    //    public function getOrgStateAttr($value, $data){
    //        $get_data = ['yes'=>'开启','no'=>'关闭'];
    //        return $get_data[$value];
    //    }


    /**
     * [getDataList 获取列表]
     * @return    [array]
     */

    public function getDataList($param, $page, $limit)
    {
        //获取登录用户的子单位ID组
        $map = [];
        $orgIds =[];

        //$GLOBALS['group_id']>2 即代表校级管理员以下角色
        if ($GLOBALS['group_id'] > 2) {
            $childIds = getChildOrgIds($GLOBALS['userInfo']['org_id']);

            $parentIds = $this->getAllParent($GLOBALS['userInfo']['org_id']);
            $orgIds = array_merge($childIds,$parentIds);
        }

        if (!empty($orgIds))$map['org_id'] = ['in', $orgIds];

        //根据关键字查询
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        if ($keywords) $map['org_name'] = ['like', '%'.$keywords.'%'];
        //根据单位级别查询
        $org_level = !empty($param['org_level']) ? $param['org_level']: '';
        if ($org_level)$map['org_level'] = $org_level;
        //根据pid父级单位ID
        $pid = !empty($param['pid']) ? $param['pid']: '';
        if ($pid)  $map['pid'] = $pid;

        $list = $this->where($map);
        // 若有分页
        if ($page && $limit) $list = $list->page($page, $limit);

        $list = $list->order('org_level,org_id')->select();

        foreach ($list as  $k =>$v) {
            $v['pname'] = $this->where('org_id',$v['pid'])->value('org_name');
            if($v['org_level'] == 'college') $v['school_id']  = $v['pid'];

            if($v['org_level'] == 'lab'){
                $v['school_id'] = $this->where('org_id',$v['pid'])->value('pid');
                if ($v['school_id'] !== '0'){
                    $v['college_id'] = $v['pid'];
                }else{
                    $v['school_id']  = $v['pid'];
                    $v['college_id'] = '';
                }
            }
            $data[] = $v->toArray();
        }
        return $data;
    }

    /**
     * 通过id修改单位信息
     * @param  array   $param  [description]
     */
    public function handleData($param)
    {
        $org_id = !empty($param['org_id']) ? $param['org_id']: '';
        if ($org_id) {
            $checkData = $this->get($org_id);
            if (!$checkData) {
                $this->error = '暂无此数据';
                return false;
            }
            $org_level = $checkData['org_level'];

            $this->startTrans();
            try {
                $this->allowField(true)->save($param, ['org_id' => $org_id]);
                $this->commit();
                return '编辑成功';

            } catch(\Exception $e) {
                $this->rollback();
                $this->error = '编辑失败';
                return false;
            }
        }else{
            // 验证
            $validate = validate('Org');
            if (!$validate->check($param)) {
                $this->error = $validate->getError();
                return false;
            }
            $this->startTrans();
            try {
                $this->data($param)->allowField(true)->save();
                $this->commit();
                return '添加成功';
            } catch(\Exception $e) {
                $this->rollback();
                $this->error = '添加失败';
                return false;
            }
        }
    }

    public function delDataById($id = '', $delSon = false)
    {
        $this->startTrans();
        try {
            $childIds = $this->getAllChild($id);
            $roomIds = model('room')->where('lab_id|dept_id',$id)->find();
            if($childIds || $roomIds){
                $this->error = '还有子单位，不能删除';
                return false;
            }
            $this->where($this->getPk(), $id)->delete();
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
            if (empty($v['0']))unset($res[$k]);
        }
        //单位名称  父级单位  单位级别  地址  校区  单位负责人（学号）  单位负责人（姓名） 单位联系人（学号）  单位联系人（姓名）
        $titles=array('org_name','porg_name','org_level','org_address','campus','responsible_xh','responsible_xm','contacts_xh','contacts_xm');
        $add_count = 0; $jump_count = 0;
        $this->startTrans();
        try {
            foreach ( $res as $k => $v ) {
                $data = array();

                foreach ($titles as $ColumnIndex => $title) {
                    $data[$title] = $v[$ColumnIndex];
                }
                $org_id = $this->where(array('org_name'=>$data['org_name'],'org_level'=>strtolower($data['org_level'])))->value('org_id');
                if ($org_id) {
                    $jump_count++;continue;
                }
                $pid = $this->where(array('org_name'=>$data['porg_name'],'org_level'=>['neq',strtolower($data['org_level'])]))->value('org_id');
                if (!$pid){
                    $this->error = $data['org_name'].'父级单位不存在，请先去创建';
                    return false;
                }
                $data['pid'] = $pid;
                $data['org_level'] = strtolower($data['org_level']);
                $data['responsible'] = $data['responsible_xm'].'('.$data['responsible_xh'].')';
                $data['contacts'] = $data['contacts_xm'].'('.$data['contacts_xh'].')';
                $this->create($data,true);
                $add_count++;
            }
            $result['add'] = $add_count;
            $result['jump'] = $jump_count;
            $this->commit();
            return $result;
        } catch(\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }




}