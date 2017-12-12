<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;
use think\db;
class Person extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $name = 'dc_person';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'dt_create';
    protected $updateTime = false;
    protected $insert = [
        'creator' => 'chingo',
    ];
    /**
     * [getDataList 获取列表]
     * @return    [array]
     */
    public function getDataList($param, $page, $limit)
    {
        $map = [];
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        if ($keywords) {
            $map['username|name'] = ['like', '%'.$keywords.'%'];
        }

        $list = $this->alias('person')->where($map);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list
            ->join('dc_org org', 'person.org_id=org.org_id', 'LEFT')
            ->field('person.*,org.org_name as org_name')
            ->order('person.firstchar')
            ->select();
        $data = $list;
        return $data;
    }

    /**
     * 通过id修改指标体系
     * @param  array   $param  [description]
     */
    public function addData($param)
    {
            // 验证
            $validate = validate('Person');
            if (!$validate->check($param)) {
                $this->error = $validate->getError();
                return false;
            }

            $param['firstchar']=GetFirstCharter($param['name']);
            $param['password_salt']=random(10);
            $param['password']=encrypt_password($param['password'],$param['password_salt']);

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
    /**
     * 通过id修改用户信息
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
            $this->allowField(true)->save($param, ['id' => $id]);
            $this->commit();
            return true;

        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '编辑失败';
            return false;
        }
    }
}