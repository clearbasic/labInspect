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
        //获取登录用户的子单位ID组
        $map = [];
        $orgIds =[];
        if ($GLOBALS['userInfo']['org_id'] != '1'){
            $childIds = $this->getAllChild($GLOBALS['userInfo']['org_id']);
            $childIds[]=$GLOBALS['userInfo']['org_id'];
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
        }
        $data = $list;
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

    public function orgData()
    {
        $data['school_list'] = $this
            ->where('org_level','school')
            ->field('org_id,org_name,org_level,org_alias,org_address,contacts,responsible,org_state,org_order')
            ->select();
        $data['college_list'] = $this
            ->alias('a')
            ->join('dc_org b', 'b.pid=a.org_id', 'LEFT')
            ->where('b.org_level','college')
            ->field('b.org_id,b.pid as school_id,b.org_name,b.org_level,b.org_alias,b.org_address,
            b.contacts,b.responsible,b.org_state,b.org_order')
            ->order('b.org_order')
            ->select();
        $data['lab_list'] = $this
            ->alias('a')
            ->join('dc_org b', 'b.pid=a.org_id', 'LEFT')
            ->join('dc_org c', 'c.pid=b.org_id', 'LEFT')
            ->where('c.org_level','lab')
            ->field('c.*,b.pid as school_id,c.pid as college_id')
            ->order('c.org_order')
            ->select();
        return $data;
    }
}