<template>
	<div class="checkPlan">
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
                                <router-link to="/">首页</router-link>
                            </li>
                            <li>
                                <router-link to="/checkPlan" class="active">{{title}}</router-link>
                            </li>
                        </ul>
                    </div>
                    <!-- 右侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{currentPlan.plan_name}}工作说明
                            </h1>
                        </div>
                        <div class="text-justify panel-body" v-html="currentPlan.intro"></div>
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
    name: "checkPlan",
    components: {
        VueHead,
        VueLeft
    },
    data() {
        return {
            title: "工作说明",
            currentPlan:{},
        };
    },
    methods:{
        getCurrentPlan(){
            const _this = this;
            const URL = this.serverUrl + '/admin/plan/index';
            this.emitAjax(URL,{plan_id:this.$route.query.plan_id},function(result){
                _this.currentPlan = result[0];
            })
        }
    },
    mounted(){
        if(this.checkPermission(this)){
            this.getCurrentPlan();
        }
    }
};
</script>