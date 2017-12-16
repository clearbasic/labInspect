<template>
    <div class="login-layout">
        <div class="login-container">
            <div class="center">
                <h1>
                    <i class="ace-icon fa fa-flask green"></i>
                    <span class="red">实验室安全管理系统</span>
                    <span class="white" id="id-text2"></span>
                </h1>
            </div>
            <div class="space-6"></div>
            <div class="position-relative">
                <div id="login-box" class="login-box visible widget-box no-border">
                    <div class="widget-body">
                        <div class="widget-main">
                            <h4 class="header blue lighter bigger">
                                <i class="ace-icon fa fa-coffee green"></i>
                                请输入您的帐号密码
                            </h4>
                            <div class="space-6"></div>
                            <form  @keyup="enterLogin($event)">
                                <fieldset>
                                    <label class="block clearfix">
                                        <span class="block input-icon input-icon-right">
                                            <input type="text" class="form-control" v-model="username" placeholder="Username" />
                                            <i class="ace-icon fa fa-user"></i>
                                        </span>
                                    </label>
                                    <label class="block clearfix">
                                        <span class="block input-icon input-icon-right">
                                            <input type="password" class="form-control" v-model="password" placeholder="Password" />
                                            <i class="ace-icon fa fa-lock"></i>
                                        </span>
                                    </label>
                                    <label class="block clearfix row">
                                        <span class="block input-icon input-icon-right col-xs-6">
                                            <input type="text" class="form-control" v-model="verifyCode" placeholder="VerifyCode" />
                                        </span>
                                        <span class="block input-icon input-icon-right col-xs-6">
                                            <img :src="serverUrl+'/admin/login/getVerify'" class="verifyCode" alt="验证码" @click="refreshVerifyCode">
                                        </span>
                                    </label>
                                    <div class="space"></div>
                                    <div class="clearfix">
                                        <label class="inline">
                                            <input type="checkbox" checked class="ace" @click="remeberme = !remeberme" v-if="remeberme" />
                                            <input type="checkbox" class="ace" @click="remeberme = !remeberme" v-if="!remeberme" />
                                            <span class="lbl"> 记住帐号？</span>
                                        </label>
                                        <button type="button" class="width-35 pull-right btn btn-sm btn-primary" @click="login">
                                            <i class="ace-icon fa fa-key"></i>
                                            <span class="bigger-110">登录</span>
                                        </button>
                                    </div>
                                    <div class="space-4"></div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="toolbar clearfix white">
                            <span class="col-xs-12"><h5>如果没有帐号，请联系相关管理人员添加帐号。</h5></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name:"login",
        data(){
            return {
                username:"",
                password:"",
                verifyCode:"",
                remeberme:false,
            }
        },
        methods:{
            login(){
                //登录
                if(!this.isNotEmpty()){return false};
                let data = {
                    data:{
                        username:this.username,
                        password:this.password,
                        verifyCode:this.verifyCode,
                    },
                    router:this.$router,
                    refreshVerifyCode:this.refreshVerifyCode
                }
                this.remeberMe();
                this.$store.dispatch("login",data);
            },
            refreshVerifyCode(){
                //刷新验证码
                $(".verifyCode").attr("src",this.serverUrl+'/admin/login/getVerify?code='+Math.random());
            },
            remeberMe(){
                //记住帐号
                if(this.remeberme){
                    localStorage.setItem("remeberMe",this.username);
                }else{
                    localStorage.removeItem("remeberMe");
                }
            },
            info(){
                //页面初始化
                this.refreshVerifyCode();
                const remeberMe = localStorage.getItem("remeberMe");
                const authKey = localStorage.getItem("authKey");
                if(remeberMe){
                    this.username = remeberMe;
                    this.remeberme = true;
                }
                if(authKey){
                    this.$router.push(this.pathName+"/");
                }
            },
            enterLogin(event){
                if(event.type == "keyup" && event.key=="Enter"){
                    this.login();
                }
            },
            isNotEmpty(){
                if(this.username == ""){
                    alert("请填写用户名！");
                    return false;
                }
                if(this.password ==""){
                    alert("请填写密码！");
                    return false;
                }
                if(this.verifyCode==""){
                    alert("请填写验证码！");
                    return false;
                }
                return true;
            }
        },
        mounted(){
            this.info();
        }
    };
</script>
<style>
    body {
        background-color: #000
    }
    .login-layout {
        background: none;
    }
    .verifyCode {
        max-width:100%;
        height: auto;
    }
</style>
