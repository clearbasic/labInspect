<?php
/**
 * Created by PhpStorm.
 * User: chingo
 * Date: 2018/1/2
 * Time: 16:27
 */
namespace app\admin\controller;

use think\console\command\make\Controller;
use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;

class Statistics extends Checklogin
{

    public function checkStatistics()
    {
        $groupModel = model('Statistics');
        $param = $this->param;
        $data = $groupModel->getCheckList($param);
        return resultArray(['data' => $data]);
    }

    public function excellentStatistics()
    {
        $groupModel = model('Statistics');
        $param = $this->param;
        $data = $groupModel->getExcellentList($param);
        return resultArray(['data' => $data]);
    }
    public function setExcellent()
    {
        $groupModel = model('Statistics');
        $param = $this->param;
        $data = $groupModel->setExcellent($param);
        return resultArray(['data' => $data]);
    }

    public function responTable()
    {
        $groupModel = model('Statistics');
        $param = $this->param;
        $data = $groupModel->responTable($param);
        return resultArray(['data' => $data]);
    }


}