<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: chingo  <chenkq@chingo.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\Common;

class Groups extends Checklogin
{
    
    public function index()
    {   
        $groupModel = model('Group');
        $param = $this->param;
        $type = !empty($param['type'])? $param['type']: '';
        $data = $groupModel->getDataList($type);
        return resultArray(['data' => $data]);
    }

    public function read()
    {   
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function getRules()
    {
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->getRules($param);
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        }
        return resultArray(['data' => $data]);
//        return $data;
    }

    public function add()
    {
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function edit()
    {
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function del()
    {
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->delDataById($param['id'], true);       
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->delDatas($param['ids'], true);  
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => '删除成功']); 
    }

    public function enables()
    {
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->enableDatas($param['ids'], $param['status'], true);  
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
}
 