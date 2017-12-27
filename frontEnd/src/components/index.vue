<template>
	<div class="welcome">
        <!-- 头部 -->
        <VueHead></VueHead>
        <div class="main-container" id="main-container">
            <!-- 左侧菜单 -->
            <VueLeft></VueLeft>
            <!-- 右侧内容 -->
            <transition name="fade">
                <router-view />
            </transition>
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
            msg: "欢迎使用实验室安全检查管理系统"
        }
    }
};
</script>