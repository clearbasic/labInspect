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
                    <h1>{{title}}</h1>
                </div>
                <UserList
                :sure="editUser"
                ref="userList"
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
                title:"用户管理"
            }
        },
        methods:{
            editUser(user){
                this.$refs.userList.showUserTable = false;
                this.$refs.userList.currentUser = user;
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

