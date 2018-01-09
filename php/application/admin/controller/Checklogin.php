<?php
// +----------------------------------------------------------------------
// | Description: Api基础类，验证权限
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;


class Checklogin extends Common
{
    public function _initialize()
    {

        parent::_initialize();

        $no_check = config('no_check');
        $request = Request::instance();
        $method = $request->controller() .'/'.$request->action();

        if (!in_array($method,$no_check)){
            $param = $this->param;
            $controller = Request::instance()->controller();
            $app_key = !empty($param['app_key']) ? $param['app_key']: '';
            $timestamp = !empty($param['timestamp']) ? $param['timestamp']: '';
            $sign = !empty($param['sign']) ? $param['sign']: '';
            $app_secret = config('app_secret');
            $sign_token = md5($app_secret.$app_key.$timestamp.$app_secret);

            if ($sign !== $sign_token){
                echo json_encode(resultArray(['error' => '认证不通过']));
                die();
            }


            /*获取头部信息*/
            $header = Request::instance()->header();

            $authKey = $header['authkey'];
            $sessionId = $header['sessionid'];

            $cache = cache('Auth_'.$authKey);
            // 校验sessionid和authKey
            if (empty($sessionId)||empty($authKey)||empty($cache)) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(['code'=>101, 'error'=>'登录已失效']));
            }


            // 检查账号有效性
            $userInfo = $cache['userInfo'];
            $map['username'] = $userInfo['username'];
            $map['person_state'] = 'yes';
            if (!Db::name('dc_person')->where($map)->value('id')) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(['code'=>103, 'error'=>'账号已被删除或禁用']));
            }

            cache('Auth_'.$authKey, $cache, 3600);
            if ($cache['userInfo']['username'] !== 'admin'){
                $authAdapter = new AuthAdapter($authKey);
                $request = Request::instance();
                $ruleName = $request->module().'/'.$request->controller() .'/'.$request->action();
                if (!$authAdapter->checkLogin($ruleName, $cache['group_id'])) {
                    header('Content-Type:application/json; charset=utf-8');
                    exit(json_encode(['code'=>102,'error'=>'没有权限']));
                }
            }
            $GLOBALS['userInfo'] = $userInfo;
            $GLOBALS['group_id'] = $cache['group_id'];

        }



    }
}
