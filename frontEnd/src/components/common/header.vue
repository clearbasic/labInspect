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
                        实验室安全检查系统
                    </small>
                </a>
            </div>
            <div class="sysSelect pull-right">
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
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {};
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
</style>
