<template>
    <div class="main-content checkList">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/awards'" class="active">{{title}}</router-link>
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
                    <button class="btn btn-primary btn-sm" role="button" data-toggle="collapse" href="#collapse" aria-expanded="false" aria-controls="collapseExample">
                        <i class="ace-icon glyphicon glyphicon-search"></i>
                        检索
                    </button>
                </p>
                <div class="collapse" id="collapse">
                    <div class="row">
                        <div class="col-sm-8 col-md-6 col-lg-4">
                            <div class="widget-box">
                                <div class="form-search widget-main padding-16">
                                    <div class="form-group">
                                        <select v-model="selectPlan">
                                            <option :value="{}">当前期次</option>
                                            <option :value="plan" v-for="plan in plan_list" :key="'plan'+plan.plan_id">{{plan.plan_name}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" name="" id="" class="form-control search-query" 
                                                placeholder="输入实验室名称" v-model="searchKey">
                                            <span class="input-group-btn">
                                                <button class="btn btn-purple btn-sm" @click="getAwardsList">
                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110">搜索</span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="center" width="60px">序号</th>
                            <th>实验室名称</th>
                            <td>期次</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(awards,index) in awards_list" :key="'awards'+awards.id">
                            <td class="center">{{index+1}}</td>
                            <td>{{awards.org_name}}</td>
                            <td>{{awards.plan_name}}</td>
                        </tr>
                        <tr v-if="awards_list.length ==0" class="center">
                            <td colspan="3">暂无评优</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "awards",
    data() {
        return {
            title: "评奖评优",
            selectPlan:{},
            awards_list:[],
            searchKey:"",
        };
    },
    computed: {
        plan_list() {
            return this.$store.state.plan_list;
        }
    },
    watch:{
        selectPlan(){
            this.getAwardsList();
        }
    },
    methods: {
        init() {
            if (this.$store.state.plan_list.length == 0) {
                this.$store.dispatch("getPlanList");
            }
            this.getAwardsList();
        },  
        getAwardsList(){
            //获取数据列表
            const URL = this.serverUrl + "/admin/statistics/excellentStatistics";
            const _this = this;
            let data = {
                plan_id:this.selectPlan.plan_id,
                org_name:this.searchKey,
            }
            this.emitAjax(URL, data, function(result) {
                _this.awards_list = result;
                _this.searchKey = "";
            });
        }
    },
    mounted() {
        this.init();
    }
};
</script>
