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

    public function recommendStatistics()
    {
        $groupModel = model('Statistics');
        $param = $this->param;
        $data = $groupModel->getRecommendList($param);
        return resultArray(['data' => $data]);
    }


    public function setRecommend()
    {
        $groupModel = model('Statistics');
        $param = $this->param;
        $data = $groupModel->setRecommend($param);
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

    public function responExport()
    {
        $groupModel = model('Statistics');
        $param = $this->param;
        $data = $groupModel->responTable($param);

        $fields = array(
            array('username','用户名'),
            array('name','姓名'),
            array('org_name','单位名称'),
            array('mobile','移动电话'),
            array('email','邮箱'),
        );
        excel_run_export($data,$fields,$filename = '安全责任人登记表');
    }

}