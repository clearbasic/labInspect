<?php
// +----------------------------------------------------------------------
// | Description: 房间管理
// +----------------------------------------------------------------------
// | Author: chenkq <chenkq@chingo.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;

class Room extends Checklogin
{
    public function index()
    {
        $model = model('Room');
        $param = $this->param;
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $model->getDataList($param, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function add()
    {
        $model = model('Room');
        $param = $this->param;
        $data = $model->createData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '添加成功']);
    }

    public function edit()
    {
        $model = model('Room');
        $param = $this->param;
        $data = $model->updateDataById($param, $param['room_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }

    public function del()
    {
        $model = model('Room');
        $param = $this->param;
        $data = $model->delById($param['room_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function deletes()
    {
        $model = model('Room');
        $param = $this->param;
        $data = $model->delDatas($param['room_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

}
