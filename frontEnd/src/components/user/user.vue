<template>
    <!-- 右侧内容 -->
    <div class="main-content user">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/userList'" class="active">{{title}}</router-link>
                    </li>
                </ul>
            </div>
            <!-- 右侧主要内容 -->
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{title}}
                        <div class="pull-right">
                            <span class="dropdown" slot="right">
                                <button class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                    操作
                                    <i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-info">
                                    <li>
                                        <router-link class="" :to="pathName+'/importPerson'">
                                            <i class="ace-icon fa fa-cloud-upload"></i>
                                            导入人员
                                        </router-link>
                                    </li>
                                    <li>
                                        <a :href="downUrl" target="_blank">
                                            <i class="ace-icon fa fa-cloud-download"></i>
                                            导出人员
                                        </a>
                                    </li>
                                </ul>
                            </span>
                            <button class="btn btn-primary btn-sm" @click="showUserTable = false">
                                <i class="ace-icon glyphicon glyphicon-plus hidden-480"></i>
                                添加
                            </button>
                        </div>
                    </h1>
                    
                </div>
                <UserList
                    :sure="editUser"
                    ref="userList"  
                    :isShow="showUserTable"
                    :setIsShow = "setIsShow"
                    :setDownUrl = "setDownUrl"
                ></UserList>
            </div>
        </div>
    </div>
</template>
<script>
    import UserList from './userList';

    export default {
        name:"user",
        data(){
            return {
                title:"人员管理",
                showUserTable:true,
                downUrl:this.serverUrl+'/admin/person/personExport',
            }
        },
        methods:{
            editUser(user){
                this.$refs.userList.showUserTable = false;
                this.$refs.userList.currentUser = user;
            },
            setIsShow(bool){
                this.showUserTable = bool;
            },
            setDownUrl(url){
                this.downUrl = this.serverUrl+'/admin/person/personExport' + url;
            }
        },
        components:{UserList}
    };
</script>
<style>
    .dataTables_wrapper input[type=password] {
        margin:0 4px;
    }
</style>

