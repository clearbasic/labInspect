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
class Person extends Checklogin
{
    public function index()
    {

        $model = model('Person');
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
        $model = model('Person');
        $param = $this->param;
        $data = $model->addData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function del()
    {
        $model = model('Person');
        $param = $this->param;
        $data = $model->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
    public function edit()
    {
        $model = model('Person');
        $param = $this->param;
        $data = $model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
    public function getuser()
    {
        $model = model('Person');
        $param = $this->param;
        $username = !empty($param['username']) ? $param['username']: '';
        $data = $model->getuser($username);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

}