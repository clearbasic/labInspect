<?php
// +----------------------------------------------------------------------
// | Description: 解决跨域问题
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\Controller;
use think\Request;

class Common extends Controller
{
    public $param;
    public function _initialize()
    {
        parent::_initialize();
        /*防止跨域*/      
        header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId");
        $param =  Request::instance()->param();
        $app_key = !empty($param['app_key']) ? $param['app_key']: '';
        $timestamp = !empty($param['timestamp']) ? $param['timestamp']: '';
        $sign = !empty($param['sign']) ? $param['sign']: '';
        $app_secret = config('app_secret');
        $sign_token = md5($app_secret.$app_key.$timestamp.$app_secret);
        if ($sign !== $sign_token){
            echo json_encode(resultArray(['error' => '认证不通过']));
            die();
        }
        $this->param = $param;
    }

    public function object_array($array) 
    {  
        if (is_object($array)) {  
            $array = (array)$array;  
        } 
        if (is_array($array)) {  
            foreach ($array as $key=>$value) {  
                $array[$key] = $this->object_array($value);  
            }  
        }  
        return $array;  
    }
}
 