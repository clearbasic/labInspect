<template>
    <!-- 右侧内容 -->
    <div class="main-content menu" @click="closeSelect">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/menu'" class="active">{{title}}</router-link>
                    </li>
                </ul>
            </div>
            <!-- 右侧主要内容 -->
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{title}}
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm" @click="setShowAdd">添加</button>
                        </div>
                    </h1>
                </div>
                <transition name="fade">
                    <div class="addMenu" v-if="showAdd">
                        <div class="row">
                            <div class="form-horizontal col-lg-8">
                                <div class="form-group">
                                    <label for="title" class="col-sm-2 control-label">菜单名称</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="newMenu.title" id="title" placeholder="菜单名称">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">权限点标识</label>
                                    <div class="col-sm-10">
                                        <div class="position-relative has-feedback">
                                            <input type="text" class="form-control"  v-model="newMenu.rule_id" @click.stop="selectrule=true">
                                            <span class="glyphicon glyphicon-chevron-down form-control-feedback"></span>
                                            <div class="widget-main padding-8 widget-box widget-color-blue2 selectMenu"  v-if="selectrule">
                                                <ul class="tree tree-selectable">
                                                    <li class="tree-item" @click="selectRuleId(0)">
                                                        <div class="tree-branch-name">无</div>
                                                    </li>
                                                    <selectItem :data = "rule" 
                                                        v-for="(rule,index) in rule_tree" 
                                                        :key="'rule'+index" 
                                                        :parentFn = "selectRuleId"
                                                        :currentLevel = "100"
                                                    ></selectItem>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">上级菜单</label>
                                    <div class="col-sm-10">
                                        <div class="position-relative has-feedback">
                                            <input type="text" class="form-control"  v-model="newMenu.pid" @click.stop="select=true">
                                            <span class="glyphicon glyphicon-chevron-down form-control-feedback"></span>
                                            <div class="widget-main padding-8 widget-box widget-color-blue2 selectMenu"  v-if="select">
                                                <ul class="tree tree-selectable">
                                                    <li class="tree-item" @click="selectPid(0)">
                                                        <div class="tree-branch-name">无</div>
                                                    </li>
                                                    <selectItem :data = "menu" 
                                                        v-for="(menu,index) in leftMenu" 
                                                        :key="'nav'+index" 
                                                        :parentFn = "selectPid"
                                                        :currentLevel = "newMenu.level"
                                                    ></selectItem>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="url" class="col-sm-2 control-label">链接</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="newMenu.url" id="url" placeholder="菜单链接">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="icon" class="col-sm-2 control-label">图标</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="newMenu.icon" id="icon" placeholder="图标">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="module" class="col-sm-2 control-label">模块</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="newMenu.module" id="module" placeholder="模块">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sort" class="col-sm-2 control-label">排序</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="newMenu.sort" id="sort" placeholder="排序">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sort" class="col-sm-2 control-label">状态</label>
                                    <label  class="control-label">
                                        <input type="radio" v-model="newMenu.status" value="1" class="ace">
                                        <span class="lbl">
                                            开启
                                        </span>
                                    </label>
                                    <label class="control-label">
                                        <input type="radio" v-model="newMenu.status" value="0" class="ace">
                                        <span class="lbl">
                                            禁用
                                        </span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button class="btn btn-success btn-sm" @click="addMenu">保存</button>
                                        <button class="btn btn-default btn-sm" @click="setHideAdd">取消</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
                <transition name="fade">
                    <div class="dd" v-if="!showAdd">
                        <ol class="dd-list">
                            <NavItem :data = "menu" v-for="(menu,index) in leftMenu" :key="'nav'+index" :parentFn = "editMenu"></NavItem>
                        </ol>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
import VueHead from "../../common/header";
import VueLeft from "../../common/leftMenu";
import leftMenu from '../../../config/leftMenu.js';
import NavItem from './menuItem.vue';
import selectItem from '../selectItem.vue';

export default {
    name: "menu",
    components: {
        VueHead,
        VueLeft,
        NavItem,
        selectItem,
    },
    data() {
        return {
            title: "左侧菜单栏设置",
            newMenu:{
                status:"1",
                pid:0,
                module:"Admin",
                rule_id:0,
            },
            showAdd:false,
            select:false,
            selectrule:false,
            rule_tree:[]
        };
    },
    computed:{
        leftMenu(){
            return this.$store.state.leftMenu.length>0?this.$store.state.leftMenu:leftMenu;
        }
    },
    methods: {
        getRuleTree(){
            //获取权限点树状结构
            const URL = this.serverUrl + '/admin/rules/index';
            const _this = this;
            this.emitAjax(URL,{type:"tree"},function(result){
                _this.rule_tree = result;
            })
        },
        addMenu(){
            const _this = this;
            let url = this.serverUrl + "/admin/menus/add";
            if(this.newMenu.id){
                url = this.serverUrl + "/admin/menus/edit";
            }
            this.emitAjax(url,this.newMenu,function(result){
                _this.setHideAdd();
                _this.$store.dispatch("getMenu");
            })
        } ,
        editMenu(menu){
            this.setShowAdd();
            let obj = Object.assign({},menu);
            this.newMenu = obj;
            if(this.newMenu.url.search("http")<0){
                this.newMenu.url = this.pathName + this.newMenu.url;
            }
        },
        setShowAdd(){
            this.showAdd = true;
        },
        setHideAdd(){
            this.showAdd = false;
            this.newMenu = {
                status:"1",
                module:"Admin",
                pid:0,
                rule_id:0, 
            };
        },
        selectPid(id){
            if(this.newMenu.id == id){
                alert("不能选择自身");
                return false;
            }
            this.newMenu.pid = id;
        },
        selectRuleId(id){
            this.newMenu.rule_id = id;
        },
        closeSelect(){
            this.selectrule = false;
            this.select = false;
        }
    },
    mounted(){
        if(this.checkPermission(this)){
            this.getRuleTree();
        }
    }
};
</script>

