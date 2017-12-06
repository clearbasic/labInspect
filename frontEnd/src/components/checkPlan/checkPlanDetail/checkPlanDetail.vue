<template>
	<div class="welcome">
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
                                <router-link :to="pathName+'/checkPlan'" class="active">检查期次管理</router-link>
                            </li>
                        </ul>
                    </div>
                    <!--  -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{title}}
                            </h1>
                        </div>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="" class="col-sm-2 col-md-1 control-label">期次名称</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" v-model="checkPlan.plan_name" @blur="setCheckPlan">
                                </div>
                                <label for="" class="col-sm-1 control-label">总分</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" v-model="checkPlan.plan_score" @blur="setCheckPlan">
                                </div>
                            </div>
                        </div>
                        <div class="tabbable">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#checkPlan" data-toggle="tab" class="bigger-130">检查计划</a></li>
                                <li><a href="#checkRule" data-toggle="tab" class="bigger-130">检查规则</a></li>
                                <li><a href="#workDescription" data-toggle="tab" class="bigger-130">工作说明</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="checkPlan" class="tab-pane fade active in">
                                    <CheckTask></CheckTask>
                                </div>
                                <div id="checkRule" class="tab-pane fade">
                                    <CheckRule></CheckRule>
                                </div>
                                <div id="workDescription" class="tab-pane fade">
                                    <CheckDescription></CheckDescription>
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
import VueHead from "../../common/header";
import VueLeft from "../../common/leftMenu";
import CheckTask from './checkTask';
import CheckRule from './checkRule';
import CheckDescription from './checkDescription';

export default {
    name: "checkPlanDetail",
    components: {
        VueHead,
        VueLeft,
        CheckTask,
        CheckRule,
        CheckDescription
    },
    data() {
        return {
            title: "检查期次管理编辑",
        };
    },
    computed:{
        checkPlan(){
            return this.$store.state.checkPlan;
        }
    },
    methods:{
        setCheckPlan(){
            this.$store.dispatch("setCheckPlan",this.checkPlan);
        },
    },
    mounted(){
        //获取检测期次信息
        this.$store.dispatch("setCheckPlan");
    }
};
</script>