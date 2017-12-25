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
                        <div class="clearfix position-relative">
                            <p class="pull-right">
                                <button class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" v-if="loginUser.user_level == currentTask.task_level">添加</button>
                                <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                                    <li v-for="lab in lab_list" :key="'lab'+lab.org_id" v-if="lab.org_state !='no'">
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
                                <p v-if="today >= moment(check.dt_begin).unix()">
                                    本单位要求时间：
                                    <span >{{moment(check.dt_begin).format('YYYY-MM-DD')}}</span>
                                    到
                                    <span>{{moment(check.dt_end).format('YYYY-MM-DD')}}</span>
                                </p>
                                <p v-if="today < moment(check.dt_begin).unix()">
                                    本单位要求时间：
                                    <datepicker v-model="check.dt_begin" width="140" 
                                        :not-before="currentTask.dt_begin" :not-after="currentTask.dt_end"
                                    ></datepicker>
                                    到
                                    <datepicker v-model="check.dt_end" width="140" 
                                        :not-before="currentTask.dt_begin" :not-after="currentTask.dt_end"
                                    ></datepicker>
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
                                                        分组名称：{{zone.zone_name}}
                                                    </h5>
                                                    分组下的房间：
                                                    <span v-for="room in room_list" :key="'room'+room.room_id" v-if="room.zone_id == zone.zone_id">
                                                        （{{room.room_name}}）
                                                    </span>
                                                </td>
                                                <td>
                                                    <!-- 循环本实验室所属房间分组列表，跟检查安排的房间分组匹配，匹配上了就利用group_id回显检查小组信息 -->
                                                    <select :value="check.zone_list[zone.zone_id]?check.zone_list[zone.zone_id].group_id:0" @change="setGroupId(check,zone.zone_id,$event)">
                                                        <option value="0">--请选择--</option>
                                                        <option :value="group.group_id" 
                                                            v-for="group in group_list" 
                                                            :key="'group'+group.group_id"
                                                            v-if="group.org_id == loginUser.org_id"
                                                        >{{group.group_name}}</option>
                                                    </select>
                                                    <br>
                                                    <span v-for="group in group_list" :key="'group'+group.group_id" v-if="check.zone_list[zone.zone_id]&&check.zone_list[zone.zone_id].group_id==group.group_id">
                                                        组长：{{group.leader_name}} 组员：{{group.members}}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-if="loginUser.user_level == currentTask.task_level">
                                                <td colspan="2" class="center">
                                                    <button class="btn btn-success btn-sm" @click="editCheckTask(check)" v-if="today < moment(check.dt_begin).unix()">保存</button>
                                                    <router-link class="btn btn-default btn-sm" 
                                                        v-if="check.check_state != 'finished'&&today > moment(check.dt_begin).unix()&&today < moment(check.dt_end).unix()" 
                                                        :to="{path:pathName+'/checkWork/progress/'+$route.params.id,query:{college_id:$route.query.college_id}}"
                                                    >查看进度</router-link>
                                                    <router-link class="btn btn-default btn-sm" 
                                                        v-if="check.check_state == 'finished' || today > moment(check.dt_end).unix()"
                                                        :to="{path:pathName+'/checkWork/result/'+$route.params.id,query:{college_id:$route.query.college_id}}"
                                                    >查看结果</router-link>
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
                                            <!-- 循环本实验室所属房间分组列表， -->
                                            <tr v-for="zone in zone_list" :key="'zone'+zone.zone_id" v-if="zone.org_id == newLabSetting.org_id">
                                                <td>
                                                    <h5>
                                                        分组名称：{{zone.zone_name}}
                                                    </h5>
                                                    分组下的房间：
                                                    <span v-for="room in room_list" :key="'room'+room.room_id" v-if="room.zone_id == zone.zone_id">
                                                       （{{room.room_name}}）
                                                    </span>
                                                </td>
                                                <td>
                                                    <!-- 循环本实验室所属房间分组列表，跟检查安排的房间分组匹配，匹配上了就利用group_id回显检查小组信息 -->
                                                    <select :value="newLabSetting.zone_list[zone.zone_id]?newLabSetting.zone_list[zone.zone_id].group_id:0" @change="setGroupId(newLabSetting,zone.zone_id,$event,true)">
                                                        <option value="0">--请选择--</option>
                                                        <option :value="group.group_id" 
                                                            v-for="group in group_list" 
                                                            :key="'group'+group.group_id"
                                                            v-if="group.org_id == loginUser.org_id"
                                                        >{{group.group_name}}</option>
                                                    </select>
                                                    <br>
                                                    <span v-for="group in group_list" :key="'group'+group.group_id" v-if="newLabSetting.zone_list[zone.zone_id]&&newLabSetting.zone_list[zone.zone_id].group_id==group.group_id">
                                                        组长：{{group.leader_name}} 组员：{{group.members}}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-if="loginUser.user_level == currentTask.task_level">
                                                <td colspan="2" class="center">
                                                    <button class="btn btn-success btn-sm" @click="saveCheckTask">添加</button>
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
            title: "分配检查工作",
            addLab: false,
            currentPlan: {},
            currentTask: { dt_begin: "", dt_end: "" },
            college_info: {},
            lab_list: [],
            group_list: [],
            zone_list: [],
            room_list: [],
            check_list: [],
            newLabSetting: {}
        };
    },
    methods: {
        getCheckInfo() {
            //获取信息 flag为true不查询基础信息只查询检查工作列表
            const _this = this;
            const URL = this.serverUrl + "/admin/check/baseinfo";
            const data = {
                college_id: this.$route.query.college_id,
                task_id: this.$route.params.id,
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
        setGroupId(checkObj,zone_id,event,istrue){
            //给房间分组分配检查小组
            checkObj.zone_list[zone_id] = {
                group_id:event.target.value,
                zone_id
            };
            if(istrue){
                for (let index = 0; index < this.group_list.length; index++) {
                    const group = this.group_list[index];
                    if(event.target.value == group.group_id){
                        const text = '组长：'+group.leader_name+' 组员：'+group.members;
                        if($(event.target).siblings("span").length>0){
                            $(event.target).siblings("span").html(text);
                        }else{
                            $(event.target).parent().append("<span>"+text+"</span>");
                        }
                    }
                    if(event.target.value == 0){
                        $(event.target).siblings("span").html("");
                    }
                }
            }
        },
        editCheckTask(checkTask) {
            console.log(checkTask)
            //修改检查工作
            const _this = this;
            const URL = this.serverUrl + "/admin/check/handle";
            const today = this.moment().format("YYYY-MM-DD");
            checkTask.dt_begin = this.moment(checkTask.dt_begin).format(
                "YYYY-MM-DD"
            );
            checkTask.dt_end = this.moment(checkTask.dt_end).format("YYYY-MM-DD");

            this.emitAjax(URL, checkTask, function() {
                alert("保存成功！");
                _this.getCheckInfo(true);
            });
        },
        addLabSetting(lab) {
            //分配检察工作 判断 有没有分配过的实验室
            for (let index = 0; index < this.check_list.length; index++) {
                const check = this.check_list[index];
                if (check.org_id == lab.org_id) {
                    alert("该实验室已分配了任务，直接修改即可！");
                    return false;
                }
            }
            this.newLabSetting = {
                org_id: lab.org_id,
                org_name: lab.org_name,
                check_level: this.currentTask.task_level,
                task_id: this.$route.params.id,
                zone_list: {},
                dt_begin: this.currentTask.dt_begin,
                dt_end: this.currentTask.dt_end
            };
            this.addLab = true;
            for (let index = 0; index < this.zone_list.length; index++) {
                const zone = this.zone_list[index];
                if (lab.org_id == zone.org_id) {
                    this.newLabSetting.zone_list[zone.zone_id] = {
                        zone_id: zone.zone_id,
                        group_id: 0
                    };
                }
            }
        },
        saveCheckTask() {
            //分配检察工作
            const _this = this;
            const URL = this.serverUrl + "/admin/check/handle";
            if (this.newLabSetting.dt_begin == "") {
                alert("请选择开始时间！");
                return false;
            }
            if (this.newLabSetting.dt_end == "") {
                alert("请选择结束时间！");
                return false;
            }
            this.newLabSetting.dt_begin = this.moment(
                this.newLabSetting.dt_begin
            ).format("YYYY-MM-DD");
            this.newLabSetting.dt_end = this.moment(
                this.newLabSetting.dt_end
            ).format("YYYY-MM-DD");
            this.emitAjax(URL, this.newLabSetting, function() {
                _this.getCheckList();
                _this.addLab = false;
            });
        }
    },
    mounted() {
        if (this.checkPermission(this)) {
            this.getCheckInfo();
            this.getCheckList();
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