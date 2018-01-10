<?php
/**
 * Created by PhpStorm.
 * User: chingo
 * Date: 2018/1/4
 * Time: 8:45
 */

namespace app\admin\model;

use think\Db;
use app\admin\model\Common;
use com\verify\HonrayVerify;

class Role extends Common
{
    protected $name = 'admin_access';
    /**
     * [getDataList 列表]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:19:57+0800
     * @param     [string]                   $keywords [关键字]
     * @param     [number]                   $page     [当前页数]
     * @param     [number]                   $limit    [t每页数量]
     * @return    [array]                             [description]
     */
    public function getDataList($keywords, $page, $limit)
    {
        $map = [];
        if ($keywords) {
            $map['username|realname'] = ['like', '%'.$keywords.'%'];
        }

        // 默认除去超级管理员
//        $map['access.user_id'] = array('neq', 1);

        $list = $this
            ->where($map)
            ->alias('access')
            ->join('dc_person person', 'access.username=person.username', 'LEFT')
            ->join('dc_org org', 'access.org_id=org.org_id', 'LEFT')
            ->join('admin_group group', 'access.group_id=group.id', 'LEFT');

        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }

        $list = $list
            ->field('access.*,person.name as p_name, org.org_name as o_name,group.title as g_name')
            ->order('group.group_level,org.org_level')
            ->select();
        $data = $list;
        return $data;
    }

    /**
     * 分配用户角色
     * @param  array   $param  [description]
     */
    public function createData($param)
    {

        $this->startTrans();
        try {
            $this->data($param)->allowField(true)->save();
            $this->commit();
            return true;
        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '添加失败';
            return false;
        }

    }
    /**
     * 分配用户角色
     * @param  array   $param  [description]
     */
    public function delData($param)
    {

        if (empty($param['username'])) {
            $this->error = '缺少用户名';
            return false;
        }
        if (empty($param['org_id'])) {
            $this->error = '缺少单位ID';
            return false;
        }
        if (empty($param['group_id'])) {
            $this->error = '缺少角色ID';
            return false;
        }
        $this->startTrans();
        try {
            $this->where(array('username'=>$param['username'],'org_id'=>$param['org_id'],'group_id'=>$param['group_id']))->delete();
            $this->commit();
            return true;
        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '删除失败';
            return false;
        }

    }
}