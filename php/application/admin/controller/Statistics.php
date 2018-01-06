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
    public function room_export()
    {
        $data = db::name('dc_room')->alias('room')
            ->field('org1.org_name as college,org2.org_name as lab,room.room_name,room.building_name,room.agent_name,room.agent_id')
            ->join('dc_org org1','room.dept_id = org1.org_id')
            ->join('dc_org org2','room.lab_id = org2.org_id')
            ->where('1 = 1')
            ->select();

        $fields = array(
            array('college','所属院系'),
            array('lab','所属实验室'),
            array('room_name','房间名称'),
            array('building_name','楼宇'),
            array('agent_name','安全责任人'),
            array('agent_id','安全责任人学工号'),
        );
        excel_run_export($data,$fields,$filename = '房间基本信息');
    }

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
        $data = $groupModel->responTable();
        return resultArray(['data' => $data]);
    }


}