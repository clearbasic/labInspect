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
                                <router-link :to="pathName+'/checkList'">检查指标类别管理</router-link>
                            </li>
                            <li>
                                <router-link :to="pathName+'/checkList/'+$route.params.id+'?checkListName='+$route.query.checkListName" class="active">{{$route.query.checkListName}}</router-link>
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
                        <h3 style="margin:10px 0;" class="text-center">{{$route.query.checkListName}}</h3>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center" width="60px">指标ID</th>
                                    <th class="center little hidden-480">类别</th>
                                    <th>名称</th>
                                    <th class="center little">一票否决</th>
                                    <th class="center little">排序</th>
                                    <th class="center little">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in checkListDate" :key="item.id" :style="item.item_type=== 'header'?'font-weight:bold':''">
                                    <td class="center">{{item.id}}</td>
                                    <td class="hidden-480"
                                        @dblclick="showcheckItemInput(item.id,item.item_name,item.item_level,item.item_type,item.item_order,'Type')"
                                    >
                                        <span v-if="isTypeInput != item.id">{{item.item_type=== 'header'?'小标题':'指标'}}</span>
                                        <label v-if="isTypeInput == item.id" class="text-left">
                                            <input type="radio" class="ace" v-model="checkItemType" value="header" @click="setcheckItemInfo($event,'Type','header')">
                                            <span class="lbl">小标题</span>
                                        </label>
                                        <label v-if="isTypeInput == item.id" class="text-left">
                                            <input type="radio" class="ace" v-model="checkItemType" value="common" @click="setcheckItemInfo($event,'Type','common')">
                                            <span class="lbl">指标</span>
                                        </label>
                                    </td>
                                    <td @dblclick="showcheckItemInput(item.id,item.item_name,item.item_level,item.item_type,item.item_order,'Name')">
                                        <span v-if="isNameInput != item.id">{{item.item_name}}</span>
                                        <input  v-if="isNameInput == item.id" 
                                                autofocus="autofocus"
                                                type="text" name="item_name" 
                                                v-model="checkItemName" 
                                                @blur="setcheckItemInfo"
                                                @keyup="setcheckItemInfo"
                                                class="inlineInput"
                                        >
                                    </td>
                                    <td class="center" 
                                        @dblclick="showcheckItemInput(item.id,item.item_name,item.item_level,item.item_type,item.item_order,'Level')"
                                    >
                                        <span v-if="isLevelInput !== item.id">{{item.item_level==="fatal"?'是':'否'}}</span>

                                        <label v-if="isLevelInput == item.id" class="text-left">
                                            <input type="radio" class="ace" v-model="checkItemLevel" value="fatal" @click="setcheckItemInfo($event,'Level','fatal')">
                                            <span class="lbl">是</span>
                                        </label>
                                        <label v-if="isLevelInput == item.id" class="text-left">
                                            <input type="radio" class="ace" v-model="checkItemLevel" value="common" @click="setcheckItemInfo($event,'Level','common')">
                                            <span class="lbl">否</span>
                                        </label>
                                    </td>
                                    <td class="center" 
                                        @dblclick="showcheckItemInput(item.id,item.item_name,item.item_level,item.item_type,item.item_order,'Order')"
                                    >
                                        <span v-if="isOrderInput != item.id">{{item.item_order}}</span>
                                        <input  v-if="isOrderInput == item.id"
                                            autofocus="autofocus" 
                                            type="text" name="item_name" 
                                            v-model="checkItemOrder" 
                                            @blur="setcheckItemInfo"
                                            @keyup="setcheckItemInfo"
                                            style="width:30px"
                                            class="text-center"
                                        >
                                    </td>
                                    <td class="center">
                                        <button class="btn btn-xs btn-danger" @click="deleteCheckItem(item.id,item.item_name)">
                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <CheckItemModal
            :getCheckItemList="getCheckItemList"
            :length = "checkListDate.length+1"
        ></CheckItemModal>
	</div>
</template>

<script>
import VueHead from "../common/header";
import VueLeft from "../common/leftMenu";
import CheckItemModal from "./checkItemModal";
import { serverUrl } from "../../config/server.js";
import { emitAjax } from "../../assets/common.js";

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
            id:0,
            checkItemName:"", //当前指标修改的名字
            checkItemOrder:0, //当前指标修改的名字
            checkItemType:"common", //当前指标修改的类别
            checkItemLevel:"common", //当前指标修改的级别
            isLevelInput:0,
            isNameInput:0,
            isOrderInput:0,
            isTypeInput:0,
            checkListDate:[],
        }
    },
    methods:{
        showcheckItemInput(id,item_name,item_level,item_type,item_order,type){
            this['is'+type+'Input'] = id;
            this.id=id;
            this.checkItemName = item_name;
            this.checkItemOrder = item_order;
            this.checkItemType = item_type;
            this.checkItemLevel = item_level;
        },
        setcheckItemInfo(event,type,value){
            //按enter键触发
            if(event.type=="keyup" && event.key=="Enter"){
                this.setcheckItem();
            }
            //输入框失焦触发
            if(event.type=="blur"&&this.id){
                this.setcheckItem();
            }
            //点击单选按钮触发
            if(event.type=="click" && type && value){
                this['checkItem'+type] = value;
                this.setcheckItem();
            }
        },
        setcheckItem(){
            //修改指标
            if(this.checkItemName == "" || this.checkItemOrder==""){
                alert("指标信息不能为空");
            }else{
                const URL = serverUrl + "/admin/item/edit";
                const _SELF = this;
                const data = {
                    id:this.id,
                    item_name:this.checkItemName,
                    item_order:this.checkItemOrder,
                    item_level:this.checkItemLevel,
                    item_type:this.checkItemType
                }
                //还原各类信息
                this.id = 0;
                this.checkItemName="";
                this.checkItemOrder=0;
                this.checkItemType="common";
                this.checkItemLevel="common";
                this.isLevelInput=0;
                this.isNameInput=0;
                this.isOrderInput=0;
                this.isTypeInput=0;
                emitAjax(URL, data, function(result) {
                    _SELF.getCheckItemList();
                });
            }
            
        },
        deleteCheckItem(id,name){
            if(window.confirm("是否删除<"+name+">，此操作为不可逆操作，请慎重！！")){
                //删除代码
                const URL = serverUrl + "/admin/item/del";
                const _SELF = this;
                const data = {
                    id
                }
                emitAjax(URL, data, function(result) {
                    _SELF.getCheckItemList();
                });
            }
        },
        getCheckItemList(){
            //获取指标库指标列表
            const URL = serverUrl + "/admin/item/index";
            const _SELF = this;
            const data = {
                checklist_id:this.$route.params.id
            }
            emitAjax(URL, data, function(result) {
                _SELF.checkListDate = result;
            });
        }
    },
    mounted(){
        this.getCheckItemList();
    }
};
</script>
