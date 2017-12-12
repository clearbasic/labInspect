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
class Plan extends Common
{
    public function index()
    {
        $plan = model('plan');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $plan->getDataList($keywords, $page, $limit);
        if (!$data) {
            return resultArray(['error' => $plan->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function add()
    {
        $plan = model('plan');
        $param = $this->param;
        $data = $plan->createData($param);
        if (!$data) {
            return resultArray(['error' => $plan->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function del()
    {
        $plan = model('plan');
        $param = $this->param;
        $data = $plan->delDataById($param['plan_id']);
        if (!$data) {
            return resultArray(['error' => $plan->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
    public function edit()
    {
        $plan = model('plan');
        $param = $this->param;
        $data = $plan->updateDataById($param, $param['plan_id']);
        if (!$data) {
            return resultArray(['error' => $plan->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
    public function current()
    {
        $plan = model('plan');
        $param = $this->param;
        $data = $plan->setcurrentById($param['plan_id']);
        if (!$data) {
            return resultArray(['error' => $plan->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
    public function getdata()
    {
        $plan = model('plan');
        $param = $this->param;
        $data = $plan->planData($param['plan_id']);
        if (!$data) {
            return resultArray(['error' => $plan->getError()]);
        }
        return resultArray(['data' => $data]);
    }
}