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
class Org extends Checklogin
{
    public function index()
    {
        $model = model('org');
        $param = $this->param;
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $model->getDataList($param, $page, $limit);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }
    public function handle()
    {
        $model = model('org');
        $param = $this->param;
        $data = $model->handleData($param);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function del()
    {
        $model = model('org');
        $param = $this->param;
        $data = $model->delDataById($param['org_id']);
        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    /*
    * 单位信息导入操作
    */
    public function orgImport(){

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
                    if (empty($v['0']))unset($res[$k]);
                }
                $count = count($res);
                $data['count'] = $count;
                $data['SaveName'] = $info->getSaveName();
                $data['org_list'] = $res;
                return resultArray(['data' => $data]);
            }
        }
    }

    public function  orgRunimport(){
        $model = model('Org');
        $param = $this->param;
        $data = $model->importData($param);

        if (!$data) {
            return resultArray(['error' => $model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

}