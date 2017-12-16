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
                            </h1>
                        </div>
                        <h3 class="text-center" style="margin-top:10px;">
                            {{currentPlan.plan_name}}
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
                        <div class="widget-box  widget-color-blue" v-for="(tasks,key) in task_list" :key="'taskItem'+key">
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
                                                <th>单位要求时间</th>
                                                <th>开展情况</th>
                                                <th>评分</th>
                                                <th>问题数量</th>
                                                <th>反馈状态</th>
                                            </tr>
                                        </thead>
                                        <!-- 循环每个实验室的具体任务 -->
                                        <tbody>
                                            <tr v-for="(task,index) in tasks" :key="'checkWork'+task.check_id">
                                                <td class="center little">{{task.task_name}}</td>
                                                <td>{{task.dt_begin.substring(0,10)}} 到 {{task.dt_end.substring(0,10)}}</td>
                                                <td>
                                                    <span v-if="task.college_dt_begin">{{task.college_dt_begin}} 到 {{task.college_dt_end}}</span>
                                                    <span v-if="!task.college_dt_begin">未设置</span>
                                                </td>
                                                <td>
                                                    <router-link :to="{path:pathName+'/checkWork/setting',query:{type:key,check_id:task.check_id,org_id:checkWorkItem.org.org_id}}" v-if="task.check_state == 'no-start'"><span  class="red">未开始</span></router-link>
                                                    <router-link :to="{path:pathName+'/checkWork/pending',query:{type:key,check_id:task.check_id,org_id:checkWorkItem.org.org_id}}" v-if="task.check_state == 'pending'"><span class="yellow">进行中</span></router-link>
                                                    <router-link :to="{path:pathName+'/checkWork/finished',query:{type:key,check_id:task.check_id,org_id:checkWorkItem.org.org_id}}" v-if="task.check_state == 'finished'"><span class="green">已完成</span></router-link>
                                                </td>
                                                <td>{{task.check_score}}</td>
                                                <td>{{task.problem_fatal?task.problem_fatal:0}}/{{task.problem_common?task.problem_common:0}}</td>
                                                <td>
                                                    <span v-if="task.review_state == 'no-need'">不需整改</span>
                                                    <span v-if="task.review_state == 'no-start'">未通知</span>
                                                    <span v-if="task.review_state == 'pending'">已通知</span>
                                                    <span v-if="task.review_state == 'finished'">已反馈</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
            checkPlan:[],
            currentPlan:{},
            task_list:{
                lab:[],
                college:[],
                school:[],
            },
        };
    },
    computed: {
        checkWork() {
            return this.$store.state.checkWork;
        }
    },
    methods: {
        getCurrentPlan(){
            //获取当前期次信息
            const _this = this;
            const URl = this.serverUrl + "/admin/plan/index";
            this.emitAjax(URl,{current:1},function(result){
                _this.currentPlan = result;
                console.log(result.plan_id)
                _this.getTaskList(result.plan_id);
            });
        },
        getTaskList(plan_id){
            //获取期次任务列表
            const _this = this;
            const URl = this.serverUrl + "/admin/task/index";
            this.emitAjax(URl,{plan_id},function(taskList){
                console.log(taskList)
                _this.setTaskType(taskList);
            });
        },
        setTaskType(taskList){
            //期次任务分类
            const _this = this;
            for (let index = 0; index < taskList.length; index++) {
                const task = taskList[index];
                _this.task_list[task.task_level].push(Object.assign({},task));
            }
        }
    },
    mounted() {
        if(this.checkPermission(this)){
            this.getCurrentPlan();
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