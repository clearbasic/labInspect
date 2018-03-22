<template>
    <div class="userList dataTables_wrapper" @click="setIsOpen(false)">
        <search :show="isOpen" :setShow="setIsOpen" v-if="showUserTable">
            <div class="input-group">
                <input type="text" class="form-control input-mask-product" v-model="searchUserName" placeholder="姓名/学工号" @keyup="searchUser($event)">
                <span class="input-group-btn">
                    <button class="btn btn-sm btn-purple" @click="searchUser($event)">
                        <i class="ace-icon glyphicon glyphicon-search bigger-120"></i>
                    </button>
                </span>
            </div>
        </search>
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
                    <tr v-for="(user,index) in userList" :key="'user'+index"
                        v-if="(index>=(page-1)*pageCount && index<page*pageCount)&&user.username != 'admin'"
                    >
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
                            <div class="hidden-xs btn-group" >
                                <button class="btn btn-success btn-xs" @click="sure(user)" title="编辑">
                                    <i class="ace-icon glyphicon glyphicon-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-xs" @click="delUser(user)" title="删除">
                                    <i class="ace-icon fa fa-trash-o"></i>
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
                                                <i class="ace-icon glyphicon glyphicon-edit"></i>
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
        <page
            :pages = "pages"
            :setPage = "setPage"
            :currentPage="page"
            v-if="showUserTable"
        ></page>
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
    import search from '../common/search.vue';

    export default {
        name:"userList",
        props:{
            sure:{
                type:Function,
                default:null
            },
            isShow:{
                type:Boolean,
                default:true
            },
            setIsShow:{
                type:Function,
                default:null
            },
            setDownUrl:{
                type:Function,
                default:null
            }
        },
        components:{CreateUser,page,search},
        data(){
            return {
                userList:[],
                showUserTable:true,
                currentUser:null,
                searchUserName:"",
                page:1,
                pages:1,
                pageCount:15,
                isOpen:false, //搜索控制下拉菜单
            }
        },
        watch:{
            isShow(){
                this.showUserTable = this.isShow;
            },
            showUserTable(){
                if(this.showUserTable){
                    this.setIsShow(this.showUserTable);
                }
            },
        },
        methods:{
            getUserList(data){
                const _this = this;
                const URL = this.serverUrl +"/admin/person/index";
                this.emitAjax(URL,data,function(result){
                    _this.userList = result.person_list;
                    _this.pages = Math.ceil(result.length/_this.pageCount)>0?Math.ceil(result.length/_this.pageCount):1;
                    _this.pages = result.pages;
                    if(_this.searchUserName){
                        _this.setDownUrl("?keywords="+_this.searchUserName);
                    }else{
                        _this.setDownUrl("");
                    }
                    _this.setIsOpen(false);
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
            },
            setIsOpen(bool){
                this.isOpen = bool;
            }
        },
        mounted(){
            this.getUserList();
        }
    };  
</script>