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
                <div class="accordion-style1 panel-group" id="accordion">
                    <!-- 按实验室循环列表 -->
                    <div class="panel panel-default" v-for="(org,index) in result_list" :key="'org'+org.org_id">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a :href="'#panelOrg'+index" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" aria-expanded="false">
                                    <i :class="['bigger-110 ace-icon fa',{'fa-angle-down':index==0},{'fa-angle-right':index!=0}]" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                                    {{org.org_name}}
                                </a>
                            </h4>
                        </div>
                        <div :class="['panel-collapse collapse',{in:index==0}]" :id="'panelOrg'+index" aria-expanded="false">
                            <div class="panel-body">
                                <p>开展时间：{{org.check[0].dt_begin.substring(0,10)}}到{{org.check[0].dt_end.substring(0,10)}}
                                    得分：{{org.check[0].check_score}}
                                    一般问题数量：{{org.check[0].problem_common}}
                                    一票否决数量：{{org.check[0].problem_fatal}}</p>
                                <div class="problem" v-if="org.check[0].problem_list.length>0">
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
                                            <tr v-for="effort in org.check[0].problem_list" :key="effort.room_id">
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
                                <div class="roomResult" v-if="org.check[0].effort_list.length>0"> 
                                    <div class="table-header" style="margin:0;">
                                        各房间得分
                                    </div>
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>房间名称</th>
                                                <th>得分</th>
                                                <th>一般问题数</th>
                                                <th>一票否决问题数量</th>
                                                <th>检查人</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="effort in org.check[0].effort_list" :key="effort.room_id">
                                                <td>
                                                    <span v-for="room in room_list" :key="room.room_id" v-if="effort.room_id == room.room_id">{{room.room_name}}</span>
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
                                    <div class="review">
                                        <button class="btn btn-primary btn-sm" v-if="org.check[0].review_state == 'no-start' && loginUser.group_level == 'school'" @click="setReview(org,'pending')">发起整改通知</button>
                                        <button class="btn btn-success btn-sm" v-if="org.check[0].review_state == 'no-start' && loginUser.group_level == 'school'" @click="setReview(org,'no-need')">不需要整改</button>
                                        <ul class="list-group" v-if="org.check[0].review_state == 'pending'||org.check[0].review_state == 'finished'">
                                            <li class="list-group-item" v-if="org.check[0].review">
                                                {{org.check[0].dt_inform.substring(0,10)}} {{org.check[0].review_staff}}提出了以下整改要求：
                                            </li>
                                            <li class="list-group-item">
                                                <label class="align-top" v-if="!org.check[0].review">整改要求：</label>
                                                <textarea v-model="org.check[0].review" v-if="loginUser.group_level == 'school'" class="textarea"></textarea>
                                                <textarea v-model="org.check[0].review" v-if="loginUser.group_level != 'school'" readonly class="bg-gray textarea"></textarea>
                                            </li>
                                            <li class="list-group-item" v-if="org.check[0].reply">
                                                {{org.check[0].dt_reply.substring(0,10)}} {{org.check[0].reply_staff}}做出了以下反馈：
                                            </li>
                                            <li class="list-group-item" v-if="org.check[0].review">
                                                <label class="align-top"  v-if="!org.check[0].reply">整改反馈内容：</label>
                                                <textarea v-model="org.check[0].reply" v-if="loginUser.group_level == 'lab'" class="textarea"></textarea>
                                                <textarea v-if="loginUser.group_level != 'lab'" v-model="org.check[0].reply" readonly class="bg-gray textarea"></textarea>
                                            </li>
                                            <li class="list-group-item" v-if="org.check[0].review_state != 'college'">
                                                <button class="btn btn-primary btn-sm" @click="saveReview(org)" v-if="loginUser.group_level=='school'">提交整改</button>
                                                <button class="btn btn-primary btn-sm" @click="saveReview(org)" v-if="loginUser.group_level=='lab'">提交反馈</button>
                                                <button class="btn btn-default btn-sm" @click="org.check[0].review_state='no-start'" v-if="loginUser.group_level=='school'&&org.check[0].review_state == 'pending'">取消</button>
                                            </li>
                                        </ul>
                                        <span v-if="org.check[0].review_state == 'no-need'">
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
            title: "检查工作进度",
            addLab: false,
            currentPlan: {},
            currentTask: { dt_begin: "", dt_end: "" },
            college_info: {},
            lab_list: [],
            group_list: [],
            zone_list: [],
            room_list: [],
            result_list:[],
            user_list:[],
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
        getResultList(){
            const _this = this;
            const URL = this.serverUrl +"/admin/check/checkResult";
            const data = {
                college_id: this.$route.query.college_id,
                task_id: this.$route.params.id
            }
            this.emitAjax(URL,data,function(result){
                _this.result_list = result;
            })
        },
        getUserList(){
            const _this = this;
            const URL = this.serverUrl +"/admin/person/index";
            this.emitAjax(URL,null,function(result){
                _this.user_list = result;
            })
        },
        setReview(org,type){
            if(type == 'no-need'){
                if(confirm("是否确定不需要整改？")){
                    org.check[0].review="";
                    org.check[0].reply="";
                    org.check[0].review_state =  type;
                    this.saveReview(org);
                }
            }else{
                org.check[0].review_state =  type;
            }
        },
        saveReview(org){

            //保存整改通知
            if(this.loginUser.group_level == 'lab' && org.check[0].review_state =='pending'){
                org.check[0].review_state = 'finished';
            }
            const data = {
                check_id:org.check[0].check_id,
                review:org.check[0].review,
                reply:org.check[0].reply,
                review_state:org.check[0].review_state
            }

            const URL = this.serverUrl + "/admin/check/feedback";
            const _this = this;
            this.emitAjax(URL,data,function(){
                _this.$store.commit("showToast",{isShow:true});
                _this.getResultList();
            })
        }
    },
    mounted() {
        this.getCheckInfo();
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