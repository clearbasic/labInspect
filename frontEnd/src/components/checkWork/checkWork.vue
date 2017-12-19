<template>
    <div class="checkWork">
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
                                <router-link :to="pathName+'/checkWork'" class="active">{{title}}</router-link>
                            </li>
                        </ul>
                    </div>
                    <!-- 右侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{title}}
                                <div class="pull-right">
                                    <select v-model="plan_id" style="font-size:14px">
                                        <option value="0">当前期次</option>
                                        <option :value="plan.plan_id" v-for="plan in plan_list" :key="'plan'+plan.plan_id">{{plan.plan_name}}</option>
                                    </select>
                                    <select v-model="college_id" style="font-size:14px" v-if="permission[loginUser.user_level] >= permission.school"> 
                                        <option :value="org.org_id" v-for="org in college_list" :key="'org'+org.org_id">{{org.org_name}}</option>
                                    </select>
                                </div>
                            </h1>
                        </div>
                        <h3 class="text-center" style="margin-top:10px;">
                           {{college_name}} - {{currentPlan.plan_name}}
                        </h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <h5 class="text-center">
                                    共有自查{{task_list.lab.length}}次，
                                    复查{{task_list.college.length}}次，
                                    抽查{{task_list.school.length}}次。
                                    满分{{currentPlan.plan_score}}分
                                    <router-link to=''>工作说明</router-link>
                                </h5>
                            </div>
                        </div>
                        <!-- 循环实验室三种任务 -->
                        <div class="widget-box  widget-color-blue" v-for="(tasks,key) in task_list" :key="'taskItem'+key" v-if="tasks.length>0">
                            <div class="widget-header">
                                <h5 v-if="key == 'lab'">自查</h5>
                                <h5 v-if="key == 'college'">复查</h5>
                                <h5 v-if="key == 'school'">抽查</h5>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main no-padding no-margin table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="center little">任务名称</th>
                                                <th>学校要求时间</th>
                                                <th class="center little">开展情况</th>
                                                <th>查看详情</th>
                                                <th>查看结果</th>
                                            </tr>
                                        </thead>
                                        <!-- 循环每个实验室的具体任务 -->
                                        <tbody>
                                            <tr v-for="task in tasks" :key="'checkWork'+task.check_id">
                                                <td class="center little">{{task.task_name}}</td>
                                                <td>{{task.dt_begin.substring(0,10)}} 到 {{task.dt_end.substring(0,10)}}</td>
                                                <td class="center little">
                                                    <span v-if="task.sum == '0'">未开展</span>
                                                    <span v-if="task.sum != '0'">{{task.state}}</span>
                                                </td>
                                                <td>
                                                    <span v-if="task.sum == '0'">详情</span>
                                                    <router-link 
                                                        :to="{path:pathName+'/checkWork/'+task.task_id,query:{college_id,plan_id}}"
                                                        v-if="task.sum != '0'">
                                                        详情
                                                    </router-link>
                                                    
                                                </td>
                                                <td>{{task.problem_fatal?task.problem_fatal:0}}/{{task.problem_common?task.problem_common:0}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <h5 v-if="task_list.lab.length==0&&task_list.college.length==0&&task_list.school.length==0" class="center red">
                            您还没有设置此期次的任务哦，如要设置
                            <router-link :to="pathName+'/checkPlan/'+plan_id">点我点我</router-link>
                            ！
                        </h5>
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
    name: "checkWork",
    components: { VueHead, VueLeft },
    data() {
        return {
            title: "检查工作列表",
            currentPlan:{},
            plan_id:0,
            plan_list:[],
            college_list:[],
            college_id:0,
            college_name:"",
            task_list:{
                lab:[],
                college:[],
                school:[],
            },
        };
    },
    watch:{
        college_id(){
            this.getCurrentPlan();
        },
        plan_id(){
            this.getCurrentPlan();
        }
    },
    methods: {
        getPlanList(){
            //获取期次列表
            const _this = this;
            const URl = this.serverUrl + "/admin/plan/index";
            this.emitAjax(URl,null,function(result){
                _this.plan_list = result;
            });
        },
        getCurrentPlan(){
            //获取期次信息
            const _this = this;
            const URl = this.serverUrl + "/admin/check/checkindex";
            let data = this.plan_id != 0?{
                    college_id:this.college_id,
                    plan_id:this.plan_id
                }:{
                    college_id:this.college_id,
                };
            this.emitAjax(URl,data,function(result){
                _this.currentPlan = result.plan;
                _this.setTaskType(result.check_list);
            });
        },
        getCollegeList(){
            //如果是学校级别，显示学院选项
            const _this = this;
            const URl = this.serverUrl + "/admin/org/index";
            this.emitAjax(URl,null,function(result){
                _this.filterOrg(result);
            });  
        },
        filterOrg(orgList){
            this.college_list = [];
            for (let index = 0; index < orgList.length; index++) {
                const org = orgList[index];
                if(org.org_level != "lab"){
                    this.college_list.push(Object.assign({},org));
                }
            }
            this.getCollege();
        },
        getCollege(){
            for (let index = 0; index < this.college_list.length; index++) {
                const college = this.college_list[index];
                if(this.college_id == college.org_id){
                    this.college_name = college.org_name;
                    break;
                }
            }
        },
        setTaskType(taskList){
            //期次任务分类
            const _this = this;
            this.task_list={
                lab:[],
                college:[],
                school:[],
            }
            for (let index = 0; index < taskList.length; index++) {
                const task = taskList[index];
                _this.task_list[task.task_level].push(Object.assign({},task));
            }
        },
        labUserInit(){
            this.college_id = this.loginUser.org_id;
            this.getPlanList();
            this.getCollegeList();
            this.getCurrentPlan();
        },
        init(){
            if(this.loginUser.user_level == "lab"){
                this.labUserInit();
            }else{
                this.college_id = this.loginUser.org_id;
                this.getPlanList();
                this.getCollegeList();
                this.getCurrentPlan();
            }
        }
    },
    mounted() {
        if(this.checkPermission(this)){
            this.init();
        }
    }
};
</script>
<style>
    .widget-box {
        margin-bottom:20px;
    }
    .yellow {
        color:#e4c523;
    }
</style>