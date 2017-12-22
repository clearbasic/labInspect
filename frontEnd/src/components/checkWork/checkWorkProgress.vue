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
                                <router-link :to="pathName+'/checkWork'">检查工作</router-link>
                            </li>
                            <li>
                                <a class="active">{{title}}</a>
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
                            {{college_info.org_name}} - 
                            {{currentPlan.plan_name}} 
                            {{currentTask.task_name}}
                            <span v-if="currentTask.task_level === 'lab'">自查</span>
                            <span v-if="currentTask.task_level === 'college'">复查</span>
                            <span v-if="currentTask.task_level === 'school'">抽查</span>
                        </h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <h5 class="text-center">
                                    学校要求时间{{currentTask.dt_begin.substring(0,10)}} 到 {{currentTask.dt_end.substring(0,10)}}
                                    满分{{currentPlan.plan_score}}
                                    <router-link :to="{path:pathName+'/checkPlanSummary',query:{plan_id:currentPlan.plan_id}}">工作说明</router-link>
                                </h5>
                            </div>
                        </div>
                        <div class="widget-box widget-color-blue ui-sortable-handle" v-for="check in progress_list" :key="'check'+check.org_id">
                            <div class="widget-header">
                                <h5 class="widget-title bigger lighter">
                                    {{check.org_name}}
                                </h5>
                                <p>
                                    本单位要求时间：
                                    <span>{{check.dt_begin.substring(0.10)}}</span>
                                    到
                                    <span>{{check.dt_begin.substring(0.10)}}</span>
                                </p>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main no-padding">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>检查小组</th>
                                                <th>小组组长</th>
                                                <th>组长电话</th>
                                                <th class="center">检查进度</th>
                                                <th class="center">已完成房间</th>
                                                <th class="center">未完成房间</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- 循环本实验室所属检查小组列表 -->
                                            <tr v-for="group in check.group_list" :key="'group'+group.group_id">
                                                <td>{{group.group_name}}</td>
                                                <td>{{group.leader_name}}</td>
                                                <td>{{group.phone}}</td>
                                                <td class="center">{{group.progress}}</td>
                                                <td class="center">{{group.finished}}</td>
                                                <td class="center">{{group.sum - group.finished}}</td>
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
import datepicker from "vue2-datepicker";
export default {
    name: "checkWork",
    components: { VueHead, VueLeft, datepicker },
    data() {
        return {
            title: "检查工作进度",
            addLab: false,
            currentPlan: {},
            currentTask: { dt_begin: "", dt_end: "" },
            college_info: {},
            lab_list: [],
            group_list: [],
            zone_list: [],
            room_list: [],
            progress_list:[]
        };
    },
    methods: {
        getCheckInfo() {
            //获取检查工作基础信息
            const _this = this;
            const URL = this.serverUrl + "/admin/check/baseinfo";
            const data = {
                college_id: this.$route.query.college_id,
                task_id: this.$route.params.id
            };
            this.emitAjax(URL, data, function(result) {
                _this.currentPlan = result.plan;
                _this.currentTask = result.task;
                _this.lab_list = result.org_list;
                _this.group_list = result.group_list;
                _this.zone_list = result.zone_list;
                _this.room_list = result.room_list;
                _this.college_info = result.college_info;
            });
        },
        getProgressList(){
            const _this = this;
            const URL = this.serverUrl +"/admin/check/progress";
            const data = {
                college_id: this.$route.query.college_id,
                task_id: this.$route.params.id
            }
            this.emitAjax(URL,data,function(result){
                console.log(result)
                _this.progress_list = result;
            })
        }
    },
    mounted() {
        if (this.checkPermission(this)) {  
            this.getCheckInfo();
            this.getProgressList();
        }
    }
};
</script>
<style>
.widget-box {
    margin-bottom: 20px;
}
.yellow {
    color: #e4c523;
}
</style>