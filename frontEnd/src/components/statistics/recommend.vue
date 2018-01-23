<template>
    <div class="main-content checkStatistics" @click="setIsOpen(false)">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/checkStatistics'" class="active">{{title}}</router-link>
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
                <search :show="isOpen" :setShow="setIsOpen">
                    <div class="form-search widget-main">
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
                                    <button class="btn btn-purple btn-sm"  @click="search">
                                        <span class="ace-icon fa fa-search icon-on-right bigger-110">搜索</span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </search>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="center" width="60px">序号</th>
                                <th>实验室名称</th>
                                <th class="center">平均分（自查）</th>
                                <th class="center">平均分（复查）</th>
                                <th class="center">平均分（抽查）</th>
                                <th>评优推荐</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(org,index) in org_list" :key="'org'+org.org_id">
                                <td>{{index+1}}</td>
                                <td>{{org.org_name}}</td>
                                <td class="center">
                                    {{computedScore("lab",org.org_id)}}
                                </td>
                                <td class="center">
                                    {{computedScore("college",org.org_id)}}
                                </td>
                                <td class="center">
                                    {{computedScore("school",org.org_id)}}
                                </td>
                                <td>
                                    <button :class="['btn btn-xs',{'btn-warning':org.isExcellent==2}]" v-if="loginUser.group_level=='college'&&org.isExcellent!=1" @click="setRecommend(org)">推荐</button>
                                    <span v-if="loginUser.group_level=='college'&&org.isExcellent==1">已评优</span>
                                    <button :class="['btn btn-xs',{'btn-warning':org.isExcellent==1}]" v-if="loginUser.group_level=='school'" @click="setExcellent(org)">评优</button>
                                </td>
                            </tr>
                            <tr v-if="org_list.length==0" class="center">
                                <td colspan="1111">暂无安全统计</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import search from '../common/search';
export default {
    name: "checkStatistics",
    data() {
        return {
            title: "检查统计表",
            selectPlan: {},
            task_list:{
                school:[],
                lab:[],
                college:[]
            },
            searchKey:"",
            org_list:[],
            check_list:[],
            isOpen:false,
        };
    },
    components:{search},
    computed: {
        plan_list() {
            return this.$store.state.plan_list;
        }
    },
    watch:{
        selectPlan(){
            this.getCheckStatistics();
        }
    },
    methods: {
        init() {
            if (this.$store.state.plan_list.length == 0) {
                this.$store.dispatch("getPlanList");
            }
            this.getCheckStatistics();
        },
        getCheckStatistics() {
            //获取数据列表
            const URL = this.serverUrl + "/admin/statistics/recommendStatistics";
            const _this = this;
            let data = {
                plan_id:this.selectPlan.plan_id,
                org_name:this.searchKey,
            }
            this.emitAjax(URL, data, function(result) {
                console.log(result)
                _this.org_list = result.org_list;
                _this.check_list = result.check_list;
                _this.task_list = {
                    school:[],
                    lab:[],
                    college:[]
                }
                _this.searchKey = '';
                _this.setIsOpen(false);
                for (let index = 0; index < result.task_list.length; index++) {
                    const task = result.task_list[index];
                    _this.task_list[task.task_level].push(task);
                }
            });
        },
        search(){
            //搜索实验室名称
            $("#collapse").collapse("hide");
            this.getCheckStatistics();
        },
        computedScore(type,org_id){
            //计算平均分
            let totalScore = 0;
            let count = 0;
            for (let index = 0; index < this.task_list[type].length; index++) {
                const task = this.task_list[type][index];
                for (let i = 0; i < this.check_list.length; i++) {
                    const check = this.check_list[i];
                    if(check.task_id == task.task_id && check.org_id == org_id){
                        totalScore += parseFloat(check.check_score);
                    }
                    if(check.task_id == task.task_id && check.check_state == 'finished'){
                        count++;
                    }
                }
            }
            const score = count != 0 ?totalScore/count:0;
            return parseFloat(score.toFixed(2));
        },
        setExcellent(org){
            //评优
            let falg = false;
            if(org.isExcellent == 2){
                if(confirm("是否要给"+org.org_name+"评优？")){
                    org.isExcellent = 1;
                    falg = true;
                }
            }else{
                if(confirm("是否要取消"+org.org_name+"的评优？")){
                    org.isExcellent = 2;
                    falg = true;
                }
            }
            if(falg){
                const URL= this.serverUrl + "/admin/statistics/setExcellent";
                const _this = this;
                const data = {
                    plan_id:this.selectPlan.plan_id,
                    org_id:org.org_id,
                    isExcellent:org.isExcellent
                }
                this.emitAjax(URL,data,null,function(){
                    //网络出问题时
                    _this.getCheckStatistics();
                })
            }
        },
        setRecommend(org){
            //推荐评优
            let falg = false;
            if(org.isExcellent == 0){
                if(confirm("是否要推荐"+org.org_name+"为评优实验室？")){
                    org.isExcellent = 2;
                    falg = true;
                }
            }else{
                if(confirm("是否要取消"+org.org_name+"的评优推荐？")){
                    org.isExcellent = 0;
                    falg = true;
                }
            }
            if(falg){
                const URL= this.serverUrl + "/admin/statistics/setRecommend";
                const _this = this;
                const data = {
                    plan_id:this.selectPlan.plan_id,
                    org_id:org.org_id,
                    isExcellent:org.isExcellent
                }
                this.emitAjax(URL,data,null,function(){
                    //网络出问题时
                    _this.getCheckStatistics();
                })
            }
        },
        setIsOpen(bool){
            //控制搜索下拉菜单
            this.isOpen = bool;
        }
    },
    mounted() {
        this.init();
    }
};
</script>
