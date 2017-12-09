<?php


namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;

class Checklist extends Common
{

    public function index()
    {

        $clist = model('Checklist');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $clist->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }
    public function add()
    {
        $clist = model('Checklist');
        $param = $this->param;

        $data = $clist->createData($param);

        if (!$data) {
            return resultArray(['error' => $clist->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function del()
    {
        $clist = model('Checklist');
        $param = $this->param;
        $data = $clist->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $clist->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
    public function edit()
    {
        $clist = model('Checklist');
        $param = $this->param;
        $data = $clist->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $clist->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
}