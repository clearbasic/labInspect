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
                                <a href="/">首页</a>
                            </li>
                            <li>
                                <a href="/" class="active">welcome</a>
                            </li>
                        </ul>
                    </div>
                    <!-- 右侧主要内容 -->
                    <div class="page-content">
                        <h1 class="text-center">{{msg}}</h1>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
import VueHead from "./common/header";
import VueLeft from "./common/leftMenu";

export default {
    name: "Index",
    components: {
        VueHead,
        VueLeft
    },
    data() {
        return {
            msg: "欢迎使用实验室安全检查系统"
        }
    }
};
</script>