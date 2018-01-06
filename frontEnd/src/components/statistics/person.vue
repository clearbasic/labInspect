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
        };
    },
    methods: {
        getPerson(){
            const URL = this. serverUrl + '/admin/statistics/responTable';
            const _this = this;
            this.emitAjax(URL, null, function(result) {
                _this.person_list = result;
            });
        }
    },
    mounted() {
        this.getPerson();
    }
};
</script>
