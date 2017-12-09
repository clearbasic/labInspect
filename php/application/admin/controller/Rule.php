<?php


namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;

class Rule extends Common
{

    public function index()
    {
        $rule = model('rule');
        $param = $this->param;
        $cklist_id = !empty($param['checklist_id']) ? $param['checklist_id']: '';
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $rule->getDataList($cklist_id,$keywords, $page, $limit);
        if (!$data) {
            return resultArray(['error' => $rule->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function add()
    {
        $rule = model('rule');
        $param = $this->param;
        $data = $rule->createData($param);
        if (!$data) {
            return resultArray(['error' => $rule->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function del()
    {
        $rule = model('rule');
        $param = $this->param;
        $data = $rule->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $rule->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
    public function edit()
    {
        $rule = model('rule');
        $param = $this->param;
        $data = $rule->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $rule->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
}