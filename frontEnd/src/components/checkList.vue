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
                                <router-link to="/checkList" class="active">{{title}}</router-link>
                            </li>
                        </ul>
                    </div>
                    <!-- 左侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>{{title}}</h1>
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center" width="60px">类别ID</th>
                                    <th>指标类别名称</th>
                                    <th class="hidden-480">描述</th>
                                    <th class="center lettle">指标数量</th>
                                    <th class="center lettle">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in checkListDate" :key="item.id">
                                    <td class="center">{{item.id}}</td>
                                    <td @dblclick="showCheckListNameInput(item.id,item.name)">
                                        <span v-if="isInput != item.id">{{item.name}}</span>
                                        <input v-if="isInput == item.id" 
                                            autofocus="autofocus" 
                                            type="text" name="name" 
                                            v-model="checkListName"
                                            @blur="setCheckListName"
                                            class="inlineInput"
                                        >
                                    </td>
                                    <td class="hidden-480">{{item.intro}}</td>
                                    <td class="center"><router-link :to="{path:pathName+'/checkList/'+item.id,query:{checkListName:item.name}}">{{item.count}}</router-link></td>
                                    <td class="center"><button class="btn btn-xs btn-danger" @click="deleteCheckItem(item.id)"><i class="ace-icon fa fa-trash-o bigger-130"></i></button></td>
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
import VueHead from "./common/header";
import VueLeft from "./common/leftMenu";

export default {
    name: "checkList",
    components: {
        VueHead,
        VueLeft
    },
    data() {
        return {
            title: "检查指标类别管理",
            isInput:0,
            checkListName:"", //当前指标分类修改的名字
            checkListDate:[
                {
                    id:1,
                    name:"教师检查指标库",
                    intro:"描述",
                    group_order:"",
                    creator:"李四",
                    dt_create:"2017-10-13",
                    count:12
                },
                {
                    id:2,
                    name:"课桌检查指标库",
                    intro:"描述",
                    group_order:"",
                    creator:"李四",
                    dt_create:"2017-10-13",
                    count:12
                },
                {
                    id:3,
                    name:"药品检查指标库",
                    intro:"描述",
                    group_order:"",
                    creator:"李四",
                    dt_create:"2017-10-13",
                    count:12
                }
            ]
        }
    },
    methods:{
        deleteCheckItem(id){
            if(window.confirm("删除后不可恢复，请谨慎操作，是否继续删除？？")){
                //删除代码
            }
        },
        showCheckListNameInput(id,name){
            this.isInput = id;
            this.checkListName = name;
        },
        setCheckListName(){
            //发送ajax修改名称 
            console.log("ID为"+this.isInput+"的指标类别名字改成了"+this.checkListName);
            this.isInput = 0;
        }
    },
};
</script>
