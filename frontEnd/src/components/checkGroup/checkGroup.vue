<template>
    <div class="checkGroup">
        <!-- 头部 -->
        <VueHead></VueHead>
        <div class="main-container" id="main-container">
            <!-- 左侧菜单 -->
            <VueLeft show=""></VueLeft>
            <!-- 右侧内容 -->
            <div class="main-content">
                <div class="main-content-inner">
                    <!-- 面包屑 -->
                    <div class="breadcrumbs" id="breadcrumbs">
                        <ul class="breadcrumb">
                            <li>
                                <i class="ace-icon fa fa-home home-icon"></i>
                                <router-link :to="pathName+'/'">首页</router-link>
                            </li>
                            <li>
                                <router-link :to="pathName+'/checkGroup'" class="active">{{title}}</router-link>
                            </li>
                        </ul>
                    </div>
                    <!-- 右侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{title}}
                                <div class="pull-right">
                                    <button class="btn btn-primary btn-sm" @click="addGroup">添加</button>
                                </div>
                            </h1>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="center" width="60px">小组ID</th>
                                        <th>小组名称</th>
                                        <th class="center">单位级别</th>
                                        <th class="center hidden-640">组长名称</th>
                                        <th class="center little hidden-640">组长电话</th>
                                        <th class="center hidden-640">所属单位</th>
                                        <th class="center little">排序</th>
                                        <th class="center little">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(group,index) in checkGroupArray" :key="'checkGroupArray'+index" v-if="group.org_id == loginUser.org_id">
                                        <td class="center">{{group.group_id}}</td>
                                        <td>{{group.group_name}}</td>
                                        <td class="center">
                                            <span v-if="group.level === 'school'">学校抽查小组</span>
                                            <span v-if="group.level === 'college'">院系复查小组</span>
                                            <span v-if="group.level === 'lab'">实验室自查小组</span>
                                        </td>
                                        <td class="center hidden-640">{{group.leader_name}} ({{group.leader_id}})</td>
                                        <td class="center hidden-640">{{group.phone}}</td>
                                        <td class="center hidden-640">
                                            <span v-if="group.org_id == 0">全部</span>
                                            <span v-for="org in orgList" :key="'org'+org.org_id" v-if="group.org_id == org.org_id">{{org.org_name}}</span>
                                        </td>
                                        <td class="center little">{{group.group_order}}</td>
                                        <td class="center">
                                            <button class="btn btn-success btn-xs" data-target="#groupModal" data-toggle="modal" @click="changeGroup(group)">
                                                <i class="ace-icon glyphicon glyphicon-edit bigger-100"></i>
                                            </button>
                                            <button class="btn btn-danger btn-xs" @click="delGroup(group)">
                                                <i class="ace-icon fa fa-trash-o bigger-110"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="checkGroupArray.length == 0">
                                        <td colspan="8" class="center">暂无数据</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <GroupModal
            :getCheckGroupList="getCheckGroupList"
            :changeObj = "changeObj"
            :orgList = "orgList"
        ></GroupModal>
        <div class="modal fade" id="userModal" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document" aria-hidden="true">
                <div class="modal-content">
                    <div class="modal-body">
                        <UserList 
                            :sure="selectUser"
                        ></UserList>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import VueHead from "../common/header";
    import VueLeft from "../common/leftMenu";
    import GroupModal from './groupModal';
    import UserList from '../user/userList';

    export default {
        name:"checkGroup",
        data(){
            return {
                title:"检查小组管理",
                changeObj:{},
                checkGroupArray:[]
            }
        },
        components:{VueHead,VueLeft,GroupModal,UserList},
        computed:{
            orgList(){
                return this.$store.state.orgList;
            }
        },
        methods:{
            delGroup(group){
                if(confirm("是否要删除<"+group.group_name+">检查小组，此操作不可逆，请慎重！")){
                    const _this = this;
                    const URL = this.serverUrl + "/admin/group/del";
                    this.emitAjax(URL,{group_id:group.group_id},function(result){
                        _this.getCheckGroupList();
                    })
                }
            },
            addGroup(){
                //添加新小组
                $("#groupModal").modal("show");
                this.changeGroup({
                    org_id:0,
                    group_name: "",
                    level: "lab",
                    leader_name: "",
                    leader_id: "",
                    phone: "",
                    members: "",
                    group_order:this.checkGroupArray.length+1
                });
            },
            changeGroup(obj){
                //编辑小组
                this.changeObj = Object.assign({},obj);
            },
            getCheckGroupList(){
                //获取检查小组列表
                const _this = this;
                const URL = this.serverUrl + "/admin/group/index";
                this.emitAjax(URL,null,function(result){
                    _this.checkGroupArray = result;
                })
            },
            selectUser(user){
                //选择用户作为组长
                this.changeObj.leader_name = user.name;
                this.changeObj.leader_id = user.username;
                this.changeObj.phone = user.mobile;
                $("#userModal").modal("hide");
            },
            getOrgList(data){
                //获取单位列表
                this.$store.dispatch("getOrgList",data);
            }
        },
        mounted(){
            if(this.checkPermission(this)){
                this.getCheckGroupList();
                this.getOrgList();
            }
        }
    };
</script>