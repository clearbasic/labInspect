<template>
    <!-- 右侧内容 -->
    <div class="main-content assignRole">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/assignRole'" class="active">{{title}}</router-link>
                    </li>
                </ul>
            </div>
            <!-- 右侧主要内容 -->
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{title}}
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm" @click="setShowType('add')">分配</button>
                        </div>
                    </h1>
                </div>
                <transition name="fade">
                    <div class="user_list" v-if="showType=='list'">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>用户Id</th>
                                    <th>用户姓名</th>
                                    <th>绑定角色</th>
                                    <th>绑定单位</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(assign,index) in assign_user_list" :key="'assign'+index">
                                    <td>{{assign.username}}</td>
                                    <td>{{assign.p_name}}</td>
                                    <td>{{assign.g_name}}</td>
                                    <td>{{assign.o_name}}</td>
                                    <td class="center little">
                                        <button class="btn btn-xs btn-danger" @click="delAssign(assign)" title="删除">
                                            <i class="ace-icon fa fa-trash-o"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="assign_user_list.length ==0">
                                    <td colspan="5" class="center">暂无人员分配</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </transition>
                <transition name="fade">
                    <div class="addUser" v-if="showType=='add'">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">选择角色</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" v-model="newAssign.group_id" @change="selectGroupLevel">
                                                <option :value="role.id" v-for="role in role_list" :key="'role'+role.id">{{role.title}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="newAssign.group_id">
                                        <label class="col-sm-2 control-label">所属单位</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" v-model="newAssign.org_id">
                                                <option :value="org.org_id" v-for="org in org_list" :key="'org'+org.id">{{org.org_name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">人员选择</label>
                                        <div class="col-sm-10">
                                            <input type="text" v-model="newAssign.username" readonly class="form-control" data-toggle="modal" data-target="#userModal" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-2">
                                            <button class="btn btn-sm btn-success" @click="saveAssign">绑定</button>
                                            <button class="btn btn-sm btn-default" @click="setShowType('list')">返回</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg" role="document">  
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">用户管理</h4>
                                    </div>
                                    <div class="modal-body">
                                        <UserModal 
                                            :sure="selectUser"
                                        ></UserModal>
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
import UserModal from "../../user/userModal";
export default {
    name: "assignRole",
    data() {
        return {
            title: "绑定角色",
            org_list: [],
            newAssign: {
                username:"",
                org_id:0,
            },
            role_list: [],
            showType: "list",
            assign_user_list: []
        };
    },
    components: { selectItem, UserModal },
    computed: {
        orgList() {
            return this.$store.state.orgList;
        }
    },
    methods: {
        init() {
            this.getAssignRoleList();
            this.getOrgList();
            this.getRoleList();
        },
        getAssignRoleList() {
            //获取绑定列表
            const URL = this.serverUrl + "/admin/roles/index";
            const _this = this;
            this.emitAjax(URL, null, function(result) {
                _this.assign_user_list = result;
            });
        },
        getRoleList() {
            //获取角色列表
            const URL = this.serverUrl + "/admin/groups/index";
            const _this = this;
            this.emitAjax(URL, null, function(result) {
                _this.role_list = result;
            });
        },
        getOrgList() {
            //获取单位列表
            if (this.$store.state.orgList.length == 0) {
                this.$store.dispatch("getOrgList");
            } else {
                this.setOrgList();
            }
        },
        setShowType(type) {
            //切换显示
            this.showType = type;
            this.newAssign = {
                username:"",
                org_id:0,
            }
        },
        selectUser(user) {
            //选择绑定的人员
            this.newAssign.username = user.username;
            $("#userModal").modal("hide");
        },
        selectGroupLevel() {
            //根据角色过滤单位
            this.newAssign.group_level = 0;
            for (let index = 0; index < this.role_list.length; index++) {
                const role = this.role_list[index];
                if (role.id == this.newAssign.group_id) {
                    this.newAssign.group_level = role.group_level;
                    break;
                }
            }
            this.setOrgList();
        },
        setOrgList() {
            //过滤单位
            this.org_list = [];
            for (let index = 0; index < this.orgList.length; index++) {
                const org = this.orgList[index];
                if (org.org_level == this.newAssign.group_level) {
                    this.org_list.push(Object.assign({}, org));
                }
            }
        },
        saveAssign(){
            //新建
            const URL = this.serverUrl + "/admin/roles/add";
            const _this = this;
            const data = {
                username:this.newAssign.username,
                group_id:this.newAssign.group_id,
                org_id:this.newAssign.org_id,
            }
            this.emitAjax(URL, data, function(result) {
                _this.getAssignRoleList();
                _this.setShowType('list');
            });
        },
        delAssign(assign){
            //删除
            if(confirm("是否要删除这条绑定，此操作不可逆，请慎重")){
                const URL = this.serverUrl + "/admin/roles/del";
                const _this = this;
                const data = {
                    username:assign.username,
                    group_id:assign.group_id,
                    org_id:assign.org_id,
                }
                this.emitAjax(URL, data, function(result) {
                    _this.getAssignRoleList();
                });
            }
        }
    },
    mounted() {
        this.init();
    }
};
</script>
