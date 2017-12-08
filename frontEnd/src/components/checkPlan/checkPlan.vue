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
                                <router-link to="/">首页</router-link>
                            </li>
                            <li>
                                <router-link to="/checkPlan" class="active">{{title}}</router-link>
                            </li>
                        </ul>
                    </div>
                    <!-- 左侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{title}}
                                <div class="pull-right" @click="addPlan">
                                    <button class="btn btn-primary btn-sm">添加</button>
                                </div>
                            </h1>
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead> 
                                <tr>
                                    <th class="center little">期次ID</th>
                                    <th>名称</th>
                                    <th class="center little">当前期次</th>
                                    <th class="center little">总分</th>
                                    <th class="center little">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in checkPlan" :key="'checkPaln'+item.id">
                                    <td class="center">
                                        {{item.plan_id}}
                                    </td>
                                    <td>
                                        <router-link :to="pathName+'/checkPlan/'+item.plan_id">{{item.plan_name}}</router-link>
                                    </td>
                                    <td class="center">
                                        {{item.current === "yes"?'是':'否'}}
                                    </td>
                                    <td class="center">
                                        {{item.plan_score}}
                                    </td>
                                    <td class="center">
                                        <router-link class="btn btn-xs btn-success" :to="pathName+'/checkPlan/'+item.plan_id" tag="button">
                                            <i class="ace-icon fa fa-search-plus bigger-100"></i>
                                        </router-link>
                                        <button class="btn btn-xs btn-danger" @click="deleteCheckPlan(item.plan_id,item.plan_name)"><i class="ace-icon fa fa-trash-o bigger-100"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="isAdd">
                                    <td class="center"></td>
                                    <td :class="{'has-error':newPlanName==''}">
                                        <input type="text" v-model="newPlanName">
                                    </td>
                                    <td class="center">
                                        <label>
                                            <input type="radio" class="ace" v-model="newCurrent" value="yes">
                                            <span class="lbl">是</span>
                                        </label>
                                        <label>
                                            <input type="radio" class="ace" v-model="newCurrent" value="no">
                                            <span class="lbl">否</span>
                                        </label>
                                    </td>
                                    <td class="center">
                                        <input type="text" v-model="newPlanScore" style="width:44px;" class="text-center">
                                    </td>
                                    <td class="center">
                                        <button class="btn btn-xs btn-success" @click="originAddPlan"><i class="ace-icon glyphicon glyphicon-ok bigger-130"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
import VueHead from "../common/header";
import VueLeft from "../common/leftMenu";
import { serverUrl } from "../../config/server.js";
import { emitAjax } from "../../assets/common.js";

export default {
    name: "checkPlan",
    components: {
        VueHead,
        VueLeft
    },
    data() {
        return {
            title: "检查期次管理",
            checkPlan:[],
            isAdd:false,
            newCurrent:"no",
            newPlanName:"",
            newPlanScore:100,
        };
    },
    methods:{
        addPlan(){
            this.isAdd = !this.isAdd;
        },
        originAddPlan(){
            if(this.newPlanName == ""){
                alert("期次名字不能为空！！");
                return false;
            }
            const URL = serverUrl + "/admin/plan/add";
            const _SELF = this;
            const data = {
                plan_name:this.newPlanName,
                plan_score:this.newPlanScore,
                current:this.newCurrent
            }
            emitAjax(URL, data,function(){
                _SELF.newPlanName = "";
                _SELF.newPlanScore = 100;
                _SELF.newCurrent = "no";
                _SELF.isAdd=false;
                _SELF.getPlanData();
            });
        },
        deleteCheckPlan(plan_id,name){
            if(confirm("是否删除期次<"+name+">，此操作不可逆，请慎重！")){
                //删除检查期次代码
                const URL = serverUrl + "/admin/plan/del";
                const _SELF = this;

                emitAjax(URL, {plan_id}, function(result) {
                    _SELF.getPlanData();
                });
            }
        },
        getPlanData(){
            const URL = serverUrl + "/admin/plan/index";
            const _SELF = this;
            emitAjax(URL, null, function(result) {
                _SELF.checkPlan = Object.assign({},result);
            });
        }
    },
    mounted(){
        this.getPlanData();
    }
};
</script>