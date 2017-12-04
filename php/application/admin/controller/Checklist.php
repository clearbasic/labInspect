<?php


namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;


class Checklist extends Common
{

    public function index()
    {
        $clist = model('Checklist');
        $param = $this->param;
        $username = $param['user'];
        p($username);
    }


}