<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;
use think\db;

class Group extends Common 
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
	protected $name = 'admin_group';

	/**
	 * [getDataList 获取列表]
	 * @linchuangbin
	 * @DateTime  2017-02-10T21:07:18+0800
	 * @return    [array]                         
	 */
	public function getDataList($type)
	{
		$cat = new \com\Category('admin_group', array('id', 'pid', 'title', 'title'));

		$data = $cat->getList('', 0, 'id');

		if ($GLOBALS['group_id'] != '1'){
            $groups = $this->getAllChild($GLOBALS['group_id']);
            $groups[]=$GLOBALS['group_id'];
            foreach ($data as $k => $v){
                if (!in_array($v['id'],$groups))unset($data[$k]);
            }
        }
        sort($data);
        // 若type为tree，则返回树状结构
        if ($type == 'tree') {
            foreach ($data as $k => $v) {
                $data[$k]['checked'] = false;
            }
            $tree = new \com\Tree();
            $data = $tree->list_to_tree($data, 'id', 'pid', 'child', 0, true, array('pid'));
        }
		return $data;
	}

	public function getRules($param)
    {
        $group_id = !empty($param['group_id']) ? $param['group_id']: '0';
        if ($group_id !== '0'){
            $groups = $this->where('id',$group_id)->select();
            $ruleIds = [];
            foreach($groups as $k => $v) {
                $ruleIds = array_unique(array_merge($ruleIds, explode(',', $v['rules'])));
            }
            $ruleMap['id'] = array('in', $ruleIds);
        }

        $ruleMap['status'] = 1;

        // 重新设置ruleIds，除去部分已删除或禁用的权限。
        $rules =Db::name('admin_rule')->where($ruleMap)->select();
        foreach ($rules as $k => $v) {
            $ruleIds[] = $v['id'];
            $rules[$k]['name'] = strtolower($v['name']);
        }
        empty($ruleIds)&&$ruleIds = '';
        //处理菜单成树状
        $tree = new \com\Tree();
        // 处理规则成树状
        $ret = $tree->list_to_tree($rules, 'id', 'pid', 'child', 0, true, array('pid'));

        return $ret;
    }

    /**
     * [createData 新建]
     * @linchuangbin
     * @DateTime  2017-02-10T21:19:06+0800
     * @param     array                    $param [description]
     * @return    [array]                         [description]
     */
    public function createData($param)
    {

        // 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }

        try {
            $this->data($param)->allowField(true)->save();
            return true;
        } catch(\Exception $e) {
            $this->error = '添加失败';
            return false;
        }
    }

    /**
     * [updateDataById 编辑]
     * @linchuangbin
     * @DateTime  2017-02-10T21:24:49+0800
     * @param     [type]                   $param [description]
     * @param     [type]                   $id    [description]
     * @return    [type]                          [description]
     */
    public function updateDataById($param, $id)
    {
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = '暂无此数据';
            return false;
        }

        // 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }


        try {
            $this->allowField(true)->save($param, [$this->getPk() => $id]);
            return true;
        } catch(\Exception $e) {
            $this->error = '编辑失败';
            return false;
        }
    }
}