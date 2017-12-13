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
                        <div class="accordion-style1 panel-group" id="accordion">
                            <!-- 循环每个实验室 -->
                            <div class="panel panel-default" v-for="(checkWorkItem,i) in checkWork.task_list" :key="'checkWork'+checkWorkItem.org.org_id">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a :href="'#collapse'+checkWorkItem.org.org_id" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                            <i class="bigger-110 ace-icon fa fa-angle-right"
                                                data-icon-hide="ace-icon fa fa-angle-down"
                                                data-icon-show="ace-icon fa fa-angle-right"
                                            ></i>
                                            {{checkWorkItem.org.org_name}}
                                        </a>
                                    </h4>
                                </div>
                                <div :class="['panel-collapse','collapse',{in:i==0}]" :id="'collapse'+checkWorkItem.org.org_id" >
                                    <div class="panel-body">
                                        <h3 class="text-center" style="margin-top:10px;">
                                            {{checkWork.plan.plan_name}}
                                        </h3>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h5 class="text-center">
                                                    共有自查{{checkWorkItem.tasks.lab.length}}次，复查{{checkWorkItem.tasks.college.length}}次，抽查{{checkWorkItem.tasks.school.length}}次。
                                                    满分{{checkWork.plan.plan_score}}分
                                                    <router-link to=''>工作说明</router-link>
                                                </h5>
                                            </div>
                                        </div>
                                        <!-- 循环实验室三种任务 -->
                                        <div class="widget-box  widget-color-blue" v-for="(tasks,key) in checkWorkItem.tasks" :key="'taskItem'+key">
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
                                                                <th>任务</th>
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
                                                                <td>{{task.task_name}}</td>
                                                                <td>{{task.dt_begin}} 到 {{task.dt_end}}</td>
                                                                <td>{{task.college_dt_begin}} 到 {{task.college_dt_end}}</td>
                                                                <td>
                                                                    <router-link :to="{path:pathName+'/checkWork/setting',query:{type:key,check_id:task.check_id,org_id:checkWorkItem.org.org_id}}" v-if="task.check_state == 'no-start'"><span  class="red">未开始</span></router-link>
                                                                    <router-link :to="{path:pathName+'/checkWork/pending',query:{type:key,check_id:task.check_id,org_id:checkWorkItem.org.org_id}}" v-if="task.check_state == 'pending'"><span class="yellow">进行中</span></router-link>
                                                                    <router-link :to="{path:pathName+'/checkWork/finished',query:{type:key,check_id:task.check_id,org_id:checkWorkItem.org.org_id}}" v-if="task.check_state == 'finished'"><span class="green">已完成</span></router-link>
                                                                </td>
                                                                <td>{{task.check_score}}</td>
                                                                <td>{{task.problem_fatal}}/{{task.problem_common}}</td>
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
        };
    },
    computed: {
        checkWork() {
            return this.$store.state.checkWork;
        }
    },
    methods: {
        
    },
    mounted() {
        //获取检测期次信息
        this.$store.dispatch("getCheckWork");
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