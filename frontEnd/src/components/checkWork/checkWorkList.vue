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
                        <h3 class="text-center" style="margin-top:10px;">
                            {{currentPlan.plan_name}} 
                            {{currentCheck.task_name}}
                            <span v-if="currentCheck.task_level === 'lab'">自查</span>
                            <span v-if="currentCheck.task_level === 'college'">复查</span>
                            <span v-if="currentCheck.task_level === 'school'">抽查</span>
                        </h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <h5 class="text-center">
                                    学校要求时间{{currentCheck.dt_begin.substring(0,10)}} 到 {{currentCheck.dt_end.substring(0,10)}}
                                    满分{{currentPlan.plan_score}}
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
            title: "检查工作列表",
            currentPlan:{},
            currentCheck:{
                dt_begin:"",
                dt_end:"",
            },
            labArray:[],
        };
    },
    methods: {
        init(){
            this.getCurrentPlan();
            this.getLabList();
        },
        getCurrentPlan(){
            //获取期次信息
            const _this = this;
            const URl = this.serverUrl + "/admin/check/checkindex";
            let data = this.$route.query.plan_id != 0?{
                    college_id:this.$route.query.college_id,
                    plan_id:this.$route.query.plan_id
                }:{
                    college_id:this.$route.query.college_id,
                };
            this.emitAjax(URl,data,function(result){
                _this.currentPlan = result.plan;
                _this.getCurrentCheck(result.check_list);
            });
        },
        getCurrentCheck(check_list){
            //获取当前检查工作内容
            for (let index = 0; index < check_list.length; index++) {
                const checkItem = check_list[index];
                if(checkItem.task_id == this.$route.params.id){
                    this.currentCheck = Object.assign({},checkItem);
                    break;
                }
            }
        },
        getLabList(){
            const _this = this;
            const URl = this.serverUrl + "/admin/org/index";
            this.emitAjax(URl,null,function(result){
                _this.filterLabList(result);
            });
        },
        filterLabList(array){
            this.labArray = [];
            const _this = this;
            for (let index = 0; index < array.length; index++) {
                const lab = array[index];
                if(lab.org_level == "lab"){
                    _this.labArray.push(Object.assign({},lab));
                }
            }
        }
    },
    mounted() {
        if(this.checkPermission(this)){
            this.init();
        }
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