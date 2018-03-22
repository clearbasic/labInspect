<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\Common;
use com\verify\HonrayVerify;
use think\db;
class Person extends Common
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     */
    protected $name = 'dc_person';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'dt_create';
    protected $updateTime = false;
    protected $insert = [
        'creator' => 'chingo',
    ];
    protected $hidden = [
        'password','password_salt',
    ];

    public function getGroupsAttr($value, $data){
        $groups = Db::name('admin_access')
            ->where('username',$data['username'])
            ->column('group_id');
        return $groups;
    }

    /**
     * [getDataList 获取列表]
     * @return    [array]
     */
    public function getDataList($param, $page, $limit)
    {
        $map = [];
        //获取登录用户的子单位ID组
        $flag = !empty($param['flag']) ? $param['flag']: '0';
        if ($flag !== 'all'){
            $childIds = getChildOrgIds($GLOBALS['userInfo']['org_id']);
            if (!empty($childIds))$map['person.org_id'] = ['in', $childIds];
        }

        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        if ($keywords) {
            $map['person.username|person.name'] = ['like', '%'.$keywords.'%'];
        }
        $list = $this->alias('person')->where($map);


        $count = db::name('dc_person')
            ->alias('person')
            ->where($map)
            ->join('dc_org org', 'person.org_id=org.org_id', 'LEFT')
            ->field('`person`.username,`person`.name,`person`.mobile,`person`.email,`person`.org_id,
            `person`.person_state, org.org_name as org_name')
            ->order('person.org_id')
            ->count();
        $limits = $limit ? $limit : config('paginate')['list_rows'];
        $pages = ceil($count/$limits);

        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }else{
            $list = $list->page(1, config('paginate')['list_rows']);
        }

        $list = $list
            ->join('dc_org org', 'person.org_id=org.org_id', 'LEFT')
            ->field('`person`.username,`person`.name,`person`.mobile,`person`.email,`person`.org_id,
            `person`.person_state, org.org_name AS org_name')
            ->order('person.org_id')
            ->select();

       foreach ($list as $k => $v){
            $data[] = $v->append(['Groups'])->toarray();
        }
        $result['pages'] = $pages;
        $result['person_list'] = $data;
        return $result;
    }

    public function getuser($username)
    {
        if (!$username) {
            $this->error = '用户名不能为空';
            return false;
        }
        $checkData = $this->field(['password','password_salt'],true)->where('username',$username)->find();
        if (!$checkData) {
            $this->error = '暂无此数据';
            return false;
        }
        return $checkData;
    }

    /**
     * 通过id修改指标体系
     * @param  array   $param  [description]
     */
    public function addData($param)
    {
        // 验证
        $validate = validate('Person');
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }

//        $param['firstchar']=GetFirstCharter($param['name']);
        $param['password_salt']=random(10);
        $param['password']=encrypt_password($param['password'],$param['password_salt']);

        $this->startTrans();
        try {
            $this->data($param)->allowField(true)->save();
            $this->commit();
            return '添加成功';
        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '添加失败';
            return false;
        }

    }
    /**
     * 通过id修改用户信息
     * @param  array   $param  [description]
     */
    public function updateDataById($param, $id)
    {
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = '暂无此数据';
            return false;
        }
        $password = !empty($param['password']) ? $param['password']: '';
        if (!$password) {
            unset($param['password']);
        }else{
            $param['password_salt']=random(10);
            $param['password']=encrypt_password($param['password'],$param['password_salt']);
        }

        $this->startTrans();
        try {
            $this->allowField(true)->save($param, ['id' => $id]);
            $this->commit();
            return true;

        } catch(\Exception $e) {
            $this->rollback();
            $this->error = '编辑失败';
            return false;
        }
    }

    /**
     * [login 登录]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:37:49+0800
     * @param     [string]                   $u_username [账号]
     * @param     [string]                   $u_pwd      [密码]
     * @param     [string]                   $verifyCode [验证码]
     * @param     Boolean                  	 $isRemember [是否记住密码]
     * @param     Boolean                    $type       [是否重复登录]
     * @return    [type]                               [description]
     */
    public function login($param,$username, $password, $verifyCode = '', $isRemember = false, $type = false)
    {
        //是否多角色判断
        $flag = !empty($param['flag'])? $param['flag']: '';
        //统一认证登录
        $tyrz = !empty($param['tyrz'])? $param['tyrz']: '';
        $time = !empty($param['time'])? $param['tyrz']: '';
        $tyrz_st = md5($username.'_'.$param['time'].'_chingo');
        $tyrz_flag = true;
        if (!$tyrz || $tyrz != $tyrz_st) $tyrz_flag = false;

        //角色ID
        $group_id = !empty($param['group_id'])? $param['group_id']: '';
        //单位ID
        $org_id = !empty($param['org_id'])? $param['org_id']: '';

        if ( !$flag ){
            if (!$username) {
                $this->error = '帐号不能为空';
                return false;
            }

            if (!$tyrz && $tyrz == $tyrz_st){
                if (!$password){
                    $this->error = '密码不能为空';
                    return false;
                }

                if (1 && !$type) {
                    if (!$verifyCode) {
                        $this->error = '验证码不能为空';
                        return false;
                    }
                    $captcha = new HonrayVerify(config('captcha'));
                    if (!$captcha->check($verifyCode)) {
                        $this->error = '验证码错误';
                        return false;
                    }
                }
            }


            $map['username'] = $username;

            $userInfo = $this->where($map)->find();

            if (!$userInfo) {
                $this->error = '帐号不存在';
                return false;
            }

            if (!$tyrz){
                if (encrypt_password($password,$userInfo['password_salt']) !== $userInfo['password']) {
                    $this->error = '密码错误';
                    return false;
                }
            }

            if ($userInfo['person_state'] === 'no') {
                $this->error = '帐号已被禁用';
                return false;
            }
            // 获取菜单和权限
//            $groups = db::name('admin_access')->where('access.username',$userInfo['username'])
//                ->alias('access')
//                ->join('dc_person person', 'access.username=person.username', 'LEFT')
//                ->join('dc_org org', 'access.org_id=org.org_id', 'LEFT')
//                ->join('admin_group group', 'access.group_id=group.id', 'LEFT')
//                ->field('access.*,person.name as p_name, org.org_name as o_name,group.title as g_name')
//                ->order('group.group_level,org.org_level')
//                ->select();

            $groups = Db::view('admin_access','*')
                ->view('dc_person',['name'=>'p_name'],'admin_access.username=dc_person.username','LEFT')
                ->view('dc_org',['org_name'=>'o_name'],'admin_access.org_id=dc_org.org_id','LEFT')
                ->view('admin_group',['title'=>'g_name'],'admin_access.group_id=admin_group.id','LEFT')
                ->where('admin_access.username',$userInfo['username'])
                ->select();

            if ( count($groups) > 1){
                return $groups;
            }
            if (empty($groups)){
                $this->error = '没有分配角色';
                return false;
            }
            $group_id = $groups['0']['group_id'];
            $org_id = $groups['0']['org_id'];
            $dataList = $this->getMenuAndRule($group_id);
        }else{
            if (!$username) {
                $this->error = '帐号不能为空';
                return false;
            }
            if (!$group_id) {
                $this->error = '角色不能为空';
                return false;
            }
            if (!$org_id) {
                $this->error = '单位不能为空';
                return false;
            }

            $map['username'] = $username;
            $userInfo = $this->where($map)->find();
            $dataList = $this->getMenuAndRule($group_id);


        }
        if (!$dataList['menusList']) {
            $this->error = '没有权限';
            return false;
        }


        $arr['login_ip'] = getClientIP();
        $arr['last_login_date'] = time();
        $arr['login_count'] = $userInfo['login_count']+1;


        $this->allowField(['login_count','login_ip','last_login_date'])->save($arr, ['id' => $userInfo['id']]);



        if ($isRemember || $type) {
            $secret['username'] = $username;
            $secret['password'] = $password;
            $data['rememberKey'] = encrypt($secret);
        }
        $userInfo['group_level'] = db::name('admin_group')->where('id',$group_id)->value('group_level');
        $userInfo['group_level'] = md5($userInfo['group_level'].'_level');
        $userInfo['org_id'] = $org_id;

        $userInfo = $userInfo->toArray();

        // 保存缓存
        session_start();
        $info['userInfo'] = $userInfo;
        $info['group_id'] = $group_id;
        $info['sessionId'] = session_id();
        $authKey = user_md5($userInfo['username'].$userInfo['password'].$info['sessionId']);
        $info['authKey'] = $authKey;

        cache('Auth_'.$authKey, null);
        cache('Auth_'.$authKey, $info, 3600);

        // 返回信息
        $data['authKey']		= $authKey;
        $data['sessionId']		= $info['sessionId'];
        $data['userInfo']		= $userInfo;
        $data['authList']		= $dataList['rulesList'];
        $data['menusList']		= $dataList['menusList'];

        return $data;
    }


    /**
     * [login 统一认证登录]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:37:49+0800
     * @param     [string]                   $u_username [账号]
     * @param     [string]                   $u_pwd      [密码]
     * @param     [string]                   $verifyCode [验证码]
     * @param     Boolean                    $isRemember [是否记住密码]
     * @param     Boolean                    $type       [是否重复登录]
     * @return    [type]                               [description]
     */
    public function login_tyrz($param)
    {
        //CAS Server的登陆URL
        $loginServer = "http://cas.xzhmu.edu.cn/cas/login";
        //CAS Server的验证URL
        $validateServer = "http://cas.xzhmu.edu.cn/cas/serviceValidate";

        //当前集成系统所在的服务器和端口号，服务器可以是机器名、域名或ip，建议使用域名。端口不指定的话默认是80
        //以及新增加的集成登录入口
        $this_url = "http://192.168.240.81:8081/lab-inspect/php/index.php/admin/login/login_tyrz";

        //判断是否有验证成功后需要跳转页面，如果有，增加跳转参数
        if(isset($param["redirectUrl"]) && !empty($param["redirectUrl"])) {
            $this_url = $this_url."?redirectUrl=".$param["redirectUrl"];
        }

        //判断是否已经登录
        if(isset($param["ticket"]) && !empty($param["ticket"])) {
            //获取登录后的返回信息
            try {
                $validateurl = $validateServer . "?ticket=" . $_REQUEST["ticket"] . "&service=" . $this_url;
                header("Content-Type:text/html;charset=utf-8");
                $validateResult = file_get_contents($validateurl);
                $validateResult = iconv("gb2312", "utf-8//IGNORE", $validateResult);

                //节点替换，去除sso:，否则解析的时候有问题
                $validateResult = preg_replace("/sso:/", "", $validateResult);

                $validateXML = simplexml_load_string($validateResult);

                //获取验证成功节点
                $successnode = $validateXML->authenticationSuccess[0];

                if(!empty($successnode)){
                    $username = $successnode->user;

                    return $username;

                }else{

                    //重定向浏览器
                    header("Location: ".$loginServer."?service=".$this_url);

                    //确保重定向后，后续代码不会被执行
                    exit;
                }
            }catch (\Exception $e){
                echo "出错了";
                echo $e-> getMessage();
            }
        }else{
            //重定向浏览器
            header("Location: ".$loginServer."?service=".$this_url);
            //确保重定向后，后续代码不会被执行
            exit;
        }
    }





    /**
     * 获取菜单和权限
     * @param  array   $param  [description]
     */
    protected function getMenuAndRule($group_id)
    {
        if ($group_id == '1') {
            $map['status'] = 1;
            $menusList = Db::name('admin_menu')->where($map)->order('sort asc')->select();

        } else {
            $groups = db::name('admin_group')->where('id',$group_id)->select();
            $ruleIds = [];
            foreach($groups as $k => $v) {
                $ruleIds = array_unique(array_merge($ruleIds, explode(',', $v['rules'])));
            }

            $ruleMap['id'] = array('in', $ruleIds);
            $ruleMap['status'] = 1;
            // 重新设置ruleIds，除去部分已删除或禁用的权限。
            $rules =Db::name('admin_rule')->where($ruleMap)->select();
            foreach ($rules as $k => $v) {
                $ruleIds[] = $v['id'];
                $rules[$k]['name'] = $v['name'];
            }
            empty($ruleIds)&&$ruleIds = '';
            $menuMap['status'] = 1;
            $menuMap['rule_id'] = array('in',$ruleIds);
            $menusList = Db::name('admin_menu')->where($menuMap)->order('sort asc')->select();
        }
        if (!$menusList) {
            return null;
        }
        //处理菜单成树状
        $tree = new \com\Tree();
        $ret['menusList'] = $tree->list_to_tree($menusList, 'id', 'pid', 'child', 0, true, array('pid'));
        $ret['menusList'] = memuLevelClear($ret['menusList']);

        // 处理规则成树状
        $ret['rulesList'] = $tree->list_to_tree($rules, 'id', 'pid', 'child', 0, true, array('pid'));
        $ret['rulesList'] = rulesDeal($ret['rulesList']);

        return $ret;
    }

    /**
     * 导入xls的信息
     * @param  array   $param  [description]
     */
    public function importData($param)
    {
        $SaveName = !empty($param['SaveName']) ? $param['SaveName'] : '';
        if (!$SaveName){
            $this->error = '文件不存在，读取失败';
            return false;
        }
        $res = read ( 'uploads'. DS .$SaveName );
        $res = array_splice($res,1);
        foreach ($res as $k => $v){
            if (empty($v['0']))unset($res[$k]);
        }
        //学院 实验室 房间分组  房间名 所在楼  责任人ID  责任人姓名
        $titles=array('username','name','mobile','email','org_name');

        $add_count = 0;
        $jump_count = 0;
        $this->startTrans();
        try {
            $insert = array();

            foreach ( $res as $k => $v ) {

                $data = array();
                foreach ($titles as $ColumnIndex => $title) {
                    $data[$title] = $v[$ColumnIndex];
                }
                if(empty($data['username'])){
                    continue;
                }

                if ($data['org_name']){
                    $org_id = model('org')->where('org_name',$data['org_name'])->value('org_id');
                    if (!$org_id)$data['org_id']= 0;
                    else $data['org_id']=$org_id;
                }

                $data['password'] = config('default_password');
                $data['password_salt']=random(10);
                $data['password']=encrypt_password($data['password'],$data['password_salt']);

                $person_id = $this->where('username',$data['username'])->value('id');

                if (!$person_id){
                    $insert[$k] = $data;
                    $add_count++;
                }else{
                    $jump_count++;
                }
            }
            $end =  $this->allowField(true)->saveAll($insert);
            $result['add'] = count($end);
            $result['jump'] = $jump_count;
            $this->commit();
            return $result;
        } catch(\Exception $e) {
            $this->error = '一次导入数据量过大，请分批导入。';
            $this->rollback();
            return false;
        }
    }

}