<?php
// +----------------------------------------------------------------------
// | Description: 菜单
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Menus extends Checklogin
{
    
    public function index()
    {   
        $menuModel = model('Menu');
        $param = $this->param;
        $type = !empty($param['type'])? $param['type']: '';
        $data = $menuModel->getDataList($type);
        return resultArray(['data' => $data]);
    }

    public function read()
    {   
        $menuModel = model('Menu');
        $param = $this->param;
        $data = $menuModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $menuModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function add()
    {
        $menuModel = model('Menu');
        $param = $this->param;
        $data = $menuModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $menuModel->getError()]);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function edit()
    {
        $menuModel = model('Menu');
        $param = $this->param;
        $data = $menuModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $menuModel->getError()]);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function del()
    {
        $menuModel = model('Menu');
        $param = $this->param;
        $data = $menuModel->delDataById($param['id'], true);       
        if (!$data) {
            return resultArray(['error' => $menuModel->getError()]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $menuModel = model('Menu');
        $param = $this->param;
        $data = $menuModel->delDatas($param['ids'], true);  
        if (!$data) {
            return resultArray(['error' => $menuModel->getError()]);
        } 
        return resultArray(['data' => '删除成功']); 
    }

    public function enables()
    {
        $menuModel = model('Menu');
        $param = $this->param;
        $data = $menuModel->enableDatas($param['ids'], $param['status'], true);  
        if (!$data) {
            return resultArray(['error' => $menuModel->getError()]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
}
 