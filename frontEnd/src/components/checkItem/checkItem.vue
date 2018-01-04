<template>
    <!-- 右侧内容 -->
    <div class="main-content checkItem">
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
            <!-- 右侧主要内容 -->
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
                <h3 style="margin:10px 0;" class="text-center">{{currentCheckItem.name}}</h3>
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
                                        @focus="setFlag('Name',item.item_name)"
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
                                    @focus="setFlag('Order',item.item_order)"
                                    style="width:30px"
                                    class="text-center"
                                >
                            </td>
                            <td class="center">
                                <button class="btn btn-xs btn-danger" @click="deleteCheckItem(item.id,item.item_name)" title="删除">
                                    <i class="ace-icon fa fa-trash-o bigger-110"></i>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="checkListDate.length == 0">
                            <td colspan="6" class="center">暂无指标数据</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <CheckItemModal
            :getCheckItemList="getCheckItemList"
            :length = "checkListDate.length+1"
        ></CheckItemModal>
    </div>
</template>
<script>
import CheckItemModal from "./checkItemModal";

export default {
    name: "checkItem",
    components: {
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
            currentCheckItem:{},
            flag:{
                type:"",
                msg:"",
            }
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
                this.setFlag(type,this['checkItem'+type]);
                this['checkItem'+type] = value;
                this.setcheckItem();
            }
        },
        setFlag(type,msg){
            this.flag.type=type;
            this.flag.msg=msg;
        },
        setcheckItem(){
            //修改指标
            if(this.checkItemName == "" || this.checkItemOrder==""){
                alert("指标信息不能为空");
                return false;
            }

            const URL = this.serverUrl + "/admin/item/edit";
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
            this.isLevelInput=0;
            this.isNameInput=0;
            this.isOrderInput=0;
            this.isTypeInput=0;
            //值不变不提交ajax
            if(this.flag.type=="Name"&&this.flag.msg == this.checkItemName){return false}
            if(this.flag.type=="Order"&&this.flag.msg == this.checkItemOrder){return false}
            if(this.flag.type=="Type"&&this.flag.msg == this.checkItemType){return false}
            if(this.flag.type=="Level"&&this.flag.msg == this.checkItemLevel){return false}
            this.checkItemName="";
            this.checkItemOrder=0;
            this.checkItemType="common";
            this.checkItemLevel="common";
            this.flag = {type:"",msg:""};
            this.emitAjax(URL, data, function(result) {
                _SELF.getCheckItemList();
            });
        },
        deleteCheckItem(id,name){
            if(window.confirm("是否删除<"+name+">，此操作为不可逆操作，请慎重！！")){
                //删除代码
                const URL = this.serverUrl + "/admin/item/del";
                const _SELF = this;
                const data = {
                    id
                }
                this.emitAjax(URL, data, function(result) {
                    _SELF.getCheckItemList();
                });
            }
        },
        getCheckItemList(){
            //获取指标库指标列表
            const URL = this.serverUrl + "/admin/item/index";
            const _SELF = this;
            const data = {
                checklist_id:this.$route.params.id
            }
            this.emitAjax(URL, data, function(result) {
                _SELF.checkListDate = result;
            });
        },
        getCheckList() {
            //刷新指标库列表
            const URL = this.serverUrl + "/admin/checklist/index";
            const _SELF = this;
            this.emitAjax(URL, null, function(result) {
                _SELF.getCheckItem(result)
            });
        },
        getCheckItem(checkList){
            const _this = this;
            for (let index = 0; index < checkList.length; index++) {
                const checkItem = checkList[index];
                if(this.$route.params.id == checkItem.id){
                    _this.currentCheckItem = Object.assign({},checkItem);
                    break;
                }
            }
        }
    },
    mounted(){
        this.getCheckList();
        this.getCheckItemList();
    }
};
</script>
