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
                        <div class="pull-right">
                            <button :class="['btn btn-sm',{'btn-primary':searchType!='college'},{'btn-success':searchType=='college'}]"
                                v-if="permission[loginUser.group_level] >= permission['school']" 
                                @click="searchPerson('college')">按学院</button>
                            <button :class="['btn btn-sm',{'btn-primary':searchType!='lab'},{'btn-success':searchType=='lab'}]" 
                            v-if="permission[loginUser.group_level] >= permission['college']" 
                            @click="searchPerson('lab')">按实验室</button>
                            <button :class="['btn btn-sm',{'btn-primary':searchType!='room'},{'btn-success':searchType=='room'}]"  
                                @click="searchPerson('room')">按房间</button>
                        </div>
                    </h1>
                </div>
                <h3 class="center">
                    <span v-if="searchType=='college'">院系安全责任人登记表</span>
                    <span v-if="searchType=='lab'">实验室安全责任人登记表</span>
                    <span v-if="searchType=='room'">房间安全责任人登记表</span>
                </h3>
                <div class="table-responsive">
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
                            <tr v-for="(user,index) in person_list" 
                                :key="'user'+index"
                                v-if="index>=(page-1)*pageCount && index<page*pageCount"
                            >
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
                <page
                    :pages = "Math.ceil(person_list.length/pageCount)"
                    :setPage = "setPage"
                    :currentPage="page"
                >
                </page>
            </div>
        </div>
    </div>
</template>
<script>
import page from '../common/page';
export default {
    name: "personStatistics",
    data() {
        return {
            title: "安全责任人统计",
            person_list:[],
            searchType:'room',
            page:1,
            pageCount:15,
        };
    },
    components:{page},
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
        },
        setPage(page){
            this.page = page;
        },  
    },
    mounted() {
        this.getPerson();
    }
};
</script>
