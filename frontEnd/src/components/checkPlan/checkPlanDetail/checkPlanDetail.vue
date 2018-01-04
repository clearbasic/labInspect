<template>
	
    <!-- 右侧内容 -->
    <div class="main-content checkPlanDetail">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/checkPlan'">检查期次管理</router-link>
                    </li>
                    <li>
                        <a class="active">{{checkPlan.plan.plan_name}}</a>
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
                        <label for="" class="col-sm-2 col-md-1 control-label" style="white-space:nowrap;">期次名称</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" 
                                v-model="checkPlan.plan.plan_name" 
                                @blur="editCheckPlan('期次名称')" 
                                @focus="setFlag(checkPlan.plan.plan_name)">
                        </div>
                        <label for="" class="col-sm-1 control-label">总分</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" 
                                v-model="checkPlan.plan.plan_score" 
                                @blur="editCheckPlan('期次总分')" 
                                @focus="setFlag(checkPlan.plan.plan_score)">
                        </div>
                        <div class="col-sm-3" style="line-height:29px;">
                            是否当前期次
                            <label>
                                <input type="radio" class="ace" v-model="checkPlan.plan.current" value="yes" @click="editCheckPlanCurrent('yes')">
                                <span class="lbl">是</span>
                            </label>
                            <label>
                                <input type="radio" class="ace" v-model="checkPlan.plan.current" value="no" @click="editCheckPlanCurrent('no')">
                                <span class="lbl">否</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#checkPlan" data-toggle="tab" class="bigger-130">检查安排</a></li>
                        <li><a href="#checkRule" data-toggle="tab" class="bigger-130">检查规则</a></li>
                        <li><a href="#workDescription" data-toggle="tab" class="bigger-130">工作说明</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="checkPlan" class="tab-pane fade active in">
                            <CheckTask :showToast="showToast"></CheckTask>
                        </div>
                        <div id="checkRule" class="tab-pane fade">
                            <CheckRule :showToast="showToast"></CheckRule>
                        </div>
                        <div id="workDescription" class="tab-pane fade">
                            <CheckDescription :showToast="showToast"></CheckDescription>
                        </div>
                    </div>
                </div>  
                <Toast :show="isShow" :msg="msg" :hide="hideToast"></Toast>
            </div>
        </div>
    </div>
</template>

<script>
import CheckTask from './checkTask';
import CheckRule from './checkRule';
import CheckDescription from './checkDescription';
import Toast from '../../common/toast.vue';

export default {
    name: "checkPlanDetail",
    components: {
        CheckTask,
        CheckRule,
        CheckDescription,
        Toast,
    },
    data() {
        return {
            title: "检查期次管理编辑",
            flag:"",
            isShow:false,
            msg:"保存成功"
        };
    },
    computed:{
        checkPlan(){
            return this.$store.state.checkPlan;
        }
    },
    methods:{
        editCheckPlan(type){
            //修改期次信息
            let isAjax = false;
            //如果信息为空则 不提交
            if(this.checkPlan.plan.plan_name == "" || this.checkPlan.plan.plan_score ==""){
                type && alert(type+"不能为空！");
                return false;
            }
            //如果输入框值没有改变
            if(type == "期次名称" && this.flag == this.checkPlan.plan.plan_name){
               return false;
            }
            if(type == "期次总分" && this.flag == this.checkPlan.plan.plan_score){
               return false;
            }
            const URL = this.serverUrl + "/admin/plan/edit";
            const _SELF = this;
            const data ={
                plan_id:this.$route.params.id,
                plan_name:this.checkPlan.plan.plan_name,
                plan_score:this.checkPlan.plan.plan_score,
                current:this.checkPlan.plan.current,
                intro:this.checkPlan.plan.intro,
            }
            this.flag = "";
            this.emitAjax(URL, data, function(){
               _SELF.showToast();
            },function(){
                //修改失败刷新页面
                _SELF.$router.push(pathName+'/checkPlan/'+this.$route.params.id);
            });
        },
        editCheckPlanCurrent(value){
            if(this.checkPlan.plan.current !== value){
                this.checkPlan.plan.current = value;
                this.editCheckPlan();
            }
        },
        getCheckPlan(){
            //获取期次信息
            this.$store.dispatch("getCheckPlan",{plan_id:this.$route.params.id});
        },
        setFlag(value){
            this.flag = value;
        },
        hideToast(){
            this.isShow = false;
        },
        showToast(msg){
            if(msg){
                this.msg = msg;
            }
            this.isShow = true;
        }
    },
    mounted(){
        this.getCheckPlan();
    }
};
</script>