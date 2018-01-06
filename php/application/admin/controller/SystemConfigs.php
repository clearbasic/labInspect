<?php
// +----------------------------------------------------------------------
// | Description: 系统配置
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\Common;

class SystemConfigs extends Common
{
    public function save()
    {
        $configModel = model('SystemConfig');
        $param = $this->param;
        $data = $configModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $configModel->getError()]);
        } 
        return resultArray(['data' => '添加成功']);	
    }

}
 