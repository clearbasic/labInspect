<?php


namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;

class Task extends Common
{

    public function index()
    {
        $task = model('task');
        $param = $this->param;
        $plan_id = !empty($param['plan_id']) ? $param['plan_id']: '';
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $task->getDataList($plan_id,$keywords, $page, $limit);
        if (!$data) {
            return resultArray(['error' => $task->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function add()
    {
        $task = model('task');
        $param = $this->param;
        $data = $task->createData($param);
        if (!$data) {
            return resultArray(['error' => $task->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function del()
    {
        $task = model('task');
        $param = $this->param;
        $data = $task->delDataById($param['task_id']);
        if (!$data) {
            return resultArray(['error' => $task->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
    public function edit()
    {
        $task = model('task');
        $param = $this->param;
        $data = $task->updateDataById($param, $param['task_id']);
        if (!$data) {
            return resultArray(['error' => $task->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
}