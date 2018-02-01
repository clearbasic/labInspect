<?php


namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;

class Checklist extends Checklogin
{

    public function index()
    {
        $model = model('Checklist');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $model->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }
    public function add()
    {
        $model = model('Checklist');
        $param = $this->param;

        $data = $model->createData($param);

        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function del()
    {
        $model = model('Checklist');
        $param = $this->param;
        $data = $model->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
    public function edit()
    {
        $model = model('Checklist');
        $param = $this->param;
        $data = $model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
}