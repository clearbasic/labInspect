<template>
	<div class="checkList">
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
                                <router-link :to="pathName+'/myCheck'" class="active">{{title}}</router-link>
                            </li>
                        </ul>
                    </div>
                    <!-- 右侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                <span v-if="templateType == 'item'">{{currentRoom.room_name}}房间</span>
                                {{currentPlan.plan_name}}
                                <span v-if="templateType != 'task'">
                                    {{currentTask.task_name}}
                                    <span v-if="currentTask.task_level=='lab'">自查</span>
                                    <span v-if="currentTask.task_level=='college'">复查</span>
                                    <span v-if="currentTask.task_level=='school'">抽查</span>
                                </span>
                                <div class="pull-right" v-if="templateType !='task'">
                                    <button class="btn btn-default btn-sm" @click="templateType='task'" v-if="templateType=='room'">上一页</button>
                                    <button class="btn btn-default btn-sm" @click="templateType='room'" v-if="templateType=='item'">上一页</button>
                                </div>
                            </h1>
                        </div>
                        <!-- 检查安排列表 -->
                        <table class="table table-bordered table-striped table-hover" v-if="templateType=='task'">
                            <thead>
                                <tr>
                                    <th class="center little">检查安排</th>
                                    <th>单位要求时间</th>
                                    <th class="center little">检查类型</th>
                                    <th class="center little">开展情况</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="taskCheck in task_list" :key="'taskCheck'+taskCheck.check_id">
                                    <td class="center little">{{taskCheck.task_name}}</td>
                                    <td>{{taskCheck.dt_begin.substring(0,10)}} 到 {{taskCheck.dt_end.substring(0,10)}}</td>
                                    <td class="center little" >
                                        <span v-if="taskCheck.task_level == 'lab'">自查</span>
                                        <span v-if="taskCheck.task_level == 'college'">复查</span>
                                        <span v-if="taskCheck.task_level == 'school'">抽查</span>
                                    </td>
                                    <td class="center little">
                                        <span v-if="today < moment(taskCheck.dt_begin).unix()">未开展</span>
                                        <a v-if="today >= moment(taskCheck.dt_begin).unix()&&today <= moment(taskCheck.dt_end).unix()"
                                            @click="showRoomList(taskCheck)"
                                        >检查中</a>
                                        <span v-if="today > moment(taskCheck.dt_end).unix()">已结束</span>
                                    </td>
                                </tr>
                                <tr v-if="task_list.length == 0">
                                    <td colspan="5" class="center">本期次没有安排给您检查工作！</td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- 具体检查安排下的房间列表 -->
                        <table v-if="templateType=='room'" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>房间名</th>
                                    <th>所属院系</th>
                                    <th>所属实验室</th>
                                    <th class="center little">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="room in currentTask.room_list" :key="'room'+room.effort_id">
                                    <td>
                                        <a @click="showCheckItem(room)">{{room.room_name}}</a>
                                    </td>
                                    <td>{{room.dept_name}}</td>
                                    <td>{{room.lab_name}}</td>
                                    <td class="center little">
                                        <a @click="showCheckItem(room)">
                                            <span v-if="room.effort_state != 'finished'">开展工作</span>
                                            <span v-if="room.effort_state == 'finished'">已提交</span>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- 具体房间下面的指标列表 -->
                        <div class="checkItemList" v-if="templateType=='item'">
                            <p><span class="red">*</span> 为一票否决，该指标不合格，该房间分数即为0，所有指标默认为合格</p>
                            <p v-if="ruleList.length==0" class="center">没有查到对应的指标</p>
                            <div class="checkList" v-for="checkList in ruleList" :key="'checkList'+checkList.id" v-if="ruleList.length>0">
                                <ul class="list-group">
                                    <li class="list-group-item active">
                                        {{checkList.name}}（{{checkList.score}}分）
                                    </li>
                                    <li class="list-group-item" v-for="item in checkList.checkList" :key="'item'+item.item_id">
                                        <div class="radio nomargin" v-if="item.item_type == 'common'">
                                            <span class="red" v-if="item.item_level=='fatal'">*</span>  {{item.item_name}}
                                            <label>
                                                <input type="radio" class="ace" v-model="item.grade" value="yes">
                                                <span class="lbl">合格</span>
                                            </label>
                                            <label>
                                                <input type="radio" class="ace" v-model="item.grade" value="no">
                                                <span class="lbl">不合格</span>
                                            </label>
                                            <label>
                                                <input type="radio" class="ace" v-model="item.grade" value="NA">
                                                <span class="lbl">不适用</span>
                                            </label>
                                            <span v-if="item.grade !='yes'">问题描述：<input type="text" v-model="item.intro" ></span>
                                        </div>
                                        <h5 v-if="item.item_type != 'common'" class="nomargin bolder">{{item.item_name}}</h5>
                                    </li>
                                </ul>
                            </div>
                            <button class="btn btn-success btn-sm" @click="submitRoomData(ruleList,'submit')" v-if="currentRoom.effort_state !='finished'">提交检查</button>
                            <button class="btn btn-primary btn-sm" @click="submitRoomData(ruleList,'save')" v-if="currentRoom.effort_state !='finished'">保存草稿</button>
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
import moment from "moment";

export default {
    name: "myCheck",
    components: {
        VueHead,
        VueLeft
    },
    data() {
        return {
            title: "我的检查工作",
            templateType:"task",
            currentPlan:{},
            task_list:[],
            currentTask:{},//点击的哪次安排
            currentRoom:{},
            ruleList:[],//存放房间的指标库
            moment:moment,
            today:Date.parse(new Date())/1000,
        };
    },
    methods: {
        getMyCheckList(){
            //获得用户检查工作列表
            const _this = this;
            const URL = this.serverUrl+'/admin/check/mycheck'
            this.emitAjax(URL,null,function(result){
                _this.task_list = result.task_list?result.task_list:[];
                _this.currentPlan = result.plan?result.plan:{};
            })
        },
        showRoomList(taskCheck){
            //查看哪个房间
            this.templateType = "room"
            this.currentTask = taskCheck;
        },
        showCheckItem(room){
            //进入提交表单页面
            const _this = this;
            const URL = this.serverUrl + "/admin/check/startcheck";
            const data = {
                dept_id:room.dept_id,
                lab_id:room.lab_id,
                task_level:this.currentTask.task_level,
                plan_id:this.currentPlan.plan_id,
                check_id:room.check_id,
                room_id:room.room_id,
            }
            this.currentRoom = room;
            this.emitAjax(URL,data,function(result){
                _this.templateType = "item";
                _this.ruleList = result?result:[];
            })
        },
        submitRoomData(ruleList,flag){
            //提交 暂存
            let score = 0; //总分
            let fatal = 0;
            for (let index = 0; index < ruleList.length; index++) {
                //循环每个指标库
                const checkList = ruleList[index];
                let totalCount = 0; //有效的指标总数
                let count = 0; //合格的总数
               
                for (let i = 0; i < checkList.checkList.length; i++) {
                    //循环每个指标
                    const item = checkList.checkList[i];
                    //剔除小标题指标和不符合的指标
                    if(item.item_type == 'common' && item.grade != "NA"){
                        totalCount++;
                        if(item.grade == 'yes'){
                            count++;
                        }else{
                            if (item.item_level == 'fatal') {
                                fatal++;
                            }
                        }
                    }
                }
                if(totalCount>0){
                    score += (parseFloat(checkList.score)/totalCount)*count;
                }else{
                    score += parseFloat(checkList.score);
                }
            }
            //如果有一票否决指标不合格数量大于0，总分为0
            score = fatal==0 ?parseFloat(score.toFixed(2)):0;
            console.log(score)
            const data = {
                score:score,
                checkList:ruleList,
                flag,
                check_id:this.currentRoom.check_id,
                room_id:this.currentRoom.room_id,
                effort_id:this.currentRoom.effort_id
            }
            //暂存 或者 提交时要确认才能执行
            if(flag=='save' || (flag=="submit" && confirm("房间最后得分为"+score+"分，是否确认提交？"))){
                const _this = this;
                const URL = this.serverUrl + "/admin/check/submitcheck";
                this.emitAjax(URL,data,function(){
                    if(flag=="submit"){
                        _this.resetEffortState();
                    }
                    _this.templateType = "room";
                })
            }
        },
        resetEffortState(){
            const room_list = this.currentTask.room_list;
            for (let index = 0; index < room_list.length; index++) {
                const element = room_list[index];
                if(this.currentRoom.effort_id == element.effort_id){
                    element.effort_state = "finished";
                }
            }
        }
    },
    mounted() {
        if(this.checkPermission(this)){
            this.getMyCheckList();
        }
    }
};
</script>
<style>
.nomargin {
    margin:0;
}
</style>

