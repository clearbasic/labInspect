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
                    <select v-model="userInfo.org_id">
                        <option value="0">全部</option>
                        <option :value="org.org_id" v-for="(org,index) in userOrgList" :key="'org'+index">{{org.org_name}}</option>
                    </select>
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
                this.emitAjax(URL,this.userInfo,function(){
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
                for (let index = 0; index < this.orgList.length; index++) {
                    const element = this.orgList[index];
                    if(element.org_level != "lab"){
                        this.userOrgList.push(Object.assign({},element));
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
            console.log(this.user)
            if(this.user){
                this.userInfo = this.user;
            }
        }
    };
</script>