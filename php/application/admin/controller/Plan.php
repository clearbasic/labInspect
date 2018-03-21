<?php
// +----------------------------------------------------------------------
// | Description: 检查期次
// +----------------------------------------------------------------------
// | Author: chenkq <chenkq@chingo.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;
class Plan extends Checklogin
{
    public function index()
    {
        $model = model('plan');
        $param = $this->param;
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $model->getDataList($param, $page, $limit);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function add()
    {
        $model = model('plan');
        $param = $this->param;
        $data = $model->createData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '添加成功']);
    }
    public function del()
    {
        $model = model('plan');
        $param = $this->param;
        $data = $model->delDataById($param['plan_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
    public function edit()
    {
        $model = model('plan');
        $param = $this->param;
        $data = $model->updateDataById($param, $param['plan_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
    public function current()
    {
        $model = model('plan');
        $param = $this->param;
        $data = $model->setcurrentById($param['plan_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
    public function getdata()
    {
        $model = model('plan');
        $param = $this->param;
        $data = $model->planData($param['plan_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }
}