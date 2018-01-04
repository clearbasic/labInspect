<template>
    <!-- 右侧内容 -->
    <div class="main-content checkWork">
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
                                <option value="0">--当前期次--</option>
                                <option :value="plan.plan_id" v-for="plan in plan_list" :key="'plan'+plan.plan_id">{{plan.plan_name}}</option>
                            </select>
                            <select v-model="college_id" style="font-size:14px" v-if="permission[loginUser.user_level] >= permission.school"> 
                                <option value="0">--请选择--</option>
                                <option :value="org.org_id" v-for="org in college_list" :key="'org'+org.org_id">{{org.org_name}}</option>
                            </select>
                        </div>
                    </h1>
                </div>
                <h3 class="text-center" style="margin-top:10px;" v-if="college_id!=0">
                    <span v-if="college_name">{{college_name}} - </span>{{currentPlan.plan_name}}
                </h3>
                <div class="row" v-if="college_id!=0">
                    <div class="col-xs-12">
                        <h5 class="text-center">
                            <span v-if="task_list.lab.length>0">共有自查{{task_list.lab.length}}次，</span>
                            <span v-if="task_list.college.length>0">复查{{task_list.college.length}}次，</span>
                            <span v-if="task_list.school.length>0">抽查{{task_list.school.length}}次，</span>
                            满分{{currentPlan.plan_score}}分
                            <router-link :to="{path:pathName+'/checkPlanSummary',query:{plan_id:currentPlan.plan_id}}">工作说明</router-link>
                        </h5>
                    </div>
                </div>
                <!-- 循环实验室三种任务 -->
                <h5 v-if="permission[loginUser.user_level] > permission.college && college_id==0" class="center red">
                    请于右上角选择要查看的期次和学院
                </h5>
                <div class="widget-box  widget-color-blue" v-for="(tasks,key) in task_list" :key="'taskItem'+key" v-if="tasks.length>0 && college_id!=0">
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
                                        <th class="center little">检查安排</th>
                                        <th>学校要求时间</th>
                                        <th class="center little">开展情况</th>
                                        <th class="center little">分配</th>
                                        <th class="center little">进度</th>
                                        <th class="center little">结果/反馈</th>
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
                                        <td class="center">
                                            <router-link :to="{path:pathName+'/checkWork/setting/'+task.task_id,query:{college_id}}" v-if="loginUser.user_level == key&&(task.sum>task.finished || !task.sum)">分配</router-link>
                                            <span v-if="loginUser.user_level != key || (task.sum==task.finished&&task.sum>0)">分配</span>
                                        </td>
                                        <td class="center">
                                            <router-link :to="{path:pathName+'/checkWork/progress/'+task.task_id,query:{college_id}}" v-if="task.sum > 0">检查进度</router-link>
                                            <span v-if="task.sum == 0">检查进度</span>
                                        </td>
                                        <td class="center">
                                            <router-link :to="{path:pathName+'/checkWork/result/'+task.task_id,query:{college_id}}" v-if="task.finished > 0">结果</router-link>
                                            <span v-if="task.finished == 0">查看结果</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <h5 v-if="task_list.lab.length==0&&task_list.college.length==0&&task_list.school.length==0&&college_id!=0" class="center red">
                    您还没有设置此期次的任务哦，如要设置
                    <router-link :to="pathName+'/checkPlan/'+plan_id">点我点我</router-link>
                    ！
                </h5>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    name: "checkWork",
    data() {
        return {
            title: "检查工作",
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
            }
        };
    },
    watch:{
        college_id(){
            this.getCurrentPlan();
            this.getCollege();
        },
        plan_id(){
            this.getCurrentPlan();
        }
    },
    methods: {
        init(){
            this.getPlanList();
            //学院用户
            if(this.permission[this.loginUser.user_level] == this.permission.college){
                this.college_id = this.loginUser.org_id;
            }
            this.getOrgList();
        },
        getPlanList(){
            //获取期次列表
            const _this = this;
            const URl = this.serverUrl + "/admin/plan/index";
            this.emitAjax(URl,null,function(result){
                _this.plan_list = result;
            });
        },
        getOrgList(){
            //获取单位信息
            const _this = this;
            const URl = this.serverUrl + "/admin/org/index";
            this.emitAjax(URl,null,function(result){
                if(_this.loginUser.user_level == "lab"){
                    _this.setLabCollegeId(result);
                }else{
                    _this.getCollegeList(result);
                }
            });  
        },
        setLabCollegeId(orgLIst){
            //实验室用户获取college_id
            let userOrgId = "";
            for (let index = 0; index < orgLIst.length; index++) {
                const org = orgLIst[index];
                if(this.loginUser.org_id == org.org_id){
                    this.college_id = org.pid;
                    break;
                }
            }
            this.getCollegeList(orgLIst);
        },
        getCollegeList(orgList){
            //获得院系列表，锁定院系名称
            this.college_list = [];
            for (let index = 0; index < orgList.length; index++) {
                const org = orgList[index];
                if(org.org_level == "college"){
                    this.college_list.push(Object.assign({},org));
                }
            }
            this.college_id = this.college_list[0]?this.college_list[0].org_id:0;
            this.getCollege();
        },
        getCollege(){
            //获得当前学院的名称
            for (let index = 0; index < this.college_list.length; index++) {
                const college = this.college_list[index];
                if(this.college_id == college.org_id){
                    this.college_name = college.org_name;
                    break;
                }
            }
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
            if(this.college_id!=0){
                this.emitAjax(URl,data,function(result){
                    _this.currentPlan = result.plan;
                    _this.setTaskType(result.check_list);
                });
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
            //过滤自查、复查、抽查
            switch (this.permission[this.loginUser.user_level]) {
                case this.permission.college:
                    this.task_list.school = [];
                    break;
                case this.permission.lab:
                    this.task_list.school = [];
                    this.task_list.college = [];
                    break;
                default:
                    break;
            }
        }
    },
    mounted() {
        this.init();
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