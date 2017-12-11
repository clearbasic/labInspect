<template>
    <div class="checkorg">
        <!-- 头部 -->
        <VueHead></VueHead>
        <div class="main-container" id="main-container">
            <!-- 左侧菜单 -->
            <VueLeft show=""></VueLeft>
            <!-- 右侧内容 -->
            <div class="main-content">
                <div class="main-content-inner">
                    <!-- 面包屑 -->
                    <div class="breadcrumbs" id="breadcrumbs">
                        <ul class="breadcrumb">
                            <li>
                                <i class="ace-icon fa fa-home home-icon"></i>
                                <router-link :to="pathName+'/'">首页</router-link>
                            </li>
                            <li>
                                <router-link :to="pathName+'/orgList'">单位列表</router-link>
                            </li>
                            <li>
                                <a class="active">{{$route.query.org_id?"编辑单位":"新建单位"}}</a>
                            </li>
                        </ul>
                    </div>
                    <!-- 右侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{$route.query.org_id?"编辑单位":"新建单位"}}
                            </h1>
                        </div>
                        <div class="form-inline">
                            <ul class="list-group">
                                <li class="list-group-item active">
                                    基础信息
                                </li>
                                <li class="list-group-item clearfix">
                                    <div class="form-group">
                                        <label for="org_name">单位名称：</label>
                                        <input type="text" name="org_name" v-model="orgInfo.org_name" id="org_name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="org_name">单位别名：</label>
                                        <input type="text" name="org_alias" v-model="orgInfo.org_alias" id="org_alias" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="org_name">单位地址：</label>
                                        <input type="text" name="org_address" v-model="orgInfo.org_address" id="org_address" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="org_name">单位级别：</label>
                                        <select name="org_level" id="" class="form-control" v-model="orgInfo.org_level" @change="showParentId">
                                            <option value="school" selected>学校</option>
                                            <option value="college">院系</option>
                                            <option value="lab">实验室</option>
                                        </select>
                                    </div>
                                    <div class="form-group" v-if="showParent">
                                        <label for="org_name">所属单位：</label>
                                        <select name="parent_id" id="" class="form-control" v-model="orgInfo.parent_id">
                                            <option value="school" v-for="org in orgList" :key="'orgList'+org.org_id">
                                                {{org.org_name}}
                                            </option>
                                        </select>
                                    </div>
                                </li>
                            </ul>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="pull-right">
                                        <button class="btn btn-success btn-sm" @click="editOrgInfo">保存</button>
                                        <router-link :to="pathName+'/orgList'" class="btn btn-default btn-sm" tag="button">返回</router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import VueHead from "../common/header";
    import VueLeft from "../common/leftMenu";

    export default {
        name:"checkorg",
        data(){
            return {
                orgInfo:{},
                showParent:false,
            }
        },
        components:{VueHead,VueLeft},
        computed:{
            orgList(){
                return this.$store.state.orgList;
            }
        },
        methods:{
            getOrgList(){
                //获取单位列表
                this.$store.dispatch("getOrgList");
            },
            getOrgInfo(){
                const _this=this;
                if(this.orgList.length>0 && this.$route.query.org_id){
                    for (let index = 0; index < this.orgList.length; index++) {
                        const element = this.orgList[index];
                        if(element.org_id==this.$route.query.org_id){
                            _this.orgInfo = element;
                        }
                    }
                }
            },
            editOrgInfo(){
                const URL = this.serverUrl+'/admin/org/handle';
                const _this=this;
                this.emitAjax(URL,this.orgInfo,function(result){
                    alert("保存成功");
                    _this.$router.push(_this.pathName+"/OrgList");
                })
            },
            showParentId(){
                if(this.orgInfo.parent_id == "college"){
                    this.showParent = true;
                }
            }
        },
        watch:{
            orgList(){
                this.getOrgInfo();
            }
        },
        mounted(){
            this.getOrgList();
        }
    };
</script>