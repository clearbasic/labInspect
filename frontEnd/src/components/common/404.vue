<template>
	<div class="wrap">
        <!-- 头部 -->
        <VueHead></VueHead>
        <div class="main-container" id="main-container">
            <!-- 左侧菜单 -->
            <VueLeft></VueLeft>
            <!-- 右侧内容 -->
            <transition name="fade">
                <div class="main-content">
                    <div class="main-content-inner">
                        <!-- 面包屑 -->
                        <div class="breadcrumbs" id="breadcrumbs">
                            <ul class="breadcrumb">
                                <li>
                                    <i class="ace-icon fa fa-home home-icon"></i>
                                    <a href="/">首页</a>
                                </li>
                            </ul>
                        </div>
                        <!-- 右侧主要内容 -->
                        <div class="page-content">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="error-container">
                                        <div class="well">
                                            <h1 class="grey lighter smaller">
                                                <span class="blue bigger-125">
                                                    <i class="ace-icon fa fa-sitemap"></i>
                                                    404
                                                </span>
                                                页面走丢啦！
                                            </h1>

                                            <hr />
                                            <h3 class="lighter smaller">找不到您所要访问的页面！</h3>
                                            <div>
                                                <div class="space"></div>
                                                <h4 class="smaller">您可以选择下列方法：</h4>

                                                <ul class="list-unstyled spaced inline bigger-110 margin-15">
                                                    <li>
                                                        <i class="ace-icon fa fa-hand-o-right blue"></i>
                                                        检查您的url是否输入正确
                                                    </li>

                                                    <li>
                                                        <i class="ace-icon fa fa-hand-o-right blue"></i>
                                                        查看平台使用方法
                                                    </li>

                                                    <li>
                                                        <i class="ace-icon fa fa-hand-o-right blue"></i>
                                                        联系相关管理人员反馈问题
                                                    </li>
                                                </ul>
                                            </div>

                                            <hr />
                                            <div class="space"></div>

                                            <div class="center">
                                                <a class="btn btn-grey" @click="$router.back()">
                                                    <i class="ace-icon fa fa-arrow-left"></i>
                                                    返回
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
	</div>
</template>

<script>
import VueHead from "./header";
import VueLeft from "./leftMenu";
export default {
    name: "error-404",
    components: {
        VueHead,
        VueLeft
    },
    data() {
        return {
            msg: "欢迎使用实验室安全检查管理系统",
        }
    },
    computed:{
        isShow(){
            return this.$store.state.showToast;
        },
        msgText(){
            return this.$store.state.msgText;
        }
    },
    methods:{
        hideToast(){
            this.$store.commit("showToast",{isShow:false});
        }
    }
};
</script>