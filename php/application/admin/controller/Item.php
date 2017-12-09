<?php


namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;

class Item extends Common
{

    public function index()
    {
        $item = model('Item');
        $param = $this->param;
        $cklist_id = !empty($param['checklist_id']) ? $param['checklist_id']: '';
        $keywords  = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $item->getDataList($cklist_id,$keywords, $page, $limit);
        if (!$data) {
            return resultArray(['error' => $item->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function add()
    {
        $item = model('Item');
        $param = $this->param;
        $data = $item->createData($param);
        if (!$data) {
            return resultArray(['error' => $item->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function del()
    {
        $item = model('Item');
        $param = $this->param;
        $data = $item->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $item->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
    public function edit()
    {
        $item = model('Item');
        $param = $this->param;
        $data = $item->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $item->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
}