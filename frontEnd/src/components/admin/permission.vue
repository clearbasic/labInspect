<template>
    <!-- 右侧内容 -->
    <div class="main-content permission">
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
                <div class="addMenu" v-if="showAdd">
                    <div class="form-inline">
                        <div class="form-group">
                            <label for="title" class="control-label">菜单名称</label>
                            <input type="text" class="form-control" v-model="newMenu.name" id="title" placeholder="菜单名称">
                        </div>
                        <div class="form-group">
                            <label class="control-label">上级菜单</label>
                            <select class="form-control" v-model="newMenu.pid">
                                <option value="1">1</option>
                            </select>
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
                                <input type="radio" v-model="newMenu.status" value="yes" class="ace">
                                <span class="lbl">
                                    开启
                                </span>
                            </label>
                            <label>
                                <input type="radio" v-model="newMenu.status" value="no" class="ace">
                                <span class="lbl">
                                    禁用
                                </span>
                            </label>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success btn-sm" @click="setShowAdd">保存</button>
                            <button class="btn btn-default btn-sm" @click="setHideAdd">取消</button>
                        </div>
                    </div>
                </div>
                <div class="dd">
                    <ol class="dd-list">
                        <NavItem :data = "menu" v-for="(menu,index) in leftMenu" :key="'nav'+index" :parentFn = "editMenu"></NavItem>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueHead from "../common/header";
import VueLeft from "../common/leftMenu";
import leftMenu from '../../config/leftMenu.js';
import NavItem from './menuItem.vue';
export default {
    name: "permission",
    components: {
        VueHead,
        VueLeft,
        NavItem
    },
    data() {
        return {
            title: "左侧菜单栏设置",
            newMenu:{
                status:"yes"
            },
            leftMenu:leftMenu,
            showAdd:false
        };
    },
    methods: {
        addMenu(){

        } ,
        editMenu(menu){
            this.showAdd = true;
            let obj = Object.assign({},menu);
            delete obj.child;
            this.newMenu = obj;
        },
        setShowAdd(){
            this.showAdd = true;
        },
        setHideAdd(){
            this.showAdd = false;
            this.newMenu = {
                status:"yes"
            };
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
        max-width: 1180px;
    }
    .addMenu .form-group {
        margin-bottom:15px;
    }
</style>

