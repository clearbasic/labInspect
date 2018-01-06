<template>
    <div :class="{app:isApp,'no-skin':true}">
        <router-view />
    </div>
</template>

<script>
export default {
    name: "app",
    data() {
        return {
            isApp: false
        };
    },
    methods: {
        isAppWatch() {
            //判断是不是手机app访问
            /* const PATHNAME = window.location.search;
            let isApp = PATHNAME.search("isApp");
            if(isApp>0){
                this.isApp = true;
            } */
            /* if(app){
                this.isApp = true;
            } */
        },
        isLogin(){
            //验证登录
            if(!this.loginUser.username){
                this.$router.push(this.pathName+"/login");
            }
        }
    },
    mounted() {
        this.isAppWatch();
        //非登录页面都要验证登录
        if(this.$route.name != "login"){
            this.isLogin();
        }
    }
};
</script>

