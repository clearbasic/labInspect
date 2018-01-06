<template>
    <!-- 右侧内容 -->
    <div class="main-content role" @click="colseSelect">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/role'" class="active">{{title}}</router-link>
                    </li>
                </ul>
            </div>
            <!-- 右侧主要内容 -->
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{title}}
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm" @click="setShowType('add')">添加</button>
                        </div>
                    </h1>
                </div>
                <!-- 角色列表 -->
                <transition name="fade">
                    <table class="table table-bordered table-hover" v-if="showType=='list'">
                        <thead>
                            <tr>
                                <th class="center little">角色ID</th>
                                <th>角色名称</th>
                                <th>父级名称</th>
                                <th>备注</th>
                                <th class="center little">状态</th>
                                <th class="center little">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="role in role_list" :key="'role'+role.id">
                                <td class="center little">{{role.id}}</td>
                                <td>{{role.title}}</td>
                                <td>
                                    <span v-if="role.pid == 0">无</span>
                                    <span v-for="pRole in role_list" :key="'pRole'+pRole.id" v-if="pRole.id == role.pid">{{pRole.title}}</span>
                                </td>
                                <td>{{role.remark}}</td>
                                <td class="center little">
                                    <span v-if="role.status == 1">开启</span>
                                    <span v-if="role.status == 0">禁用</span>
                                </td>
                                <td class="center little">
                                    <div class="hidden-xs btn-group" v-if="role.id != 1">
                                        <button class="btn btn-xs btn-success" @click="editRole(role)" title="编辑">
                                            <i class="ace-icon glyphicon glyphicon-edit"></i>
                                        </button>
                                        <button class="btn btn-xs btn-danger" @click="delRole(role)" title="删除">
                                            <i class="ace-icon fa fa-trash-o"></i>
                                        </button>
                                    </div>
                                    <div class="hidden-sm hidden-md hidden-lg" v-if="role.id != 1">
                                        <div class="inline pos-rel">
                                            <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto" aria-expanded="false">
                                                <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                                <li>
                                                    <a class="tooltip-info blue" @click="editRole(role)">
                                                        <i class="ace-icon glyphicon glyphicon-edit bigger-100"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="tooltip-info red" @click="delRole(role)">
                                                        <i class="ace-icon fa fa-trash-o bigger-100"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <span v-if="role.id == 1">不可操作</span>
                                </td>
                            </tr>
                            <tr v-if="role_list.length == 0">
                                <td colspan="6" class="center">
                                    暂无角色
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </transition>
                <!-- 新添角色 -->
                <transition name="fade">
                    <div class="addRole" v-if="showType=='add'">
                        <div class="row">
                            <div class="form-horizontal col-lg-8">
                                <div class="form-group">
                                    <label for="title" class="col-sm-2 control-label">角色名称</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="newRole.title" id="title" placeholder="角色名称">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">父角色</label>
                                    <div class="col-sm-10">
                                        <div class="position-relative has-feedback">
                                            <input type="text" class="form-control"  v-model="newRole.pid" @click.stop="select=true">
                                            <span class="glyphicon glyphicon-chevron-down form-control-feedback"></span>
                                            <div class="widget-main padding-8 widget-box widget-color-blue2 selectMenu"  v-if="select">
                                                <ul class="tree tree-selectable">
                                                    <li class="tree-item" @click="selectPidId(0)">
                                                        <div class="tree-branch-name">无</div>
                                                    </li>
                                                    <selectItem :data = "role" 
                                                        v-for="(role,index) in role_tree" 
                                                        :key="'role'+index" 
                                                        :parentFn = "selectPidId"
                                                        :currentLevel = "1"
                                                        v-if="newRole.group_level == 'school'"
                                                    ></selectItem>
                                                    <selectItem :data = "role" 
                                                        v-for="(role,index) in role_tree" 
                                                        :key="'role'+index" 
                                                        :parentFn = "selectPidId"
                                                        :currentLevel = "2"
                                                        v-if="newRole.group_level == 'college'"
                                                    ></selectItem>
                                                    <selectItem :data = "role" 
                                                        v-for="(role,index) in role_tree" 
                                                        :key="'role'+index" 
                                                        :parentFn = "selectPidId"
                                                        :currentLevel = "3"
                                                        v-if="newRole.group_level == 'lab'"
                                                    ></selectItem>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sort" class="col-sm-2 control-label">角色级别</label>
                                    <div class="col-sm-10">
                                        <label  class="control-label">
                                            <input type="radio" v-model="newRole.group_level" value="lab" class="ace">
                                            <span class="lbl">
                                                实验室级别
                                            </span>
                                        </label>
                                        <label class="control-label">
                                            <input type="radio" v-model="newRole.group_level" value="college" class="ace">
                                            <span class="lbl">
                                                院系级别
                                            </span>
                                        </label>
                                        <label class="control-label">
                                            <input type="radio" v-model="newRole.group_level" value="school" class="ace">
                                            <span class="lbl">
                                                学校级别
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sort" class="col-sm-2 control-label">状态</label>
                                    <div class="col-sm-10">
                                        <label  class="control-label">
                                            <input type="radio" v-model="newRole.status" value="1" class="ace">
                                            <span class="lbl">
                                                开启
                                            </span>
                                        </label>
                                        <label class="control-label">
                                            <input type="radio" v-model="newRole.status" value="0" class="ace">
                                            <span class="lbl">
                                                禁用
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="url" class="col-sm-2 control-label">备注</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" v-model="newRole.remark"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <selectRule :returnRules="getRules" :rules="this.newRole.rules" :pid="newRole.pid"></selectRule>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button class="btn btn-success btn-sm" @click="addRole">保存</button>
                                        <button class="btn btn-default btn-sm" @click="setShowList">取消</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
import selectItem from "../selectItem.vue";
import selectRule from "./selectRule.vue";

export default {
    name: "role",
    components: {
        selectItem,
        selectRule
    },
    data() {
        return {
            title: "角色设置",
            newRole: {
                status: 1,
                group_level: "lab",
                pid: 0
            },
            showType: 'list',
            select: false,
            role_list: [],
            role_tree: [],
        };
    },
    methods: {
        init() {
            this.getRoleList();
            this.getRoleTree();
        },
        getRoleList() {
            //获取角色列表
            const URL = this.serverUrl + "/admin/groups/index";
            const _this = this;
            this.emitAjax(URL, null, function(result) {
                _this.role_list = result;
            });
        },
        getRoleTree() {
            //获取角色树状列表
            const URL = this.serverUrl + "/admin/groups/index";
            const _this = this;
            this.emitAjax(URL, { type: "tree" }, function(result) {
                _this.role_tree = result;
            });
        },
        addRole() {
            //添加角色
            let URL = this.serverUrl + "/admin/groups/add";
            if (this.newRole.id) {
                URL = this.serverUrl + "/admin/groups/edit";
            }
            const _this = this;
            this.emitAjax(URL, this.newRole, function(result) {
                _this.setShowList();
                _this.init();
            });
        },
        editRole(role) {
            this.newRole = Object.assign({}, role);
            this.setShowType('add');
        },
        delRole(role){
            if(confirm("是否要删除<"+role.title+">这个角色，此操作不可逆，请慎重！")){
                const URL = this.serverUrl + "/admin/groups/del";
                const _this = this;
                this.emitAjax(URL, {id:role.id}, function(result) {
                    _this.init();
                });
            }
        },
        getRules(rules) {
            //获取权限列表
            this.newRole.rules = rules;
        },
        selectPidId(id) {
            //选择父级权限
            if(this.newRole.id == id){
                alert("不能选择自身");
                return false;
            }
            this.newRole.pid = id;
        },
        setShowType(type) {
            //页面显示切换
            this.showType = type;
        },
        setShowList() {
            //显示角色列表
            this.setShowType('list');
            this.newRole = {
                status: 1,
                group_level: "lab",
                pid: 0
            };
        },
        colseSelect() {
            //关闭下拉菜单
            this.select = false;
        }
    },
    mounted() {
        this.init();
    }
};
</script>

