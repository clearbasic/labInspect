<?php
// +----------------------------------------------------------------------
// | Description: 房间分组
// +----------------------------------------------------------------------
// | Author: chenkq <chenkq@chingo.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;

class Zone extends Checklogin
{
    public function index()
    {
        $model = model('Zone');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $model->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function add()
    {
        $model = model('Zone');
        $param = $this->param;
        $data = $model->createData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '添加成功']);
    }

    public function edit()
    {
        $model = model('Zone');
        $param = $this->param;
        $data = $model->updateDataById($param, $param['zone_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }

    public function del()
    {
        $model = model('Zone');
        $param = $this->param;
        $data = $model->delById($param['zone_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function deletes()
    {
        $model = model('Zone');
        $param = $this->param;
        $data = $model->delDatas($param['zone_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

}
