<template>
    <div class="checkorg">
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
                                <router-link :to="pathName+'/orgList'" class="active">{{title}}</router-link>
                            </li>
                        </ul>
                    </div>
                    <!-- 右侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{title}}
                                <div class="pull-right">
                                    <router-link class="btn btn-primary btn-sm" tag="button" v-if="permission[loginUser.user_level] >= permission.college"
                                    :to="{path:pathName+'/orgEdit'}">添加</router-link>
                                </div>
                            </h1>
                        </div>
                        
                        <div class="dataTables_wrapper form-inline no-footer">
                            <div class="row"  v-if="permission[loginUser.user_level] >= permission.college">
                                <div class="col-xs-12">
                                    <select @change="filterList" v-model="orgType">
                                        <option value="all">全部</option>
                                        <option value="school" v-if="permission[loginUser.user_level] >= permission.school">显示学校</option>
                                        <option value="college" v-if="permission[loginUser.user_level] >= permission.college">显示院系</option>
                                        <option value="lab">显示实验室</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTable">
                                    <thead>
                                        <tr>
                                            <th class="center" width="60px">单位ID</th>
                                            <th>单位名称</th>
                                            <th class="hidden-640">单位地址</th>
                                            <th>所属单位</th>
                                            <th class="center little">单位级别</th>
                                            <th class="center little">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(org,index) in orgArray" :key="'orgList'+index">
                                            <td class="center">{{org.org_id}}</td>
                                            <td>
                                                <router-link :to="{path:pathName+'/orgEdit',query:{org_id:org.org_id}}">{{org.org_name}}</router-link>
                                            </td>
                                            <td class="hidden-640">{{org.org_address}}</td>
                                            <td>{{getParentName(org.pid)}}</td>
                                            <td class="center">
                                                <span v-if="org.org_level === 'school'">学校</span>
                                                <span v-if="org.org_level === 'college'">院系</span>
                                                <span v-if="org.org_level === 'lab'">实验室</span>
                                            </td>
                                            <td class="center">
                                                <div class="hidden-xs btn-group">
                                                    <router-link class="btn btn-success btn-xs" tag="button" 
                                                        :to="{path:pathName+'/orgEdit',query:{org_id:org.org_id}}">
                                                        <i class="ace-icon glyphicon glyphicon-edit bigger-100"></i>    
                                                    </router-link>
                                                    <button class="btn btn-danger btn-xs" @click="delOrg(org)">
                                                        <i class="ace-icon fa fa-trash-o bigger-110"></i>
                                                    </button>
                                                </div>
                                                <div class="hidden-sm hidden-md hidden-lg">
                                                    <div class="inline pos-rel">
                                                        <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto" aria-expanded="false">
                                                            <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                                            <li>
                                                                <router-link class="tooltip-info blue" :to="{path:pathName+'/orgEdit',query:{org_id:org.org_id}}" data-rel="tooltip" data-original-title="View">
                                                                    <i class="ace-icon glyphicon glyphicon-edit bigger-100"></i>
                                                                </router-link>
                                                            </li>
                                                            <li>
                                                                <a class="tooltip-info red" data-rel="tooltip" data-original-title="View"  @click="delOrg(org)">
                                                                    <i class="ace-icon fa fa-trash-o bigger-110"></i>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import VueHead from "../common/header";
    import VueLeft from "../common/leftMenu";

    export default {
        name:"checkorg",
        data(){
            return {
                title:"单位列表",
                changeObj:null,
                orgType:"all",
                orgArray:[],
                orgListArray:[],
            }
        },
        components:{VueHead,VueLeft},
        computed:{
            orgList(){
                return this.$store.state.orgList;
            }
        },
        watch:{
            orgList(){
                this.filterList();
            }
        },
        methods:{
            getOrgList(data){
                //获取单位列表
                this.$store.dispatch("getOrgList",data);
            },
            getParentName(id){
                //获取所属单位名称
                let org_name = '无';
                for (let index = 0; index < this.orgList.length; index++) {
                    const element = this.orgList[index];
                    if(element.org_id ==id){
                        org_name = element.org_name;
                        break;
                    }
                }
                return org_name;
            },
            delOrg(org){
                //删除单位
                const _this = this;
                const URL = this.serverUrl+'/admin/org/del';
                if(window.confirm("是否要删除单位<"+org.org_name+">,此操作不可逆，请慎重！")){
                    this.emitAjax(URL,{org_id:org.org_id},function(result){
                        _this.getOrgList();
                    })
                }
            },
            filterList(){
                //检索整个单位列表
                this.orgListArray = [];
                for (let index = 0; index < this.orgList.length; index++) {
                    const org = this.orgList[index];
                    if(this.orgType == "all"){
                        this.orgListArray.push(org);
                    }else{
                        if(this.orgType == org.org_level){
                            this.orgListArray.push(org);
                        }
                    }
                }
                this.filterOrgList();
            },
            filterOrgList(){
                //检索单位列表后，判断权限
                this.orgArray = [];
                for (let index = 0; index < this.orgListArray.length; index++) {
                    const org = this.orgListArray[index];
                    switch (this.loginUser.user_level) {
                        case "lab":
                            if(org.org_level == "lab"){
                                this.orgArray.push(Object.assign({},org));
                            }
                            break;
                        case "college":
                            if(org.org_level != "school"){
                                this.orgArray.push(Object.assign({},org));
                            }
                            break;
                        default:
                            this.orgArray.push(Object.assign({},org));
                            break;
                    }
                }
            }
        },
        mounted(){
            if(this.checkPermission(this)){
                this.getOrgList();
            }
        }
    };
</script>