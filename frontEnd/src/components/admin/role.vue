<template>
    <!-- 右侧内容 -->
    <div class="main-content role">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/role'" class="active">{{title}}</router-link>
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
                    <div class="addRole" v-if="showAdd">
                        <div class="row">
                            <div class="form col-lg-6">
                                <div class="form-group">
                                    <label for="title" class="control-label">菜单名称</label>
                                    <input type="text" class="form-control" v-model="newRole.title" id="title" placeholder="菜单名称">
                                </div>
                                <div class="form-group">
                                    <label for="url" class="control-label">链接</label>
                                    <input type="text" class="form-control" v-model="newRole.url" id="url" placeholder="菜单链接">
                                </div>
                                <div class="form-group">
                                    <label for="icon" class="control-label">图标</label>
                                    <input type="text" class="form-control" v-model="newRole.icon" id="icon" placeholder="图标">
                                </div>
                                <div class="form-group">
                                    <label for="module" class="control-label">模块</label>
                                    <input type="text" class="form-control" v-model="newRole.module" id="module" placeholder="模块">
                                </div>
                                <div class="form-group">
                                    <label for="sort" class="control-label">排序</label>
                                    <input type="text" class="form-control" v-model="newRole.sort" id="sort" placeholder="排序">
                                </div>
                                <div class="form-group">
                                    <label for="sort" class="control-label">状态</label>
                                    <label>
                                        <input type="radio" v-model="newRole.status" value="1" class="ace">
                                        <span class="lbl">
                                            开启
                                        </span>
                                    </label>
                                    <label>
                                        <input type="radio" v-model="newRole.status" value="0" class="ace">
                                        <span class="lbl">
                                            禁用
                                        </span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success btn-sm" @click="addRole">保存</button>
                                    <button class="btn btn-default btn-sm" @click="setHideAdd">取消</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
                <transition name="fade">
                    <table class="table table-bordered table-hover">

                    </table>
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
import selectItem from './selectItem.vue';

export default {
    name: "role",
    components: {
        VueHead,
        VueLeft,
        selectItem,
    },
    data() {
        return {
            title: "角色设置",
            newRole:{},
            showAdd:false,
            role_list:[],
            role_tree:[],
        };
    },
    methods: {
        init(){
            this.getRoleList();
            this.getRoleTree();
        },
        getRuleTree(){
            //获取权限点树状结构
            const URL = this.serverUrl + '/admin/rules/index';
            const _this = this;
            this.emitAjax(URL,{type:"tree"},function(result){
                _this.rule_tree = result;
            })
        },
        getRoleList(){
            //获取角色列表
        },
        getRoleTree(){
            //获取角色树状列表
        },
        addRole(){},
        setShowAdd(){
            this.showAdd = true;
        },
        setHideAdd(){
            this.showAdd = false;
            this.newRole = {};
        }
    },
    mounted(){
        if(this.checkPermission(this)){
            this.init();
        }
    }
};
</script>

