<template>
    <!-- 右侧内容 -->
    <div class="main-content checkorg">
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
                <div class="table-responsive" v-if="loginUser.user_level != 'school'">
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
                                        <router-link class="btn btn-success btn-xs" tag="button" title="编辑" 
                                            :to="{path:pathName+'/orgEdit',query:{org_id:org.org_id}}">
                                            <i class="ace-icon glyphicon glyphicon-edit bigger-100"></i>    
                                        </router-link>
                                        <button class="btn btn-danger btn-xs" @click="delOrg(org)" title="删除">
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
                            <tr v-if="orgArray.length==0">
                                <td colspan="6" class="center">暂无单位数据</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <ul class="list-group orgList" v-for="school in schoolOrg" :key="school.org_id" v-if="loginUser.user_level == 'school'">
                    <li class="list-group-item">
                        <router-link :to="{path:pathName+'/orgEdit',query:{org_id:school.org_id}}">
                            <h4><i class="ace-icon fa fa-home"></i> {{school.org_name}}</h4>
                        </router-link>
                    </li>
                    <li class="list-group-item clearfix" v-for="college in collegeOrg" :key="college.org_id" v-if="college.pid==school.org_id">
                        <div class="col-xs-12">
                            <router-link :to="{path:pathName+'/orgEdit',query:{org_id:college.org_id}}" class="orange"><h5>{{college.org_name}}</h5></router-link>
                            <span v-for="lab in labOrg" :key="lab.org_id" v-if="lab.pid==college.org_id"> 
                                <router-link :to="{path:pathName+'/orgEdit',query:{org_id:lab.org_id}}" class="green"> <i class="ace-icon fa fa-flask"></i> {{lab.org_name}} </router-link>
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name:"checkorg",
        data(){
            return {
                title:"单位列表",
                changeObj:null,
                orgType:"all",
                orgArray:[],
                orgListArray:[],
                labOrg:[],
                collegeOrg:[],
                schoolOrg:[],
            }
        },
        computed:{
            orgList(){
                return this.$store.state.orgList;
            }
        },
        watch:{
            orgList(){
                this.filterOrgList();
            },
            orgType(){
                this.filterOrgList();
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
            filterOrgList(){
                //检索单位列表后，判断权限
                this.orgArray = [];
                this.labOrg=[];
                this.collegeOrg=[];
                this.schoolOrg=[];
                for (let index = 0; index < this.orgList.length; index++) {
                    const org = this.orgList[index];
                    switch (this.loginUser.user_level) {
                        case "lab":
                            if(org.org_level == "lab"){
                                this[org.org_level+'Org'].push(org);
                                this.orgArray.push(Object.assign({},org));
                            }
                            break;
                        case "college":
                            if(org.org_level != "school"){
                                this[org.org_level+'Org'].push(org);
                                this.orgArray.push(Object.assign({},org));
                            }
                            break;
                        default:
                            this[org.org_level+'Org'].push(org);
                            this.orgArray.push(Object.assign({},org));
                            break;
                    }
                }
            }
        },
        mounted(){
            this.getOrgList();
        }
    };
</script>
<style>
    .orgList h5,.orgList h4 {
        margin:0;
    }
</style>
