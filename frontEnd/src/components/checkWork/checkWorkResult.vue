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
                                <router-link :to="pathName+'/checkWork'">{{title}}</router-link>
                            </li>
                            <li>
                                <a class="active">设置检查工作</a>
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
                        <div class="clearfix position-relative">
                            <p class="pull-right">
                                <button class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">添加</button>
                                <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                                    <li v-for="lab in lab_list" :key="'lab'+lab.org_id" v-if="lab.org_state !='no'">
                                        <a @click="addLabSetting(lab)">{{lab.org_name}}</a>
                                    </li>
                                </ul>
                            </p>
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
import moment from "moment";
export default {
    name: "checkWork",
    components: { VueHead, VueLeft, datepicker },
    data() {
        return {
            title: "检查工作",
            addLab: false,
            currentPlan: {},
            currentTask: { dt_begin: "", dt_end: "" },
            college_info: {},
            lab_list: [],
            group_list: [],
            zone_list: [],
            room_list: [],
            today: Date.parse(new Date()) / 1000,
            momentObj: moment,
            check_list: [],
            newLabSetting: {}
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
        getCheckList(){
            //获取检查工作基础信息
            const _this = this;
            const URL = this.serverUrl + "/admin/check/allot";
            const data = {
                college_id: this.$route.query.college_id,
                task_id: this.$route.params.id,
            };
            this.emitAjax(URL, data, function(result) {
                _this.check_list = result;
            });
        },
        editCheckTask(checkTask) {
            //修改检查工作
            const _this = this;
            const URL = this.serverUrl + "/admin/check/handle";
            const today = moment().format("YYYY-MM-DD");
            checkTask.dt_begin = moment(checkTask.dt_begin).format(
                "YYYY-MM-DD"
            );
            checkTask.dt_end = moment(checkTask.dt_end).format("YYYY-MM-DD");

            this.emitAjax(URL, checkTask, function() {
                _this.getCheckList();
                alert("保存成功！");
            });
        },
    },
    mounted() {
        if (this.checkPermission(this)) {  
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