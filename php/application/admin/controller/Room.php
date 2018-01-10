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

    /*
    * 实验室信息导入操作
    */
    public function roomImport(){

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $file = request()->file('file');
        if (!$file) {
            return resultArray(['error' => '请上传文件']);
        }

        $info = $file->validate(['ext'=>'xls,xlsx'])->move(ROOT_PATH . DS . 'uploads');
        if ($info) {
            $res = read ( 'uploads'. DS .$info->getSaveName() );
            $res = array_splice($res,1);
            if (!$res){
                return resultArray(['error' => '数据处理失败']);
            }else{
                foreach ($res as $k => $v){
                    if (empty($v['2']))unset($res[$k]);
                }
                $count = count($res);
                $data['count'] = $count;
                $data['SaveName'] = $info->getSaveName();
                $data['room_list'] = $res;
                return resultArray(['data' => $data]);
            }
        }
    }

    public function  roomRunimport(){
        $model = model('Room');
        $param = $this->param;
        $data = $model->importData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function roomExport()
    {
        $param = $this->param;

        $map= [];

        $college = !empty($param['college_id']) ? $param['college_id']: '';
        if ($college) $map['room.dept_id'] = $college;

        $lab = !empty($param['lab_id']) ? $param['lab_id']: '';
        if ($lab) $map['room.lab_id'] = $lab;

        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        if ($keywords) $map['room.room_name'] = ['like', '%'.$keywords.'%'];

        $zone_id = !empty($param['zone_id']) ? $param['zone_id']: '';
        if ($zone_id) $map['room.zone_id'] = $zone_id;


        $data = db::name('dc_room')->alias('room')
            ->field('org1.org_name as college,org2.org_name as lab,zone.zone_name as zone_name,room.room_name,room.building_name,room.agent_name,room.agent_id')
            ->join('dc_org org1','room.dept_id = org1.org_id')
            ->join('dc_org org2','room.lab_id = org2.org_id')
            ->join('dc_zone zone','room.zone_id = zone.zone_id')
            ->where($map)
            ->where('1 = 1')
            ->select();

        $fields = array(
            array('college','所属院系'),
            array('lab','所属实验室'),
            array('zone_name','房间分组'),
            array('room_name','房间名称'),
            array('building_name','楼宇'),
            array('agent_name','安全责任人'),
            array('agent_id','安全责任人学工号'),
        );
        excel_run_export($data,$fields,$filename = '房间基本信息');
    }


    public function roomAptitude()
    {
       $room_aptitude =  lab_aptitude();
       return resultArray(['data' => $room_aptitude]);

    }


}
