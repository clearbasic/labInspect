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
                        <router-link :to="pathName+'/checkStatistics'">检查统计表</router-link>
                    </li>
                    <li>
                        <a class="active">{{resultObj.org_name}}{{title}}</a>
                    </li>
                </ul>
            </div>
            <!-- 右侧主要内容 -->
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{resultObj.org_name}}{{title}}
                    </h1>
                </div>
                <h3 class="text-center" style="margin-top:10px;">
                    {{resultObj.org_name}}
                </h3>
                <div class="accordion-style1 panel-group" id="accordion">
                    <!-- 按实验室工作循环列表 -->
                    <div class="panel panel-default" v-for="(check,index) in resultObj.check" :key="'check'+check.check_id">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a :href="'#panelOrg'+index" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" aria-expanded="false">
                                    <i :class="['bigger-110 ace-icon fa',{'fa-angle-down':index==0},{'fa-angle-right':index!=0}]" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                                    {{check.task_name}}
                                    <span v-if="check.check_level === 'lab'">自查</span>
                                    <span v-if="check.check_level === 'college'">复查</span>
                                    <span v-if="check.check_level === 'school'">抽查</span>
                                </a>
                            </h4>
                        </div>
                        <div :class="['panel-collapse collapse',{in:index==0}]" :id="'panelOrg'+index" aria-expanded="false">
                            <div class="panel-body">
                                <p>开展时间：{{check.dt_begin.substring(0,10)}}到{{check.dt_end.substring(0,10)}}
                                    得分：{{check.check_score}}
                                    一般问题数量：{{check.problem_common}}
                                    一票否决数量：{{check.problem_fatal}}</p>
                                <div class="problem" v-if="check.problem_list">
                                    <div class="table-header" style="margin:0;">
                                        问题汇总
                                    </div>
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>房间号</th>
                                                <th>指标项</th>
                                                <th>问题类型</th>
                                                <th>问题原因</th>
                                                <th>描述</th>
                                                <th>照片</th>
                                                <th>检查人</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="effort in check.problem_list" :key="effort.room_id">
                                                <td>
                                                    <span v-for="room in room_list" :key="room.room_id" v-if="effort.room_id == room.room_id">{{room.room_name}}</span>
                                                </td>
                                                <td>{{effort.item_name}}</td>
                                                <td>
                                                    <span v-if="effort.item_level == 'common'">普通</span>
                                                    <span v-if="effort.item_level == 'fatal'">一票否决</span>
                                                </td>
                                                <td>
                                                    <span v-if="effort.problem_level == 'no'">不合格</span>
                                                    <span v-if="effort.problem_level == 'NA'">不符合</span>
                                                </td>
                                                <td>{{effort.intro}}</td>
                                                <td>
                                                    <img :src="effort.photos" alt="" v-if="effort.photos">
                                                </td>
                                                <td>
                                                    <span v-for="user in user_list" :key="user.username" v-if="user.username == effort.staff">{{user.name}}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="roomResult" v-if="check.effort_list"> 
                                    <div class="table-header" style="margin:0;">
                                        各房间得分
                                    </div>
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>房间号</th>
                                                <th>得分</th>
                                                <th>一般问题数</th>
                                                <th>一票否决问题数量</th>
                                                <th>检查人</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="effort in check.effort_list" :key="effort.room_id">
                                                <td>
                                                    {{effort.room_name}}
                                                </td>
                                                <td>{{effort.score}}</td>
                                                <td>
                                                    {{effort.problem_common}}
                                                </td>
                                                <td>
                                                    {{effort.problem_fatal}}
                                                </td>
                                                <td>
                                                    <span v-for="user in user_list" :key="user.username" v-if="user.username == effort.staff">{{user.name}}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="review" v-if="check.check_state == 'finished'">
                                        <button class="btn btn-primary btn-sm" v-if="check.review_state == 'no-start'" @click="setReview(org,'pending')">发起整改通知</button>
                                        <button class="btn btn-success btn-sm" v-if="check.review_state == 'no-start'" @click="setReview(org,'no-need')">不需要整改</button>
                                        <ul class="list-group" v-if="check.review_state == 'pending'||check.review_state == 'finished'">
                                            <li class="list-group-item">
                                                <span class="align-top">整改要求：</span>
                                                <span>{{check.review}}</span>
                                            </li>
                                            <li class="list-group-item">
                                                整改通知人：{{check.review_staff}}，整改通知时间：{{check.dt_inform.substring(0,10)}}
                                            </li>
                                            <li class="list-group-item" v-if="check.review">
                                                <span class="align-top">整改反馈内容：</span>
                                                <span>{{check.reply}}</span>
                                            </li>
                                            <li class="list-group-item">
                                                整改反馈人：{{check.reply_staff}}，整改反馈时间：{{check.dt_reply.substring(0,10)}}
                                            </li>
                                        </ul>
                                        <span v-if="check.review_state == 'no-need'">
                                            不需要整改
                                        </span>
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
import datepicker from "vue2-datepicker";
export default {
    name: "checkWork",
    components: { datepicker },
    data() {
        return {
            title: "检查工作详情",
            resultObj:{},
            user_list:[],
        };
    },
    methods: {
        getResultList(){
            const _this = this;
            const URL = this.serverUrl +"/admin/check/checkResult";
            const data = {
                lab_id: this.$route.params.id,
                plan_id: this.$route.query.plan_id,
            }
            this.emitAjax(URL,data,function(result){
                _this.resultObj = result[0];
            })
        },
        getUserList(){
            const _this = this;
            const URL = this.serverUrl +"/admin/person/index";
            this.emitAjax(URL,null,function(result){
                _this.user_list = result;
            })
        }
    },
    mounted() {
        //this.getCheckInfo();
        this.getResultList();
        this.getUserList();
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