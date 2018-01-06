<?php

/**
 * 行为绑定
 */
\think\Hook::add('app_init','app\\common\\behavior\\InitConfigBehavior');
use think\Db;
use think\Request;
use think\Response;
use think\View;
use think\Lang;
/**
 * 返回对象
 * @param $array 响应数据
 */
function resultArray($array)
{
    if(isset($array['data'])) {
        $array['error'] = '';
        $code = 200;
    } elseif (isset($array['error'])) {
        $code = 400;
        $array['data'] = '';
    }else{
        $code = 200;
        $array['data'] = '';
    }


    return [
        'code'  => $code,
        'data'  => $array['data'],
        'error' => $array['error']
    ];
}

/**
 * 调试方法
 * @param  array   $data  [description]
 */
function p($data,$die=1)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if ($die) die;
}

/**
 * 用户密码加密方法
 * @param  string $str      加密的字符串
 * @param  [type] $auth_key 加密符
 * @return string           加密后长度为32的字符串
 */
function user_md5($str, $auth_key = '')
{
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}

/**
 * 数组 转 对象
 *
 * @param array $arr 数组
 * @return object
 */
function array_to_object($arr) {
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)array_to_object($v);
        }
    }

    return (object)$arr;
}
/**
 * 对象 转 数组
 *
 * @param object $object 对象
 * @return array
 */
function object2array($object) {
    if (is_object($object)) {
        foreach ($object as $key => $value) {
            $array[$key] = $value;
        }
    }
    else {
        $array = $object;
    }
    return $array;
}
/**
 * 所有用到密码的不可逆加密方式
 * @param string $password
 * @param string $password_salt
 * @return string
 */
function encrypt_password($password, $password_salt)
{
    return md5(md5($password) . md5($password_salt));
}
/**
 * 随机字符
 * @param int $length 长度
 * @param string $type 类型
 * @param int $convert 转换大小写 1大写 0小写
 * @return string
 */
function random($length=10, $type='letter', $convert=0){
    $config = array(
        'number'=>'1234567890',
        'letter'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string'=>'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if(!isset($config[$type])) $type = 'letter';
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) -1;
    for($i = 0; $i < $length; $i++){
        $code .= $string{mt_rand(0, $strlen)};
    }
    if(!empty($convert)){
        $code = ($convert > 0)? strtoupper($code) : strtolower($code);
    }
    return $code;
}

//php获取中文字符拼音首字母
function GetFirstCharter($str){
    if(empty($str)){
        return '';
    }
    $fchar=ord($str{0});
    if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
    $s1=iconv('UTF-8','gb2312',$str);
    $s2=iconv('gb2312','UTF-8',$s1);
    $s=$s2==$str?$s1:$str;
    if(empty($s{1})){
        return '';
    }
    $asc=ord($s{0})*256+ord($s{1})-65536;
    if($asc>=-20319 && $asc<=-20284) return 'A';
    if($asc>=-20283 && $asc<=-19776) return 'B';
    if($asc>=-19775 && $asc<=-19219) return 'C';
    if($asc>=-19218 && $asc<=-18711) return 'D';
    if($asc>=-18710 && $asc<=-18527) return 'E';
    if($asc>=-18526 && $asc<=-18240) return 'F';
    if($asc>=-18239 && $asc<=-17923) return 'G';
    if($asc>=-17922 && $asc<=-17418) return 'H';
    if($asc>=-17417 && $asc<=-16475) return 'J';
    if($asc>=-16474 && $asc<=-16213) return 'K';
    if($asc>=-16212 && $asc<=-15641) return 'L';
    if($asc>=-15640 && $asc<=-15166) return 'M';
    if($asc>=-15165 && $asc<=-14923) return 'N';
    if($asc>=-14922 && $asc<=-14915) return 'O';
    if($asc>=-14914 && $asc<=-14631) return 'P';
    if($asc>=-14630 && $asc<=-14150) return 'Q';
    if($asc>=-14149 && $asc<=-14091) return 'R';
    if($asc>=-14090 && $asc<=-13319) return 'S';
    if($asc>=-13318 && $asc<=-12839) return 'T';
    if($asc>=-12838 && $asc<=-12557) return 'W';
    if($asc>=-12556 && $asc<=-11848) return 'X';
    if($asc>=-11847 && $asc<=-11056) return 'Y';
    if($asc>=-11055 && $asc<=-10247) return 'Z';
    return null;
}


/*
 * 创建房间检查后创建分表
 * @param id
 */
function add_newtable($table_name){
    $sql = 'create table '.$table_name.'(
       `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `rules_id` int(11) DEFAULT NULL COMMENT "条款id",
      `answer` int(11) DEFAULT NULL COMMENT "条款选中项",
      `mark` varchar(255) DEFAULT NULL COMMENT "备注",
      `org_id` int(11) DEFAULT NULL COMMENT "实验室id",
      `task_id` int(11) DEFAULT NULL COMMENT "自查任务id",
       PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8;';
    $result = Db::execute($sql);
    return $result;
}

/*
 * 获取登录用户角色
 * @param $uid 用户学号 $orgid 选择登录的单位ID
 */
function get_groups($uid,$orgid){
    $result = db::name('admin_access')->where(array('username'=>$uid,'org_id'=>$orgid))->column('group_id');
    return $result;
}

/*
 * 导出成excel
 * @param $data 查表数据 $fields 表字段名 $filename 导出文件名
 */
function excel_run_export($data,$fields,$filename=''){

    error_reporting(E_ALL);
    date_default_timezone_set('Asia/chongqing');
    $obj = new \PHPExcel();
    //设置工作表名称
    $obj->setActiveSheetIndex ( 0 )->setTitle ($filename );

    // 总列数
    $max_cols = count ( $fields );

    //表头设置
    $i = 1;
    for($k = 1, $j = 'A'; $k <= $max_cols; $k ++, $j ++) {
        $cell_index = $j . $i;
        $field = $fields [$k - 1] [1];

        $obj->getActiveSheet ()->setCellValue ( "{$cell_index}", $field );

        if ($fields [$k - 1] [0] == 'tigan' || $fields [$k - 1] [0] == 'xuanxiang') {
            $obj->getActiveSheet ()->getColumnDimension ( "{$j}" )->setWidth ( 50 );
            $obj->getActiveSheet ()->getStyle ( "{$j}" )->getAlignment ()->setWrapText ( true );
        }
    }
    //开始导出试题
    $i = 2 ; //内容从第二行开始
    foreach ( $data as $key=>$d ) {

        $value = '';
        for($k = 1, $j = 'A'; $k <= $max_cols; $k ++, $j ++) {

            //当前字段名
            $field_name = $fields [$k - 1] [0];

            //当前所在单元格
            $cell_index = $j . $i;

            // 单远格填充的值
            if ($field_name == 'id') {
                $value = $key+1;
            } else {
                $value = $d [$field_name];
            }
            $obj->getActiveSheet ()->setCellValueExplicit ( "{$cell_index}", $value,
                \PHPExcel_Cell_DataType::TYPE_STRING );
        } // end for

        $i ++;
    } // end foreach
    ob_end_clean();

    header ( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
    header ( 'Content-Disposition: attachment;filename="'.$filename.'.xls"' );
    header ( 'Cache-Control: max-age=0' );


    $objWriter = \PHPExcel_IOFactory::createWriter ( $obj, 'Excel5' );
    $objWriter->save ( 'php://output' );
    exit ();

}

/**
 * 实验室所有资质
 */
function  lab_aptitude(){
    $qualified_group = array();
    $qualified=db::name('system_parameter')->where('syskey','=',"qualified")->order('sort_order')->column('id');
    if(isset($qualified[0]) && $qualified[0]){
        $qualified_group=db::name('system_parameter')->where('type','=',$qualified[0])->order('sort_order')->select();
    }
    $hazard_source_group = array();
    $hazard_source=db::name('system_parameter')->where('syskey','=',"hazard_source")->order('sort_order')->column('id');
    if(isset($hazard_source[0]) && $hazard_source[0]){
        $hazard_source_group=db::name('system_parameter')->where('type','=',$hazard_source[0])->order('sort_order')->select();
    }
    $precautions_group = array();
    $precautions=db::name('system_parameter')->where('syskey','=',"precautions")->order('sort_order')->column('id');
    if(isset($precautions[0]) && $precautions[0]){
        $precautions_group=db::name('system_parameter')->where('type','=',$precautions[0])->order('sort_order')->select();
    }
    $main_outfire_group = array();
    $main_outfire=db::name('system_parameter')->where('syskey','=',"main_outfire")->order('sort_order')->column('id');
    if(isset($main_outfire[0]) && $main_outfire[0]){
        $main_outfire_group=db::name('system_parameter')->where('type','=',$main_outfire[0])->order('sort_order')->select();
    }
    $lab_aptitude['qualified_group']=$qualified_group;
    $lab_aptitude['hazard_source_group']=$hazard_source_group;
    $lab_aptitude['precautions_group']=$precautions_group;
    $lab_aptitude['main_outfire_group']=$main_outfire_group;
    return $lab_aptitude;
}
