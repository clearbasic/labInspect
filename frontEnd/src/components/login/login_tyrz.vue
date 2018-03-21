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
                        <div class="loginType blue">
                            <i class="ace-icon fa fa-credit-card pointer" 
                                v-if="loginType=='local'" 
                                @click="loginType='tyrz'"
                                title="点击切换登录方式"
                            ></i>
                            <i class="ace-icon fa fa-desktop pointer" 
                                v-else @click="loginType='local'"
                                title="点击切换登录方式"></i>
                        </div>
                        <div class="loginHead">
                            <h3 v-if="loginType=='local'">本地登录</h3>
                            <h3 v-else>统一身份认证登录</h3>
                        </div>
                        <div v-if="loginType=='local'">
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
                        <div class="inputGroup" v-else>
                            <a class='btn btn-block btn-primary' :href="'http://cas.xzhmu.edu.cn/cas/login?service='+serverUrl+'/admin/login/login_tyrz?redirectUrl='+frontUrl">统一身份认证登录</a>
                        </div>
                    </div>
                    <div class="loginFormBox" v-if="!isSingle">
                        <h4 class="header blue lighter bigger">
                            请选择一个身份登录
                        </h4>
                        <div class="space-6"></div>
                        <fieldset>
                            <label v-if="role_list" v-for="role in role_list" :key="'role'+role.org_id" class="block">
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
import { setLocalData, delLocalData } from "../../assets/common.js";
import { frontUrl } from '../../config/server.js'
import md5 from "crypto-js/md5";
export default {
    name: "login",
    data() {
        return {
            username: "",
            password:"",
            tyrz: "",
            verifyCode: "",
            remeberme: false,
            role_list: [],
            isSingle: true,
            selectRole: {},
            frontUrl: frontUrl,
            time: "",
            loginType: "local",
        }
    },
    methods: {
        login() {
            //登录
            if (!this.isNotEmpty()) { return false };
            const _this = this;
            let data = {}

            if(this.loginType == 'local'){
                data = {
                    username:this.username.replace(/\s+/g,""),
                    password:this.password.replace(/\s+/g,""),
                    verifyCode:this.verifyCode.replace(/\s+/g,""),
                }
            }else{
                data = {
                    username: this.username.replace(/\s+/g, ""),
                    tyrz: this.tyrz.replace(/\s+/g, ""),
                    time: this.time
                }
            }
            this.remeberMe();
            const url = this.serverUrl + "/admin/login/login";
            this.emitAjax(url, data, function (result) {
                _this.loginSuccess(result);
            }, function () {
                delLocalData();
                _this.refreshVerifyCode();
            })
        },
        loginSuccess(result) {
            //登录成功做处理
            if (Array.isArray(result)) {
                this.isSingle = false;
                this.role_list = result;
            } else {
                setLocalData(result);
                if (result.menusList[0].url != '') {
                    window.location.href = this.pathName + result.menusList[0].url
                } else if (result.menusList[0].url == '' && result.menusList[0].child) {
                    window.location.href = this.pathName + result.menusList[0].child[0].url;
                } else {
                    window.location.href = this.pathName + '/';
                }
            }

        },
        selectRoleLogin(role) {
            this.selectRole = role;
        },
        submitRoleLogin() {
            const _this = this;
            let data = Object.assign({}, this.selectRole, {
                flag: true,
            })
            const url = this.serverUrl + "/admin/login/login";
            this.emitAjax(url, data, function (result) {
                _this.loginSuccess(result);
            }, function () {
                delLocalData();
                _this.refreshVerifyCode();
            })
        },
        refreshVerifyCode() {
            //刷新验证码
            this.verifyCode = "";
            $(".verifyCode").attr("src", this.serverUrl + '/admin/login/getVerify?code=' + Math.random());
        },
        remeberMe() {
            //记住帐号
            if (this.remeberme) {
                localStorage.setItem("remeberMe", this.username);
            } else {
                localStorage.removeItem("remeberMe");
            }
        },
        info() {
            //页面初始化
            this.refreshVerifyCode();
            const remeberMe = localStorage.getItem("remeberMe");
            const authKey = localStorage.getItem("authKey");
            const sessionId = localStorage.getItem("sessionId");
            if (remeberMe) {
                this.username = remeberMe;
                this.remeberme = true;
            }
            if (authKey && sessionId && this.loginUser.username) {
                this.$router.push(this.pathName + "/");
            }
            const username = this.getCookie("tyrz");
            if (username) {
                this.loginType = "tyrz"
                this.username = username;
                this.time = Date.parse(new Date());
                this.tyrz = md5(this.username + "_" + this.time + "_chingo").toString();
                this.login();
            }
            
        },
        enterLogin(event) {
            if (event.type == "keyup" && event.key == "Enter") {
                this.login();
            }
        },
        isNotEmpty() {
            if (this.username == "") {
                alert("请填写用户名！");
                return false;
            }
            if(this.password =='' && this.loginType=='local'){
                alert("密码不能为空！");
                return false;
            }
            if(this.verifyCode =='' && this.loginType=='local'){
                alert("验证码不能为空！");
                return false;
            }
            if (this.tyrz == "" && this.loginType=='tyrz') {
                alert("验证码不正确！");
                return false;
            }
            return true;
        },
        getCookie(c_name) {
            if (document.cookie.length > 0) {
                let c_start = document.cookie.indexOf(c_name + "=")
                if (c_start != -1) {
                    c_start = c_start + c_name.length + 1
                    let c_end = document.cookie.indexOf(";", c_start)
                    if (c_end == -1) c_end = document.cookie.length
                    return unescape(document.cookie.substring(c_start, c_end));
                }
            }
            return ""
        }
    },
    mounted() {
        this.info();
    }
};
</script>
