<?php
// +----------------------------------------------------------------------
// | Description: 机构单位
// +----------------------------------------------------------------------
// | Author: chenkq <chenkq@chingo.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;
class Person extends Checklogin
{
    public function index()
    {

        $model = model('Person');
        $param = $this->param;
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $model->getDataList($param, $page, $limit);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function add()
    {
        $model = model('Person');
        $param = $this->param;
        $data = $model->addData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function del()
    {
        $model = model('Person');
        $param = $this->param;
        $data = $model->delDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
    public function edit()
    {
        $model = model('Person');
        $param = $this->param;
        $data = $model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }
    public function getuser()
    {
        $model = model('Person');
        $param = $this->param;
        $username = !empty($param['username']) ? $param['username']: '';
        $data = $model->getuser($username);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    /*
    * 人员信息导入操作
    */
    public function personImport(){

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
}