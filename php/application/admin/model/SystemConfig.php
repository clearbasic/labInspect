<?php
// +----------------------------------------------------------------------
// | Description: 系统配置
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;

class SystemConfig extends Model 
{

    protected $name = 'system_parameter';

    public function getDataList($type ='')
    {
        $cat = new \com\Category('system_parameter', array('id', 'pid', 'title', 'title'));
        $data = $cat->getList('', 0, 'sort');
        $res = $data;
        // 若type为tree，则返回树状结构
        if ($type == 'tree') {
            $res = array();
            foreach ($data as $k => $v) {
                $data[$k]['checked'] = false;
            }
            $tree = new \com\Tree();
            $data = $tree->list_to_tree($data, 'id', 'pid', 'child', 0, true, array('pid'));
            foreach ($data as $k => $v){
                $res[$v['type']] = $v;
            }
        }

        return $res;
    }

	/**
	 * 批量修改配置
	 * @param  array   $param  [description]
	 */
	public function createData($param)
	{
		$list = [
		    ['id' => 1, 'value' => $param['SYSTEM_NAME']],
		    ['id' => 2, 'value' => $param['SYSTEM_LOGO']],
		    ['id' => 3, 'value' => $param['LOGIN_SESSION_VALID']],
		    ['id' => 4, 'value' => $param['IDENTIFYING_CODE']],	
		    ['id' => 5, 'value' => $param['LOGO_TYPE']],			
		];
		if ($this->saveAll($list)) {
			$data = $this->getDataList();
			cache('DB_CONFIG_DATA', $data, 3600);
			return $data;
		}
		$this->error = '更新失败';
		return false;
	}
}