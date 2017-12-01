<template>
	<div class="welcome">
        <VueHead></VueHead>
        <div class="main-container" id="main-container">
            <VueLeft show=""></VueLeft>
            <div class="main-content">
                <div class="main-content-inner">
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
                    <div class="page-content">
                        <h1 class="text-center">欢迎使用实验室安全检查系统</h1>
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
            msg: "Welcome to Your Vue.js App"
        }
    }
};
</script>