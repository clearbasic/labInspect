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
            if ($GLOBALS['userInfo']['org_id'] != '1'){
                $childIds = model('org')->getAllChild($GLOBALS['userInfo']['org_id']);
                $childIds[]=$GLOBALS['userInfo']['org_id'];

                if (!empty($childIds)){
                    $map['person.org_id'] = ['in', $childIds];
                }
            }
        }

        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        if ($keywords) {
            $map['person.username|person.name'] = ['like', '%'.$keywords.'%'];
        }
        $list = $this->alias('person')->where($map);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list
            ->join('dc_org org', 'person.org_id=org.org_id', 'LEFT')
            ->field('person.*,org.org_name as org_name')
            ->order('org.org_level,id')
            ->select();
        foreach ($list as $k => $v){
            unset($v['password']);
            unset($v['password_salt']);
        }

        $data = $list;
        return $data;
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

        $param['firstchar']=GetFirstCharter($param['name']);
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
        $flag = !empty($param['flag'])? $param['flag']: '';
        $group_id = !empty($param['group_id'])? $param['group_id']: '';
        $org_id = !empty($param['org_id'])? $param['org_id']: '';

        if ($flag != 'true' && !$group_id ){
            if (!$username) {
                $this->error = '帐号不能为空';
                return false;
            }
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

            $map['username'] = $username;

            $userInfo = $this->where($map)->find();

            if (!$userInfo) {
                $this->error = '帐号不存在';
                return false;
            }

            if (encrypt_password($password,$userInfo['password_salt']) !== $userInfo['password']) {
                $this->error = '密码错误';
                return false;
            }
            if ($userInfo['person_state'] === 'no') {
                $this->error = '帐号已被禁用';
                return false;
            }
            // 获取菜单和权限
            $groups = db::name('admin_access')->where('access.username',$userInfo['username'])
                ->alias('access')
                ->join('dc_person person', 'access.username=person.username', 'LEFT')
                ->join('dc_org org', 'access.org_id=org.org_id', 'LEFT')
                ->join('admin_group group', 'access.group_id=group.id', 'LEFT')
                ->field('access.*,person.name as p_name, org.org_name as o_name,group.title as g_name')
                ->order('group.group_level,org.org_level')
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

        if ($isRemember || $type) {
            $secret['username'] = $username;
            $secret['password'] = $password;
            $data['rememberKey'] = encrypt($secret);
        }
        $userInfo['group_level'] = db::name('admin_group')->where('id',$group_id)->value('group_level');
        $userInfo['group_level'] = md5($userInfo['group_level'].'_level');
        $userInfo['org_id'] = $org_id;
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
     * 获取菜单和权限
     * @param  array   $param  [description]
     */
    protected function getMenuAndRule($group_id)
    {
        if ($group_id == '1') {
            $map['status'] = 1;
            $menusList = Db::name('admin_menu')->where($map)->order('sort asc')->select();

        } else {
//            $group_id = db::name('admin_access')->where(array('username'=>$u_id,'org_id'=>$org_id))->value('group_id');

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
}