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
class Org extends Checklogin
{
    public function index()
    {
        $model = model('org');
        $param = $this->param;
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $model->getDataList($param, $page, $limit);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function handle()
    {
        $model = model('org');
        $param = $this->param;
        $data = $model->handleData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function del()
    {
        $model = model('org');
        $param = $this->param;
        $data = $model->delDataById($param['org_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }



}