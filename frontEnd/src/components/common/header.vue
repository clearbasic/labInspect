<template>
    <div id="navbar" class="navbar navbar-default">
        <div id="navbar-container" class="navbar-container">
            <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebars">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-header pull-left">
                <router-link :to="pathName+'/'" class="navbar-brand">
                    <small>
                        <i class="fa fa-leaf"></i>
                        实验室安全检查管理系统
                    </small>
                </router-link>
            </div>
            <div class="sysSelect pull-left">
                <a href="" data-toggle="dropdown" class="dropdown-toggle">
                    <span><small>实验室安全检查</small></span>
                    <b class="ace-icon fa fa-caret-down"></b>
                </a>
            </div>
            <div class="navbar-buttons navbar-header pull-right" v-if="loginUser.username">
                <ul class="nav ace-nav">
                    <li class="light-blue">
                        <a href="" data-toggle="dropdown" class="dropdown-toggle">
                            <span class="user-info">
                                <small>欢迎您，{{loginUser.name}},</small>
                                {{loginUser.username}}
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
export default {
    data() {
        return {};
    },
    methods:{
        logout(){
            if(window.confirm("是否要退出本系统！")){
                this.$store.dispatch("logout");
            }
        }
    }
};
</script>
