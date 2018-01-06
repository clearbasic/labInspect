<?php
// +----------------------------------------------------------------------
// | Description: 检查小组
// +----------------------------------------------------------------------
// | Author: chenkq <chenkq@chingo.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;

class Check extends Checklogin
{

    public function checkindex()
    {
        $Model = model('check');
        $param = $this->param;
        $data = $Model->checkindex($param);
        if (!$data) {
            return resultArray(['error' => $Model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function allot()
    {
        $Model = model('check');
        $param = $this->param;
        $data = $Model->allot($param);
        if (!$data) {
            return resultArray(['error' => $Model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function baseinfo()
    {
        $Model = model('check');
        $param = $this->param;
        $data = $Model->baseinfo($param);
        if (!$data) {
            return resultArray(['error' => $Model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function feedback(){
        $Model = model('check');
        $param = $this->param;
        $data = $Model->feedback($param);
        if (!$data) {
            return resultArray(['error' => $Model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function handle()
    {
        $Model = model('check');
        $param = $this->param;
        $data = $Model->handleData($param);
        if (!$data) {
            return resultArray(['error' => $Model->getError()]);
        }
        return resultArray(['data' => $data]);
    }


    public function progress()
    {
        $Model = model('check');
        $param = $this->param;
        $data = $Model->progressData($param);
        if (!$data) {
            return resultArray(['error' => $Model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function mycheck()
    {

        $Model = model('check');
        $param = $this->param;
        $data = $Model->mycheck($param);
        if (!$data) {
            return resultArray(['error' => $Model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function showresult()
    {
        $Model = model('check');
        $param = $this->param;
        $data = $Model->resultData($param);
        if (!$data) {
            return resultArray(['error' => $Model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function startcheck()
    {
        $Model = model('check');
        $param = $this->param;
        $data = $Model->startcheck($param);
        if (!$data) {
            return resultArray(['error' => $Model->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    
    public function submitcheck()
    {
        $Model = model('check');
        $param = $this->param;
        $data = $Model->submitcheck($param);
        if (!$data) {
            return resultArray(['error' => $Model->getError()]);
        }
        return resultArray(['data' => $data]);
    }


}
