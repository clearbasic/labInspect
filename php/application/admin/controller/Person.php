<?php
// +----------------------------------------------------------------------
// | Description: 机构单位
// +----------------------------------------------------------------------
// | Author: chenkq <chenkq@chingo.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;
class Person extends Common
{
    public function index()
    {
        $plan = model('Person');
        $param = $this->param;
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $plan->getDataList($param, $page, $limit);
        if (!$data) {
            return resultArray(['error' => $plan->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function add()
    {
        $plan = model('Person');
        $param = $this->param;
        $data = $plan->addData($param);
        if (!$data) {
            return resultArray(['error' => $plan->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function del()
    {
        $plan = model('Person');
        $param = $this->param;
        $data = $plan->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $plan->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
    public function edit()
    {
        $plan = model('Person');
        $param = $this->param;
        $data = $plan->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $plan->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }


}