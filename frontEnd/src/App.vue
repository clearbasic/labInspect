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
<style>
.app #navbar {
    display: none !important;
}
.app #sidebar {
    display: none !important;
}
.app #breadcrumbs {
    display: none !important;
}
a:hover {
    cursor: pointer;
}
.inlineInput {
    display: inline-block;
    width: 100%;
    max-width: 160px;
}
.app .page-content {
    padding-left: 0;
    padding-right: 0;
}
.has-error input[type="text"],
.has-error input[type="password"] {
    border-color: #f00;
}
.form-group h5 {
    margin: 0;
}
.table-responsive {
    overflow-y: auto;
}
.control-label {
    white-space: nowrap;
}
input[readonly]:hover {
    cursor: pointer;
}
.fade-enter-active {
    transition: opacity 0.5s
}
	
.fade-enter,.fade-leave-active {
    opacity: 0
}
.dd-item button {
        line-height: 1px;
        outline: none;
    }
.dd {
    max-width: 1980px;
}
.selectMenu {
    width: 100%;
    position: absolute;
    left: 0;
    top:30px;
    background: #fff;
    z-index: 10;
    max-height:300px;
    overflow-y:auto;
}
@media only screen and (max-width: 640px) {
    .hidden-640 {
        display: none !important;
    }
}
@media screen and (min-width: 768px) {
    .little {
        width: 80px;
    }
}
@media screen and (min-width: 992px) {
    .little {
        width: 110px;
    }
}
@media screen and (min-width: 1200px) {
    .little {
        width: 140px;
    }
}
</style>

