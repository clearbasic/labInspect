<template>
    <div id="navbar" class="navbar navbar-default">
        <div id="navbar-container" class="navbar-container">
            <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebars">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-header pull-left">
                <a href="index.html" class="navbar-brand">
                    <small>
                        <i class="fa fa-leaf"></i>
                        实验室安全检查管理系统
                    </small>
                </a>
            </div>
            <div class="sysSelect pull-left">
                <a href="" data-toggle="dropdown" class="dropdown-toggle">
                    <span><small>实验室安全检查</small></span>
                    <b class="ace-icon fa fa-caret-down"></b>
                </a>
                <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                    <li><a href="">其他系统</a></li>
                    <li><a href="">其他系统</a></li>
                    <li><a href="">其他系统</a></li>
                    <li><a href="">其他系统</a></li>
                </ul>
            </div>
            <div class="navbar-buttons navbar-header pull-right" v-if="loginUser.username">
                <ul class="nav ace-nav">
                    <li class="light-blue">
                        <a href="" data-toggle="dropdown" class="dropdown-toggle">
                            <span class="user-info">
                                <small>欢迎您，{{loginUser.name}},</small>
                                您可以
                            </span>
                            <i class="ace-icon fa fa-caret-down"></i>
                        </a>
                        <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                            <li>
                                <a @click="logout">
                                    <i class="ace-icon fa fa-power-off"></i>
                                    退出
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
import { setLocalData,delLocalData } from "../../assets/common.js";
export default {
    data() {
        return {};
    },
    methods:{
        logout(){
            if(window.confirm("是否要退出本系统！")){
                const url = this.serverUrl +"/admin/login/logout";
                const _this = this;
                this.emitAjax(url,null,function(result){
                    delLocalData();
                    _this.$router.push(_this.pathName+"/login");
                })
            }
        }
    }
};
</script>
<style>
    .sysSelect {
        height: 45px;
        line-height: 45px;
        background: #5db7e9;
        padding: 0 10px;
        position: relative;
        margin-left:6px;
    }
    .sysSelect a {
        color:#fff;
    }
    @media screen and (max-width:480px){
        .sysSelect li {
            text-align:right;
        }
    }
</style>
