<template>
	<div class="wrap">
        <!-- 头部 -->
        <VueHead></VueHead>
        <div class="main-container" id="main-container">
            <!-- 左侧菜单 -->
            <VueLeft></VueLeft>
            <!-- 右侧内容 -->
            <transition name="fade">
                <div class="main-content" v-if="$route.name=='index'">
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
                <router-view />
            </transition>
        </div>
        <Toast :show="isShow" :msg="msgText" :hide="hideToast"></Toast>
	</div>
</template>

<script>
import VueHead from "./common/header";
import VueLeft from "./common/leftMenu";
import Toast from './common/toast.vue';
export default {
    name: "Index",
    components: {
        VueHead,
        VueLeft,
        Toast
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