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
                                <a class="active">{{$route.query.org_id?orgInfo.org_name:"新建单位"}}</a>
                            </li>
                        </ul>
                    </div>
                    <!-- 右侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{$route.query.org_id?orgInfo.org_name:"新建单位"}}
                            </h1>
                        </div>
                        <ul class="list-group form-horizontal">
                            <li class="list-group-item active">
                                基础信息
                            </li>
                            <li class="list-group-item clearfix">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div :class="['form-group','has-feedback',{'has-error':orgInfo.org_name ==''}]">
                                            <label for="org_name" class="col-sm-2 col-lg-4 col-md-5 control-label">单位名称：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.org_name" id="org_name" class="form-control">
                                                <span class="form-control-feedback red">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="org_alias" class="col-sm-2 col-lg-4 col-md-5 control-label">单位别名：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.org_alias" id="org_alias" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="org_address" class="col-sm-2 col-lg-4 col-md-5 control-label">单位地址：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.org_address" id="org_address" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item clearfix">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-2 col-lg-4 col-md-5 control-label">单位级别：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <select class="form-control" v-model="orgInfo.org_level" @change="showParentId">
                                                    <option value="school">学校</option>
                                                    <option value="college">院系</option>
                                                    <option value="lab">实验室</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" v-if="showSchool">
                                            <label class="col-sm-2 col-lg-4 col-md-5 control-label">所属学校：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <select class="form-control" v-model="orgInfo.school_id">
                                                    <option v-for="org in schoolArray" :key="'schoolArray'+org.org_id" :value="org.org_id">
                                                        {{org.org_name}}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" v-if="showCollege">
                                            <label class="col-sm-2 col-lg-4 col-md-5 control-label">所属院系：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <select class="form-control" v-model="orgInfo.college_id">
                                                    <option v-for="org in collegeArray" :key="'collegeArray'+org.org_id" :value="org.org_id">
                                                        {{org.org_name}}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="list-group form-horizontal" v-if="showCollege">
                            <li class="list-group-item active">实验室信息</li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="room" class="col-sm-2 col-lg-4 col-md-5 control-label">房间号：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.room" id="room" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="building" class="col-sm-2 col-lg-4 col-md-5 control-label">楼宇：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.building" id="building" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="campus" class="col-sm-2 col-lg-4 col-md-5 control-label">校区：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.campus" id="campus" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="usage" class="col-sm-2 col-lg-4 col-md-5 control-label">用途：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.usage" id="usage" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="bigcategory" class="col-sm-2 col-lg-4 col-md-5 control-label">大分类：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.bigcategory" id="bigcategory" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="smallcategory" class="col-sm-2 col-lg-4 col-md-5 control-label">小分类：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.smallcategory" id="smallcategory" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contacts" class="col-sm-2 col-lg-4 col-md-5 control-label">安全卫生联系人：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.contacts" id="contacts" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="responsible" class="col-sm-2 col-lg-4 col-md-5 control-label">安全卫生责任人：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.responsible" id="responsible" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="coordinate" class="col-sm-2 col-lg-4 col-md-5 control-label">经纬度：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.coordinate" id="coordinate" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="qualified" class="col-sm-2 col-lg-4 col-md-5 control-label">资质：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.qualified" id="qualified" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="qualified_remarks" class="col-sm-2 col-lg-4 col-md-5 control-label">资质备注：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.qualified_remarks" id="qualified_remarks" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="precautions" class="col-sm-2 col-lg-4 col-md-5 control-label">防护措施：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.precautions" id="precautions" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="precautions_remarks" class="col-sm-2 col-lg-4 col-md-5 control-label">防护措施备注：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.precautions_remarks" id="precautions_remarks" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="hazard_source" class="col-sm-2 col-lg-4 col-md-5 control-label">危险源：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.hazard_source" id="hazard_source" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="hazard_source_remarks" class="col-sm-2 col-lg-4 col-md-5 control-label">危险源备注：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.hazard_source_remarks" id="hazard_source_remarks" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="main_outfire" class="col-sm-2 col-lg-4 col-md-5 control-label">灭火要点：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.main_outfire" id="main_outfire" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="main_outfire_remarks" class="col-sm-2 col-lg-4 col-md-5 control-label">灭火要点备注：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.main_outfire_remarks" id="main_outfire_remarks" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="remarks" class="col-sm-2 col-lg-4 col-md-5 control-label">备注：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7">
                                                <input type="text" v-model="orgInfo.remarks" id="remarks" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="org_state" class="col-sm-2 col-lg-4 col-md-5 control-label">是否开启：</label>
                                            <div class="col-sm-10 col-lg-8 col-md-7" style="line-height:29px;">
                                                <label>
                                                    <input type="radio" class="ace" v-model="orgInfo.org_state" value="yes">
                                                    <span class="lbl">是</span>
                                                </label>
                                                <label>
                                                    <input type="radio" class="ace" v-model="orgInfo.org_state" value="no">
                                                    <span class="lbl">否</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="pull-right">
                                    <router-link :to="pathName+'/orgList'" class="btn btn-default btn-sm" tag="button">返回</router-link>
                                    <button class="btn btn-success btn-sm" @click="editOrgInfo">保存</button>
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
                orgInfo:{
                    org_level:"school",
                },
                showSchool:false,
                showCollege:false,
                schoolArray:[],
                collegeArray:[],
                labArray:[]
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
                this.schoolArray=[];
                this.collegeArray=[];
                this.labArray=[];
                if(this.orgList.length>0){
                    for (let index = 0; index < this.orgList.length; index++) {
                        const element = this.orgList[index];
                        if(element.org_id==this.$route.query.org_id){
                            _this.orgInfo = Object.assign({},element);
                            console.log(element)
                        }
                        _this[element.org_level+"Array"].push(Object.assign({},element));
                    }
                }
            },
            editOrgInfo(){
                const URL = this.serverUrl+'/admin/org/handle';
                const _this=this;
                switch (this.orgInfo.org_level) {
                    case "school":
                        this.orgInfo.pid = 0;
                        break;
                    case "college":
                        this.orgInfo.pid = this.orgInfo.school_id;
                        break;
                    case "lab":
                        this.orgInfo.pid = this.orgInfo.college_id?this.orgInfo.college_id:this.orgInfo.school_id;
                        break;
                    default:
                        break;
                }
                if(this.orgInfo.org_id && this.orgInfo.school_id == this.orgInfo.org_id){
                    alert("不能选择自身作为所属学校");
                    return false;
                }
                if(this.orgInfo.org_id && this.orgInfo.college_id == this.orgInfo.org_id){
                    alert("不能选择自身作为所属院系");
                    return false;
                }
                if(this.orgInfo.org_name == ''){
                    alert("实验室名称为必填项！");
                    return false;
                }
                this.emitAjax(URL,this.orgInfo,function(result){
                    alert("保存成功");
                    _this.$router.push(_this.pathName+"/OrgList");
                })
            },
            showParentId(){
                switch (this.orgInfo.org_level) {
                    case "school":
                        this.showSchool = false;
                        this.showCollege = false;
                        this.orgInfo.college_id = null;
                        this.orgInfo.school_id = null;
                        break;
                    case "college":
                        this.showSchool = true;
                        this.showCollege = false;
                        this.orgInfo.college_id = null;
                        break;
                    case "lab":
                        this.showSchool = true;
                        this.showCollege = true;
                        break;
                    default:
                        break;
                }
            }
        },
        watch:{
            orgList(){
                this.getOrgInfo();
                this.showParentId();
            }
        },
        mounted(){
            this.getOrgList();
        }
    };
</script>
<style>
@media screen and (min-width:992px) {
    .checkorg .form-horizontal .form-group {
        margin-bottom:0; 
    } 
}
.control-label {
    white-space: nowrap;
}

</style>
