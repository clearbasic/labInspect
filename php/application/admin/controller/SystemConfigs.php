<?php
// +----------------------------------------------------------------------
// | Description: 系统配置
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\Common;

class SystemConfigs extends Common
{

    public function index()
    {
        $menuModel = model('SystemConfig');
        $param = $this->param;
        $type = !empty($param['type'])? $param['type']: '';
        $data = $menuModel->getDataList($type);
        return resultArray(['data' => $data]);
    }
    public function save()
    {
        $configModel = model('SystemConfig');
        $param = $this->param;
        $data = $configModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $configModel->getError()]);
        } 
        return resultArray(['data' => '添加成功']);	
    }
    public function add()
    {
        $menuModel = model('SystemConfig');
        $param = $this->param;
        $data = $menuModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $menuModel->getError()]);
        }
        return resultArray(['data' => '添加成功']);
    }

    public function edit()
    {
        $menuModel = model('SystemConfig');
        $param = $this->param;
        $data = $menuModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $menuModel->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
}
 