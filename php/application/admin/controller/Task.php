<?php
// +----------------------------------------------------------------------
// | Description: 检查安排
// +----------------------------------------------------------------------
// | Author: chenkq <chenkq@chingo.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;
use think\log;

class Task extends Checklogin
{

    public function index()
    {
            $model = model('task');
            $param = $this->param;
            $plan_id = !empty($param['plan_id']) ? $param['plan_id']: '';
            $keywords = !empty($param['keywords']) ? $param['keywords']: '';
            $page = !empty($param['page']) ? $param['page']: '';
            $limit = !empty($param['limit']) ? $param['limit']: '';
            $data = $model->getDataList($plan_id,$keywords, $page, $limit);
            if (!$data) {
                return resultArray(['error' => $model->getError()]);
            }
            return resultArray(['data' => $data]);
    }
    public function add()
    {
        $model = model('task');
        $param = $this->param;
        $data = $model->createData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function del()
    {
        $model = model('task');
        $param = $this->param;
        $data = $model->delDataById($param['task_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
    public function edit()
    {
        $model = model('task');
        $param = $this->param;
        $data = $model->updateDataById($param, $param['task_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
}