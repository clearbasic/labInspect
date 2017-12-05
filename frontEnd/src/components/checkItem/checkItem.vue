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
                                <router-link to="/checkList" class="active">检查指标类别管理</router-link>
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
                                    <button class="btn btn-primary btn-sm">导入</button>
                                </div>
                            </h1>
                        </div>
                        <h3 style="margin:10px 0;" class="text-center">{{$route.query.checkItemName}}</h3>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center" width="60px">指标ID</th>
                                    <th width="60px" class="center hidden-480">类别</th>
                                    <th>名称</th>
                                    <th class="center lettle">一票否决</th>
                                    <th class="center lettle">序号</th>
                                    <th class="center lettle">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in checkListDate" :key="item.id" :style="item.item_type=== 'header'?'font-weight:bold':''">
                                    <td class="center">{{item.id}}</td>
                                    <td class="center hidden-480">{{item.item_type=== 'header'?'小标题':'指标'}}</td>
                                    <td @dblclick="showcheckItemNameInput(item.id,item.item_name,'name')">
                                        <span v-if="isNameInput != item.id">{{item.item_name}}</span>
                                        <input  v-if="isNameInput == item.id" 
                                                autofocus="autofocus"
                                                type="text" name="item_name" 
                                                v-model="checkItemName" 
                                                @blur="setcheckItemName"
                                                class="inlineInput"
                                        >
                                    </td>
                                    <td class="center">{{item.item_level==="fatal"?'是':'否'}}</td>
                                    <td class="center" @dblclick="showcheckItemNameInput(item.id,item.item_order,'order')">
                                        <span v-if="isOrderInput != item.id">{{item.item_order}}</span>
                                        <input  v-if="isOrderInput == item.id"
                                                autofocus="autofocus" 
                                                type="text" name="item_name" 
                                                v-model="checkItemOrder" 
                                                @blur="setcheckItemOrder"
                                                style="width:30px"
                                                class="text-center"
                                        >
                                    </td>
                                    <td class="center"><button class="btn btn-xs btn-danger" @click="deleteCheckItem(item.id)"><i class="ace-icon fa fa-trash-o bigger-130"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <CheckItemModal
            :getCheckItemList="getCheckItemList"
        ></CheckItemModal>
	</div>
</template>

<script>
import VueHead from "../common/header";
import VueLeft from "../common/leftMenu";
import CheckItemModal from "./checkItemModal";

export default {
    name: "checkItem",
    components: {
        VueHead,
        VueLeft,
        CheckItemModal
    },
    data() {
        return {
            title: "检查指标管理",
            isNameInput:0,
            isOrderInput:0,
            checkItemName:"", //当前指标修改的名字
            checkItemOrder:"", //当前指标修改的名字
            checkListDate:[{
                id:1,
                item_name:"指标的名字",
                item_level:"common",
                item_order:"1",
                item_type:"header"
            },{
                id:2,
                item_name:"指标的名字",
                item_level:"fatal",
                item_order:"1",
                item_type:"common"
            }],
        }
    },
    methods:{
        showcheckItemNameInput(id,name,type){
            if(type === "name"){
                this.isNameInput = id;
                this.checkItemName = name;
            }else{
                this.isOrderInput = id;
                this.checkItemOrder = name;
            }
        },
        setcheckItemName(){
            //远程提交操作
            this.isNameInput = 0;
        },
        setcheckItemOrder(){
            //远程提交操作
            this.isOrderInput = 0;
        },
        deleteCheckItem(id){
            if(window.confirm("删除后不可恢复，请谨慎操作，是否继续删除？？")){
                //删除代码
            }
        },
        getCheckItemList(){
            //获取指标库指标列表
        }
    },
    mounted(){
        this.getCheckItemList();
    }
};
</script>
