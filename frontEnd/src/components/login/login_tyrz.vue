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
export default {
    name: "login",
    data() {
        return {
            username: "",
            tyrz: "",
            verifyCode: "",
            remeberme: false,
            role_list: [],
            isSingle: true,
            selectRole: {},
            frontUrl: "http://192.168.240.81:8090/login",
        }
    },
    methods: {
        login() {
            //登录
            if (!this.isNotEmpty()) { return false };
            const _this = this;
            let data = {
                username: this.username.replace(/\s+/g, ""),
                tyrz: this.tyrz.replace(/\s+/g, ""),
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
            console.log(username)
            if(username){
                this.username = username;
                this.tyrz = "111"
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
            if (this.tyrz == "") {
                alert("请填写密码！");
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
