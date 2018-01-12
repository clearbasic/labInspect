<template>
    <div class="login boxCenter">
        <div class="loginherder">
            <img src="../../assets/images/loginLogo.png" alt="loginLogo">
            <span class="bigger-200" style="display:inline-block;vertical-align:bottom;color:#014099">实验室安全检查管理系统</span>
        </div>
        <div class="loginContent">
            <div class="clearfix">
                <div class="loginBox">
                    <div class="loginFormBox" v-if="isSingle" @keyup="enterLogin($event)">
                        <div class="loginHead">
                            <h3>统一身份认证登录</h3>   
                        </div>
                        <div class="inputGroup">
                            <input type="text" name="username" class="inputText" v-model="username" placeholder="帐号">
                        </div>
                        <div class="inputGroup">
                            <input type="password" name="password" class="inputText" v-model="password" placeholder="密码">
                        </div>
                        <div class="inputGroup clearfix verificationCode">
                            <div class="fl">
                                <input type="text" v-model="verifyCode" class="inputText" placeholder="验证码">
                            </div>
                            <div class="fr">
                                <img :src="serverUrl+'/admin/login/getVerify'" class="verifyCode" alt="验证码" @click="refreshVerifyCode">
                            </div>
                        </div>
                        <div class="remeberme">
                            <label>
                                <input type="checkbox" class="ace" v-model="remeberme" />
                                <span class="lbl"> 记住帐号？</span>
                            </label>
                        </div>
                        <div class="inputGroup clearfix">
                            <div class="fl verificationCode">
                                <button class="loginBtn fl" @click="login"> 登录</button>
                            </div>
                            <div class="fr verificationCodeImg">
                                <input type="reset" class="loginBtn fr" value="重置">
                            </div>
                        </div>
                    </div>
                    <div class="loginFormBox" v-if="!isSingle">
                        <h4 class="header blue lighter bigger">
                            请选择一个身份登录
                        </h4>
                        <div class="space-6"></div>
                        <fieldset>
                            <label  v-if="role_list" v-for="role in role_list" :key="'role'+role.org_id" class="block">
                                <input type="radio" class="ace" @click="selectRoleLogin(role)" name="role">
                                <span class="lbl"> {{role.o_name}}-{{role.g_name}}</span>
                            </label>
                            <div class="space"></div>
                            <div class="clearfix">
                                <button type="button" class="width-35 btn btn-sm btn-primary" @click="submitRoleLogin">
                                    <span class="bigger-110">确定</span>
                                </button>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>         
        <div class="loginfooter">
            <p>技术支持：杭州创高软件科技有限公司 2017 徐州医科大学 版权所有</p>
        </div>
    </div>
</template>
<script>
    import { setLocalData,delLocalData } from "../../assets/common.js";
    export default {
        name:"login",
        data(){
            return {
                username:"",
                password:"",
                verifyCode:"",
                remeberme:false,
                role_list:[],
                isSingle:true,
                selectRole:{},
            }
        },
        methods:{
            login(){
                //登录
                if(!this.isNotEmpty()){return false};
                const _this = this;
                let data={
                    username:this.username.replace(/\s+/g,""),
                    password:this.password.replace(/\s+/g,""),
                    verifyCode:this.verifyCode.replace(/\s+/g,""),
                }
                this.remeberMe();
                const url = this.serverUrl +"/admin/login/login";
                this.emitAjax(url,data,function(result){
                    _this.loginSuccess(result);
                },function(){
                    delLocalData();
                    _this.refreshVerifyCode();
                })
            },
            loginSuccess(result){
                //登录成功做处理
                if(Array.isArray(result)){
                    this.isSingle = false;
                    this.role_list = result;
                }else{
                    setLocalData(result);
                    if(result.menusList[0].url != ''){
                        window.location.href = this.pathName+result.menusList[0].url
                    }else if(result.menusList[0].url == ''&&result.menusList[0].child){
                        window.location.href = this.pathName+result.menusList[0].child[0].url;
                    }else{
                        window.location.href = this.pathName+'/';   
                    }
                }
                
            },
            selectRoleLogin(role){
                this.selectRole = role;
            },
            submitRoleLogin(){
                const _this = this;
                let data=Object.assign({},this.selectRole,{
                    flag:true,
                })
                const url = this.serverUrl +"/admin/login/login";
                this.emitAjax(url,data,function(result){
                    _this.loginSuccess(result);
                },function(){
                    delLocalData();
                    _this.refreshVerifyCode();
                })
            },
            refreshVerifyCode(){
                //刷新验证码
                this.verifyCode = "";
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
                const sessionId = localStorage.getItem("sessionId");
                if(remeberMe){
                    this.username = remeberMe;
                    this.remeberme = true;
                }
                if(authKey&&sessionId&&this.loginUser.username){
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
            },
        },
        mounted(){
            this.info();
        }
    };
</script>
