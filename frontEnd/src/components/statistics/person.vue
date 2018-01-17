<template>
    <div class="main-content personStatistics">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/personStatistics'" class="active">{{title}}</router-link>
                    </li>
                </ul>
            </div>
            <!-- 右侧主要内容 -->
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{title}}
                    </h1>
                </div>
                <p>
                    <button class="btn btn-primary btn-sm" v-if="permission[loginUser.group_level] >= permission['school']" @click="searchPerson('college')">按学院</button>
                    <button class="btn btn-primary btn-sm" v-if="permission[loginUser.group_level] >= permission['college']" @click="searchPerson('lab')">按实验室</button>
                    <button class="btn btn-primary btn-sm"  @click="searchPerson('room')">按房间</button>
                </p>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="center" style="width:60px;">序号</th>
                            <th>用户名</th>
                            <th class="center little">姓名</th>
                            <th class="hidden-640">单位名称</th>
                            <th class="center little">移动电话</th>
                            <th class="hidden-640">电子邮箱</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user,index) in person_list" :key="'user'+index">
                            <td>{{index+1}}</td>
                            <td>
                                {{user.username}}
                            </td>
                            <td class="center little">{{user.name}}</td>
                            <td class="hidden-640">{{user.org_name}}</td>
                            <td class="center little">{{user.mobile}}</td>
                            <td class="hidden-640">{{user.email}}</td>
                        </tr>
                        <tr v-if="person_list.length==0" class="center">
                            <td colspan="6">暂无安全责任人</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    name: "personStatistics",
    data() {
        return {
            title: "安全责任人统计",
            person_list:[],
            searchType:'room'
        };
    },
    watch:{
        searchType(){
            this.getPerson();
        }
    },
    methods: {
        getPerson(){
            const URL = this. serverUrl + '/admin/statistics/responTable';
            const _this = this;
            this.emitAjax(URL, {org_level:this.searchType}, function(result) {
                _this.person_list = result;
            });
        },
        searchPerson(type){
            this.searchType = type;
        }   
    },
    mounted() {
        this.getPerson();
    }
};
</script>
