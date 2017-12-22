<template>
    <div class="userList dataTables_wrapper">
        <div class="row" v-if="showUserTable">
            <div class="col-xs-12 form-inline">
                <div class="form-group">
                    <button class="btn btn-primary btn-sm" @click="showUserTable = false" v-if="showUserTable">添加新用户</button>
                </div>
                <div class="input-group form-group">
                    <input type="text" class="form-control input-mask-product" v-model="searchUserName" placeholder="姓名/学工号" @keyup="searchUser($event)">
                    <span class="input-group-btn">
                        <button class="btn btn-sm" @click="searchUser($event)">
                            <i class="ace-icon glyphicon glyphicon-search bigger-120"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="table-responsive" v-if="showUserTable">
            <table class="table table-striped table-bordered table-hover dataTable">
                <thead>
                    <tr>
                        <th>用户名</th>
                        <th class="center little">姓名</th>
                        <th class="hidden-640">单位名称</th>
                        <th class="center little">移动电话</th>
                        <th class="hidden-640">电子邮箱</th>
                        <th class="center little hidden-640">状态</th>
                        <th class="center little">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user,index) in userList" :key="'user'+index">
                        <td>
                            {{user.username}} <span v-if="user.username == loginUser.username">（自己）</span>
                        </td>
                        <td class="center little">{{user.name}}</td>
                        <td class="hidden-640">{{user.org_name}}</td>
                        <td class="center little">{{user.mobile}}</td>
                        <td class="hidden-640">{{user.email}}</td>
                        <td class="center little hidden-640">
                            {{user.person_state == 'yes'?"开启":"禁用"}}   
                        </td>
                        <td class="center little">
                            <span v-if="user.username == 'admin'">系统账户不能操作</span>
                            <div class="hidden-xs btn-group" v-if="user.username != 'admin'">
                                <button class="btn btn-success btn-xs" @click="sure(user)">
                                    <i class="ace-icon glyphicon glyphicon-edit" v-if="$route.name=='userList'"></i>
                                    <i class="ace-icon glyphicon glyphicon-ok" v-if="$route.name!='userList'"></i>
                                </button>
                                <button class="btn btn-danger btn-xs" @click="delUser(user)">
                                    <i class="ace-icon fa fa-trash-o"></i>
                                </button>
                            </div>
                            <div class="hidden-sm hidden-md hidden-lg" v-if="user.username != 'admin'">
                                <div class="inline pos-rel">
                                    <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto" aria-expanded="false">
                                        <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                        <li>
                                            <a class="tooltip-info blue" data-rel="tooltip" data-original-title="View"  @click="sure(user)">
                                                <i class="ace-icon glyphicon glyphicon-edit" v-if="$route.name=='userList'"></i>
                                                <i class="ace-icon glyphicon glyphicon-ok" v-if="$route.name!='userList'"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="tooltip-info red" data-rel="tooltip" data-original-title="View"  @click="delUser(user)">
                                                <i class="ace-icon fa fa-trash-o"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <CreateUser v-if="!showUserTable"
            :showUserList="showUserList"
            :getUserList = "getUserList"
            :user = "currentUser"
        ></CreateUser>
    </div>
</template>
<script>
    import CreateUser from './createUser';
    export default {
        name:"userList",
        props:["sure"],
        components:{CreateUser},
        data(){
            return {
                userList:[],
                showUserTable:true,
                currentUser:null,
                searchUserName:"",
            }
        },
        methods:{
            getUserList(data){
                const _this = this;
                const URL = this.serverUrl +"/admin/person/index";
                this.emitAjax(URL,data,function(result){    
                    _this.userList = result;
                })
            },
            showUserList(){
                this.showUserTable = true;
                this.currentUser = null;
            },
            delUser(user){
                if(window.confirm("是否删除<"+user.name+">,此操作不可逆，请慎重！")){
                    const _this = this;
                    const URL = this.serverUrl +"/admin/person/del";
                    this.emitAjax(URL,{id:user.id},function(){
                        _this.getUserList();
                    })
                }
            },
            searchUser(event){
                if(event.type == "keyup" && event.key!="Enter"){
                    return false;
                }
                this.getUserList({
                    keywords:this.searchUserName
                })
            }
        },
        mounted(){
            this.getUserList();
        }
    };  
</script>