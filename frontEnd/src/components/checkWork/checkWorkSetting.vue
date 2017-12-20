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
                                    <router-link to=''>工作说明</router-link>
                                </h5>
                            </div>
                        </div>
                        <div class="clearfix position-relative">
                            <p class="pull-right">
                                <button class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">添加</button>
                                <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                                    <li v-for="lab in lab_list" :key="'lab'+lab.org_id">
                                        <a @click="addLabSetting(lab)">{{lab.org_name}}</a>
                                    </li>
                                </ul>
                            </p>
                        </div>
                        <div class="widget-box widget-color-blue ui-sortable-handle" v-for="check in check_list" :key="'check'+check.org_id">
                            <div class="widget-header">
                                <h5 class="widget-title bigger lighter">
                                    {{check.org_name}}
                                </h5>
                                <p>
                                    本单位要求时间：
                                    <datepicker v-model="check.dt_begin" width="140" :not-before="currentTask.dt_begin" :not-after="currentTask.dt_end"></datepicker>
                                    -
                                    <datepicker v-model="check.dt_end" width="140" :not-before="currentTask.dt_begin" :not-after="currentTask.dt_end"></datepicker>
                                </p>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main no-padding">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="50%">房间小组</th>
                                                <th width="50%">分配给</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- 循环本实验室所属房间分组列表， -->
                                            <tr v-for="zone in zone_list" :key="'zone'+zone.zone_id" v-if="zone.org_id == check.org_id">
                                                <td>
                                                    <h5>
                                                        {{zone.zone_name}}
                                                    </h5>
                                                    <span v-for="room in room_list" :key="'room'+room.room_id" v-if="room.zone_id == zone.zone_id">
                                                        {{room.room_name}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <!-- 循环本实验室所属房间分组列表，跟检查安排的房间分组匹配，匹配上了就利用group_id回显检查小组信息 -->
                                                    <div v-for="checkZone in check.zone_list" :key="'checkZone'+checkZone.zone_id" v-if="checkZone.zone_id == zone.zone_id">
                                                        <select v-model="checkZone.group_id">
                                                            <option value="0">选择检查小组</option>
                                                            <option :value="group.group_id" 
                                                                v-for="group in group_list" 
                                                                :key="'group'+group.group_id"
                                                                v-if="check.org_id == group.org_id"
                                                            >{{group.group_name}}</option>
                                                        </select>
                                                        <br>
                                                        <span v-for="group in group_list" :key="'group'+group.group_id" v-if="checkZone.group_id==group.group_id">
                                                            组长：{{group.leader_name}} 组员：{{group.members}}
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="center">
                                                    <button class="btn btn-success btn-sm" @click="editCheckTask(check)">保存设置</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </div>
                            </div>
                        </div>
                        <div class="widget-box widget-color-blue ui-sortable-handle" v-if="addLab">
                            <div class="widget-header">
                                <h5 class="widget-title bigger lighter">
                                    {{newLabSetting.org_name}}
                                </h5>
                                <p>
                                    本单位要求时间：
                                    <datepicker v-model="newLabSetting.dt_begin" width="140" :not-before="currentTask.dt_begin" :not-after="currentTask.dt_end"></datepicker>
                                    -
                                    <datepicker v-model="newLabSetting.dt_end" width="140" :not-before="currentTask.dt_begin" :not-after="currentTask.dt_end"></datepicker>
                                </p>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main no-padding">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="50%">房间小组</th>
                                                <th width="50%">分配给</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <tr v-for="zone in newLabSetting.zone_list" :key="'zone'+zone.zone_id">
                                                <td>
                                                    <div>
                                                        <h5>
                                                            {{zone.zone_name}}
                                                        </h5>
                                                        <span v-for="room in zone.room_list" :key="'room'+room.room_id">
                                                            {{room.room_name}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <select name="" id="" v-model="zone.group_id" @click="changeGroup(zone)">
                                                            <option value="0">选择检查小组</option>
                                                            <option :value="group.group_id" 
                                                                v-for="group in group_list" 
                                                                :key="'group'+group.group_id"
                                                                v-if="zone.org_id == group.org_id"
                                                            >{{group.group_name}}</option>
                                                        </select>
                                                    </div>
                                                    <span>组长：{{zone.leader_name}} 组员：{{zone.members}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="center">
                                                    <button class="btn btn-success btn-sm">保存设置</button>
                                                    <button class="btn btn-default btn-sm" @click="addLab = false">取消</button>
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
import datepicker from "vue2-datepicker";
export default {
    name: "checkWork",
    components: { VueHead, VueLeft, datepicker },
    data() {
        return {
            title: "检查工作",
            addLab:false,
            currentPlan: {},
            currentTask: {
                dt_begin: "",
                dt_end: ""
            },
            college_info:{},
            lab_list: [],
            group_list:[],
            zone_list:[],
            room_list:[],
            check_list: [
                {
                    org_id: 17,
                    org_name: "化工系实验室",
                    dt_begin: "2017-12-06 10:00:00",
                    dt_end: "2017-12-12 10:00:00",
                    zone_list: [
                        {
                            zone_id: 1,
                            zone_name: "分组1",
                            group_id: 0,
                            group_name:"检查小组",
                            leader_id: "1000",
                            leader_name: "组长名称",
                            members: "组员，组员"
                        }
                    ]
                }
            ],
            newLabSetting:{
                org_id: 0,
                org_name: "",
                dt_begin: "",
                dt_end: "",
                zone_list: []
            }
        };
    },
    methods: {
        getCheckInfo(){
            //获取信息
            const _this = this;
            const URL = this.serverUrl +"/admin/check/allot";
            const data = {
                college_id:this.$route.query.college_id,
                task_id:this.$route.params.id
            }
            this.emitAjax(URL,data,function(result){
                console.log(result)
                _this.currentPlan = result.plan;
                _this.currentTask = result.task;
                _this.lab_list = result.org_list;
                _this.group_list = result.group_list;
                _this.zone_list = result.zone_list;
                _this.room_list = result.room_list;
                _this.check_list = result.check_list;
                _this.college_info = result.college_info;
            })
        },
        addLabSetting(lab){
            //判断 有没有分配过的实验室
            for (let index = 0; index < this.check_list.length; index++) {
                const check = this.check_list[index];
                if(check.org_id == lab.org_id){
                    alert("该实验室已分配了任务，直接修改即可！")
                    return false;
                }
            }
            this.newLabSetting.org_id = lab.org_id;
            this.newLabSetting.org_name = lab.org_name;
            this.newLabSetting.zone_list=[];
            this.addLab = true;
            for (let index = 0; index < this.zone_list.length; index++) {
                const zone = this.zone_list[index];
                if(lab.org_id == zone.org_id){
                    this.newLabSetting.zone_list.push({
                        zone_id: zone.zone_id,
                        zone_name:zone.zone_name,
                        group_id: 0,
                    })
                }
            }
        },
        editCheckTask(checkTask){
            console.log(checkTask);
        }
    },
    mounted() {
        if (this.checkPermission(this)) {
            this.getCheckInfo();
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