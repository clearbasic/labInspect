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
                                <router-link :to="pathName+'/checkWork'">检查工作列表</router-link>
                            </li>
                            <li>
                                <router-link to="" class="active">{{title}}</router-link>
                            </li>
                        </ul>
                    </div>
                    <!-- 左侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{title}}
                                <div class="pull-right">
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#groupModal">添加</button>
                                </div>
                            </h1>
                        </div>
                        <h3 class="text-center">
                            {{checkWork.plan&&checkWork.plan.plan_name}} {{checkWorkTask&&checkWorkTask.task_name}} 
                            <span v-if="checkWorkTask&&checkWorkTask.task_level === 'school'">自查</span>
                            <span v-if="checkWorkTask&&checkWorkTask.task_level === 'college'">复查</span>
                            <span v-if="checkWorkTask&&checkWorkTask.task_level === 'lab'">抽查</span>
                        </h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <h5 class="text-center">
                                    学校要求时间：{{checkWorkTask&&checkWorkTask.dt_begin}}到{{checkWorkTask&&checkWorkTask.dt_end}}
                                    满分{{checkWork.plan_score}}分
                                    <router-link to=''>工作说明</router-link>
                                </h5>
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
            title: "检查安排",
            checkWorkTask:null
        };
    },
    computed: {
        checkWork() {
            return this.$store.state.checkWork;
        }
    },
    methods: {
        setCount() {
            //设置当前任务对象
            const _this = this;
            const taskList = this.checkWork.task_list;
            if (taskList) {
                for (let index = 0; index < taskList.length; index++) {
                    
                    const element = taskList[index];

                    if(element.org.org_id === _this.$route.query.org_id){
                        let tasks = null;
                        if(_this.$route.query.type == "school"){
                            tasks = element.tasks.school;
                        }else if(_this.$route.query.type == "college"){
                            tasks = element.tasks.college;
                        }else if(_this.$route.query.type == "lab"){
                            tasks = element.tasks.lab;
                        }
                        console.log(tasks)
                        for (let i = 0; i < tasks.length; i++) {
                            const task = tasks[i];
                            if(task.check_id == _this.$route.query.check_id){
                                _this.checkWorkTask = Object.assign({},task);
                            }
                        }
                    }
                    
                }
            }
        }
    },
    watch: {
        checkWork: function() {
            this.setCount();
        }
    },
    mounted() {
        //获取检测期次信息
        this.$store.dispatch("setCheckWork");
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