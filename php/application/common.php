<?php

/**
 * 行为绑定
 */
\think\Hook::add('app_init','app\\common\\behavior\\InitConfigBehavior');

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


function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){

    if(is_array($arrays)){
        foreach ($arrays as $array){
            if(is_array($array)){
                $key_arrays[] = $array[$sort_key];
            } else{
                return false;
            }

        }
    }else{
        return false;
    }
    array_multisort($key_arrays,$sort_order,$sort_type,$arrays);

    return $arrays;
}