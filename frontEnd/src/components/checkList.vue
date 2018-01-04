<template>
    <div class="main-content checkList">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/checkList'" class="active">{{title}}</router-link>
                    </li>
                </ul>
            </div>
            <!-- 右侧主要内容 -->
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{title}}
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm" @click="showAdd">添加</button>
                        </div>
                    </h1>
                </div>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="center" width="60px">类别ID</th>
                            <th>指标类别名称</th>
                            <th class="hidden-640">描述</th>
                            <th class="center little">排序</th>
                            <th class="center little">指标数量</th>
                            <th class="center little">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in checkListDate" :key="item.id">
                            <td class="center">{{item.id}}</td>
                            <td @dblclick="showCheckListInput(item.id,item.name,item.intro,item.group_order,'Name')">
                                <span v-if="isName != item.id">{{item.name}}</span>
                                <input v-if="isName == item.id" 
                                    autofocus 
                                    type="text"
                                    v-model="checkListName"
                                    @blur="setCheckListInfo"
                                    @keyup="setCheckListInfo($event)"
                                    @focus="setFlag('Name',item.name)"
                                    class="inlineInput"
                                >
                            </td>
                            <td @dblclick="showCheckListInput(item.id,item.name,item.intro,item.group_order,'Intro')" class="hidden-640">
                                <span v-if="isIntro != item.id">{{item.intro}}</span>
                                <input v-if="isIntro == item.id"
                                    autofocus 
                                    type="text"
                                    v-model="checkListIntro"
                                    @blur="setCheckListInfo"
                                    @keyup="setCheckListInfo($event)"
                                    @focus="setFlag('Intro',item.intro)"
                                    class="inlineInput"
                                >
                            </td>
                            <td @dblclick="showCheckListInput(item.id,item.name,item.intro,item.group_order,'Order')" class="center little">
                                <span v-if="isOrder != item.id">{{item.group_order}}</span>
                                <input v-if="isOrder == item.id"
                                    autofocus
                                    type="text"
                                    v-model="checkListOrder"
                                    @blur="setCheckListInfo"
                                    @keyup="setCheckListInfo($event)"
                                    @focus="setFlag('Order',item.group_order)"
                                    class="inlineInput"
                                    style="width:30px;"
                                >
                            </td>
                            <td class="center">
                                <router-link :to="pathName+'/checkList/'+item.id">{{item.count}}</router-link>
                            </td>
                            <td class="center">
                                <button class="btn btn-xs btn-danger" @click="deleteCheckItem(item.id,item.name,item.count)" title="删除">
                                    <i class="ace-icon fa fa-trash-o bigger-110"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- 添加新指标库 -->
                        <tr v-if="isAppCheckList">
                            <td class="center"></td>
                            <td :class="{'has-error':!newCheckListName}">
                                <input autofocus  type="text" v-model="newCheckListName" class="inlineInput" />
                                <span class="red">*</span>
                            </td>
                            <td>
                                <input type="text" name="name" v-model="newCheckListIntro" class="inlineInput" />
                            </td>
                            <td class="center">
                                <input type="text" name="name" v-model="newCheckListOrder" class="inlineInput" style="width:30px;" />
                            </td>
                            <td class="center"></td>
                            <td class="center">
                                <button class="btn btn-xs btn-success" @click="addCheckList" title="添加">
                                    <i class="ace-icon glyphicon glyphicon-ok bigger-110"></i>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="checkListDate.length ==0">
                            <td colspan="6" align="center">
                                暂无指标库数据
                            </td>
                        </tr>
                    </tbody>
                </table>
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
        VueLeft,
    },
    data() {
        return {
            title: "检查指标类别管理",
            isName: 0,
            isIntro: 0,
            isOrder: 0,
            checkListid: 0,
            checkListName: "", //当前指标分类修改的名字
            checkListIntro: "", //修改描述
            checkListOrder: "", //修改序号
            checkListDate: [],
            isAppCheckList: false,
            newCheckListName: "",
            newCheckListIntro: "",
            newCheckListOrder: "",
            flag:{
                type:"",
                msg:"",
            }
        };
    },
    methods: {
        deleteCheckItem(id, name, count) {
            //删除指标库
            if (count > 0) {
                alert("不可删除<" + name + ">，此指标类库内含有指标项，请移除后在删除！！")
            } else {
                if (window.confirm("是否要删除<" + name + ">,此操作不可逆，请慎重！！")) {
                    const URL = this.serverUrl + "/admin/checklist/del";
                    const _SELF = this;
                    const data = { id };
                    this.emitAjax(URL, data, function(result) {
                        _SELF.getCheckList();
                    });
                }
            }
        },
        showCheckListInput(id, name, intro, group_order, type) {
            //双击显示输入框修改指标库
            this["is" + type] = id;
            this.checkListid = id;
            this.checkListName = name;
            this.checkListIntro = intro;
            this.checkListOrder = group_order;
        },
        setCheckListInfo(event) {
            //按enter触发
            if (event.type == "keyup" && event.key == "Enter") {
                this.setCheckList();
                return false;
            }
            //输入框失去焦点触发
            if (event.type == "blur" && this.checkListid) {
                this.setCheckList();
            }
        },
        setFlag(type,msg){
            this.flag.type = type;
            this.flag.msg = msg;
        },
        setCheckList() {
            //修改指标库信息
            if (
                this.checkListName === "" ||
                this.checkListIntro === "" ||
                this.checkListOrder === ""
            ) {
                alert("修改信息不能为空！！");
                return false;
            }
            
            const URL = this.serverUrl + "/admin/checklist/edit";
            const _SELF = this;
            const data = {
                id: this.checkListid,
                name: this.checkListName,
                intro: this.checkListIntro,
                group_order: this.checkListOrder
            };
            //信息没有修改 不提交
            _SELF.isName = 0;
            _SELF.isIntro = 0;
            _SELF.isOrder = 0;
            _SELF.checkListid = 0;
            if(this.flag.type=="Name" && this.flag.msg == this.checkListName){return false}
            if(this.flag.type=="Intro" && this.flag.msg == this.checkListIntro){return false}
            if(this.flag.type=="Order" && this.flag.msg == this.checkListOrder){return false}
            _SELF.checkListName = "";
            _SELF.checkListIntro = "";
            _SELF.checkListOrder = "";
            this.flag = {
                type:"",
                msg:""
            }
            this.emitAjax(URL, data, function(result) {
                _SELF.getCheckList();
            });
            
        },
        getCheckList() {
            //刷新指标库列表
            const URL = this.serverUrl + "/admin/checklist/index";
            const _SELF = this;
            this.emitAjax(URL, null, function(result) {
                _SELF.checkListDate = result;
            });
        },
        showAdd() {
            this.isAppCheckList = !this.isAppCheckList;
            this.newCheckListOrder = this.checkListDate.length + 1;
        },
        addCheckList() {
            //添加指标库
            if (this.newCheckListName == "") {
                alert("请填写指标库名称!!");
            } else {
                const URL = this.serverUrl + "/admin/checklist/add";
                const _SELF = this;
                const data = {
                    name: this.newCheckListName,
                    intro: this.newCheckListIntro,
                    group_order: this.newCheckListOrder
                };
                _SELF.isAppCheckList = false;
                _SELF.newCheckListName = "";
                _SELF.newCheckListIntro = "";
                _SELF.newCheckListOrder = "";
                this.emitAjax(URL, data, function(result) {
                    _SELF.getCheckList();
                });
            }
        }
    },
    mounted() {
        if(this.checkPermission(this)){
            this.getCheckList();
        }
    }
};
</script>
