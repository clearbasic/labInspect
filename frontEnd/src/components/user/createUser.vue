<template>
    <div class="creteUser">
        <div class="form-horizontal">
            <div :class="['form-group','has-feedback',{'has-error':!userInfo.username&&!user}]">
                <label for="username" class="control-label col-sm-2">用户名/学工号</label>
                <div class="col-sm-10">
                    <input type="text" v-model="userInfo.username" id="username" v-if="user" readonly class="form-control" placeholder="学工号">
                    <input type="text" v-model="userInfo.username" id="username" v-if="!user" class="form-control" placeholder="学工号">
                    <span class="form-control-feedback red">*</span>
                </div>
            </div>
            <div :class="['form-group','has-feedback',{'has-error':!userInfo.password&&!user}]">
                <label for="password" class="control-label col-sm-2">密码</label>
                <div class="col-sm-10">
                    <input type="password" v-model="userInfo.password" id="password" maxlength="18" class="form-control" placeholder="密码">
                    <span class="form-control-feedback red">*</span>
                </div>
            </div>
            <div :class="['form-group','has-feedback',{'has-error':userInfo.password!=userInfo.repassword}]">
                <label for="repassword" class="control-label col-sm-2">确认密码</label>
                <div class="col-sm-10">
                    <input type="password" v-model="userInfo.repassword" id="repassword" maxlength="18" class="form-control" placeholder="确认密码">
                    <span class="form-control-feedback red">*</span>
                </div>
            </div>
            <div :class="['form-group','has-feedback',{'has-error':!userInfo.name}]">
                <label for="name" class="control-label col-sm-2">姓名</label>
                <div class="col-sm-10">
                    <input type="text" v-model="userInfo.name" id="name" class="form-control" placeholder="姓名">
                    <span class="form-control-feedback red">*</span>    
                </div>
            </div>
            <div class="form-group">
                <label for="mobile" class="control-label col-sm-2">手机/电话</label>
                <div class="col-sm-10">
                    <input type="text" v-model="userInfo.mobile" id="mobile" class="form-control" placeholder="手机/电话">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="control-label col-sm-2">邮箱</label>
                <div class="col-sm-10">
                    <input type="text" v-model="userInfo.email" id="email" class="form-control" placeholder="邮箱">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="control-label col-sm-2">单位</label>
                <div class="col-sm-10">
                    <select v-model="userInfo.org_id" @change="setUserLevel">
                        <option value="0">--全部--</option>
                        <option :value="org.org_id" v-for="(org,index) in userOrgList" :key="'org'+index">{{org.org_name}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="control-label col-sm-2">状态</label>
                <div class="col-sm-10">
                    <div class="radio">
                        <label>
                            <input type="radio" v-model="userInfo.person_state" value="yes" class="ace">
                            <span class="lbl">开启</span>
                        </label>
                        <label>
                            <input type="radio" v-model="userInfo.person_state" value="no" class="ace">
                            <span class="lbl">禁用</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="pull-right">
                        <button class="btn btn-default btn-sm" @click="showUserList">返回</button>
                        <button class="btn btn-success btn-sm" @click="saveUser">保存</button>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name:"createUser",
        props:["showUserList","getUserList","user"],
        data(){
            return {
                userInfo:{
                    password:"",
                    org_id:0
                },
                user_level:"",
                userOrgList:[],
            }
        },
        computed:{
            orgList(){
                return this.$store.state.orgList;
            }
        },
        methods:{
            saveUser(){
                const _this= this;
                let URL = this.serverUrl + "/admin/person/add";
                if(!this.userInfo.username || this.userInfo.username == ''){
                    alert("请填写用户名/学工号！");
                    return false;
                }
                
                if(this.user){
                    URL = this.serverUrl +"/admin/person/edit";
                }else{
                    if(!this.userInfo.password || this.userInfo.password == '' || this.userInfo.password.length<6){
                        alert("请填写密码，字数为6~18！");
                        return false;
                    }
                }
                if(!this.userInfo.name || this.userInfo.name == ''){
                    alert("请填写您的姓名！");
                    return false;
                }
                const data = Object.assign({},this.userInfo,{
                    user_level:this.user_level
                })
                this.emitAjax(URL,data,function(){
                    if(_this.loginUser.username == _this.userInfo.username){
                        alert("您修改了自己的信息，请重新登录，便以更新信息！");
                        _this.$store.dispatch("logout");
                    }
                    _this.getUserList();
                    _this.showUserList();
                });
            },
            getOrgList(data){
                //获取单位列表
                this.$store.dispatch("getOrgList",data);
            },
            filterOrgList(){
                this.userOrgList = [];
                const _this = this;
                for (let index = 0; index < this.orgList.length; index++) {
                    const element = this.orgList[index];
                    switch (this.loginUser.user_level) {
                        case 'lab':
                            if(element.org_level =="lab"&&element.org_state !="no"){
                                _this.userOrgList.push(Object.assign({},element));
                            }
                            break;
                        case 'college':
                            if(element.org_level !="school"&&element.org_state !="no"){
                                _this.userOrgList.push(Object.assign({},element));
                            }
                            break;
                        default:
                            if(element.org_state !="no"){
                                _this.userOrgList.push(Object.assign({},element));
                            }
                            break;
                    }
                }
            },
            setUserLevel(){
                const _this = this;
                for (let index = 0; index < _this.userOrgList.length; index++) {
                    const org = _this.userOrgList[index];
                    if(org.org_id == _this.userInfo.org_id){
                        _this.user_level = org.org_level;
                    }
                }
            }
        },
        watch:{
            orgList(){
                this.filterOrgList();
            }
        },
        mounted(){
            this.getOrgList();
            if(this.user){
                this.userInfo = Object.assign({},this.user);
                this.user_level = this.user.user_level;
            }
        }
    };
</script>