<template>
    <div class="userList dataTables_wrapper">
        <div class="row" v-if="showUserTable">
            <div class="col-xs-12 form-inline">
                <div class="form-group">
                    <button class="btn btn-primary btn-sm" @click="showUserTable = false" v-if="showUserTable">
                        <i class="ace-icon glyphicon glyphicon-plus hidden-480"></i>
                        添加
                    </button>
                </div>
                <div class="input-group form-group">
                    <input type="text" class="form-control input-mask-product" v-model="searchUserName" placeholder="姓名/学工号" @keyup="searchUser($event)">
                    <span class="input-group-btn">
                        <button class="btn btn-sm" @click="searchUser($event)">
                            <i class="ace-icon glyphicon glyphicon-search bigger-120"></i>
                        </button>
                    </span>
                </div>
                <div class="form-group">
                    <select v-model="searchType">
                        <option :value="null">当前单位</option>
                        <option value="all">全部</option>
                    </select>
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
                        <th class="center little">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user,index) in userList" :key="'user'+index"
                        v-if="(index>=(page-1)*pageCount && index<page*pageCount)&&user.username!='admin'"
                    >
                        <td>
                            {{user.username}} <span v-if="user.username == loginUser.username">（自己）</span>
                        </td>
                        <td class="center little">{{user.name}}</td>
                        <td class="hidden-640">{{user.org_name}}</td>
                        <td class="center little">
                            <div class="hidden-xs btn-group">
                                <button class="btn btn-success btn-xs" @click="sure(user)">
                                    <i class="ace-icon glyphicon glyphicon-ok"></i>
                                </button>
                            </div>
                            <div class="hidden-sm hidden-md hidden-lg">
                                <div class="inline pos-rel">
                                    <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto" aria-expanded="false">
                                        <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                        <li>
                                            <a class="tooltip-info blue" data-rel="tooltip" data-original-title="View"  @click="sure(user)">
                                                <i class="ace-icon glyphicon glyphicon-ok"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <page
                :pages = "Math.ceil(userList.length/pageCount)"
                :setPage = "setPage"
            ></page>
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
    import page from '../common/page.vue';
    export default {
        name:"userModal",
        props:["sure"],
        components:{CreateUser,page},
        data(){
            return {
                userList:[],
                showUserTable:true,
                currentUser:null,
                searchType:null,
                searchUserName:"",
                page:1,
                pageCount:15,
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
            },
            setPage(page){
                this.page = page;
            }
        },
        watch:{
            searchType(){
                this.getUserList({
                    flag:this.searchType
                })
            }
            
        },
        mounted(){
            this.getUserList();
        }
    };  
</script>