<?php
/**
 * Created by PhpStorm.
 * User: chingo
 * Date: 2018/1/3
 * Time: 14:35
 */

namespace app\admin\controller;

use app\common\controller\Common;

class Roles extends Common
{

    public function index()
    {
        $userModel = model('Role');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $userModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {
        $groupModel = model('Role');
        $param = $this->param;
        $data = $groupModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function add()
    {
        $groupModel = model('Role');
        $param = $this->param;
        $data = $groupModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        }
        return resultArray(['data' => '添加成功']);
    }

    public function edit()
    {
        $groupModel = model('Role');
        $param = $this->param;
        $data = $groupModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }

    public function del()
    {
        $groupModel = model('Role');
        $param = $this->param;
        $data = $groupModel->delData($param);
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function deletes()
    {
        $groupModel = model('Role');
        $param = $this->param;
        $data = $groupModel->delDatas($param['ids'], true);
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function enables()
    {
        $groupModel = model('Role');
        $param = $this->param;
        $data = $groupModel->enableDatas($param['ids'], $param['status'], true);
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        }
        return resultArray(['data' => '操作成功']);
    }
}
