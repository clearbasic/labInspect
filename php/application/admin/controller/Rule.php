<?php
// +----------------------------------------------------------------------
// | Description: 检查规则
// +----------------------------------------------------------------------
// | Author: chenkq <chenkq@chingo.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;

class Rule extends Checklogin
{

    public function add()
    {
        $model = model('CkRule');
        $param = $this->param;
        $data = $model->createData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function copy()
    {
        $model = model('CkRule');
        $param = $this->param;
        $data = $model->copyData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function del()
    {
        $model = model('CkRule');
        $param = $this->param;
        $data = $model->delData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
    public function edit()
    {
        $model = model('CkRule');
        $param = $this->param;
        $data = $model->updateData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
}