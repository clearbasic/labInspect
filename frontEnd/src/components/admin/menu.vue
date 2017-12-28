<template>
    <!-- 右侧内容 -->
    <div class="main-content menu">
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
                        <div class="form">
                            <div class="form-group">
                                <label for="title" class="control-label">菜单名称</label>
                                <input type="text" class="form-control" v-model="newMenu.title" id="title" placeholder="菜单名称">
                            </div>
                            <div class="form-group">
                                <label for="rule_id" class="control-label">权限点标识</label>
                                <input type="text" class="form-control" v-model="newMenu.rule_id" id="rule_id" placeholder="权限点标识">
                            </div>
                            <div class="form-group">
                                <label class="control-label">上级菜单</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control"  v-model="newMenu.pid" @focus="select=true">
                                    <div class="widget-main padding-8 widget-box widget-color-blue2 selectMenu"  v-if="select">
                                        <ul class="tree tree-selectable">
                                            <MenuSelect :data = "menu" v-for="(menu,index) in leftMenu" :key="'nav'+index" :parentFn = "selectPid"></MenuSelect>
                                        </ul>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <label for="url" class="control-label">链接</label>
                                <input type="text" class="form-control" v-model="newMenu.url" id="url" placeholder="菜单链接">
                            </div>
                            <div class="form-group">
                                <label for="icon" class="control-label">图标</label>
                                <input type="text" class="form-control" v-model="newMenu.icon" id="icon" placeholder="图标">
                            </div>
                            <div class="form-group">
                                <label for="sort" class="control-label">排序</label>
                                <input type="text" class="form-control" v-model="newMenu.sort" id="sort" placeholder="排序">
                            </div>
                            <div class="form-group">
                                <label for="sort" class="control-label">状态</label>
                                <label>
                                    <input type="radio" v-model="newMenu.status" value="1" class="ace">
                                    <span class="lbl">
                                        开启
                                    </span>
                                </label>
                                <label>
                                    <input type="radio" v-model="newMenu.status" value="0" class="ace">
                                    <span class="lbl">
                                        禁用
                                    </span>
                                </label>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success btn-sm" @click="addMenu">保存</button>
                                <button class="btn btn-default btn-sm" @click="setHideAdd">取消</button>
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
import VueHead from "../common/header";
import VueLeft from "../common/leftMenu";
import leftMenu from '../../config/leftMenu.js';
import NavItem from './menuItem.vue';
import MenuSelect from './menuSelect.vue';
export default {
    name: "menu",
    components: {
        VueHead,
        VueLeft,
        NavItem,
        MenuSelect
    },
    data() {
        return {
            title: "左侧菜单栏设置",
            newMenu:{
                status:"1",
                pid:0,
            },
            showAdd:false,
            select:false,
        };
    },
    computed:{
        leftMenu(){
            return this.$store.state.leftMenu.length>0?this.$store.state.leftMenu:leftMenu;
        }
    },
    methods: {
        addMenu(){
            this.setShowAdd();
            const _this = this;
            let url = this.serverUrl + "/admin/menus/add";
            if(this.newMenu.id){
                url = this.serverUrl + "/admin/menus/edit";
            }
            console.log(this.newMenu)
            this.emitAjax(url,this.newMenu,function(result){
                _this.setHideAdd();
                _this.$store.dispatch("getMenu");
            })
        } ,
        editMenu(menu){
            this.showAdd = true;
            let obj = Object.assign({},menu);
            console.log(obj);
            delete obj.child;
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
            };
        },
        selectPid(id){
            this.newMenu.pid = id;
            this.select = false;
        }
    },
};
</script>

<style>
    .dd-item button {
        line-height: 1px;
        outline: none;
    }
    .dd {
        max-width: 1980px;
    }
    .selectMenu {
        width: 100%;
        position: absolute;
        left: 0;
        top:30px;
        background: #fff;
        z-index: 10;
        max-height:300px;
        overflow-y:auto;
    }
</style>

