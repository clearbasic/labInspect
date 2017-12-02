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
                                <div class="pull-right">
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">添加</button>
                                </div>
                            </h1>
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead> 
                                <tr>
                                    <th class="center lettle">期次ID</th>
                                    <th>名称</th>
                                    <th class="center lettle">当前期次</th>
                                    <th class="center lettle">总分</th>
                                    <th class="center lettle">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in checkPlan" :key="'checkPaln'+item.id">
                                    <td class="center">
                                        {{item.plan_id}}
                                    </td>
                                    <td>
                                        {{item.plan_name}}
                                    </td>
                                    <td class="center">
                                        {{item.current === "yes"?'是':'否'}}
                                    </td>
                                    <td class="center">
                                        {{item.plan_score}}
                                    </td>
                                    <td class="center">
                                        
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

export default {
    name: "checkPlanDetail",
    components: {
        VueHead,
        VueLeft
    },
    data() {
        return {
            title: "检查期次管理",
            checkPlan:[
                {
                    plan_id:1,
                    plan_name:"2018年实验室检查",
                    current:"no",
                    plan_score:100
                },
                {
                    plan_id:2,
                    plan_name:"2017年实验室检查",
                    current:"yes",
                    plan_score:100
                }
            ]
        };
    }
};
</script>