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
                            {{this.$store.state.checkPlan.plan_name}}
                        </h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <h5 class="text-center">
                                    共有自查{{checkWorkType[0].array.length}}次，复查{{checkWorkType[1].array.length}}次，抽查{{checkWorkType[2].array.length}}次。
                                    满分{{this.$store.state.checkWork.plan_score}}分
                                    <router-link to=''>工作说明</router-link>
                                </h5>
                            </div>
                        </div>
                        <div class="widget-box  widget-color-blue" v-for="(item,i) in checkWorkType" :key="'type'+i">
                            <div class="widget-header">
                                <h5>{{item.name}}</h5>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main no-padding table-responsive">
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
                                        <tbody>
                                            <tr v-for="(checkWork,index) in item.array" :key="'checkWork'+index">
                                                <td>{{checkWork.task_name}}</td>
                                                <td>{{checkWork.dt_begin}} 到 {{checkWork.dt_end}}</td>
                                                <td>{{checkWork.college_dt_begin}} 到 {{checkWork.college_dt_end}}</td>
                                                <td>
                                                    <span v-if="checkWork.check_state == 'pending'" class="red">未完成</span>
                                                    <span v-if="checkWork.check_state == 'finished'" class="green">完成</span>
                                                </td>
                                                <td>{{checkWork.check_score}}</td>
                                                <td>{{checkWork.problem_fatal}}/{{checkWork.problem_common}}</td>
                                                <td>
                                                    <span v-if="checkWork.review_state == 'no-need'">不需整改</span>
                                                    <span v-if="checkWork.review_state == 'no-start'">未通知</span>
                                                    <span v-if="checkWork.review_state == 'pending'">已通知</span>
                                                    <span v-if="checkWork.review_state == 'finished'">已反馈</span>
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
            checkWorkType:[{
                name:'自查',
                array:[],
            },{
                name:'复查',
                array:[],
            },{
                name:'抽查',
                array:[],
            }]
        };
    },
    computed: {
        checkWork() {
            return this.$store.state.checkWork;
        }
    },
    methods: {
        setCount() {
            //计划分类
            const _this = this;
            this.checkWorkType[0].array = [];
            this.checkWorkType[1].array = [];
            this.checkWorkType[2].array = [];
            const taskList = this.checkWork.task_list;
            if (taskList) {
                for (let index = 0; index < taskList.length; index++) {
                    const element = taskList[index];
                    switch (element.task_level) {
                        case "school":
                            _this.checkWorkType[0].array.push(Object.assign({}, element));
                            break;
                        case "college":
                            _this.checkWorkType[1].array.push(Object.assign({}, element));
                            break;
                        case "lab":
                            _this.checkWorkType[2].array.push(Object.assign({}, element));
                        default:
                            break;
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
        margin-bottom:10px;
    }
</style>