<template>
    
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
            <div class="page-content nomargin">
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
                                <div :class="['form-group','has-feedback',{'has-error':!orgInfo.org_name}]">
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
                                            <option value="school" v-if="permission[loginUser.group_level] >= permission.school">学校</option>
                                            <option value="college" v-if="permission[loginUser.group_level] >= permission.college">院系</option>
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
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="campus" class="col-sm-2 col-lg-4 col-md-5 control-label">校区：</label>
                                    <div class="col-sm-10 col-lg-8 col-md-7">
                                        <select v-model="orgInfo.campus" class="form-control">
                                            <option value="">--请选择--</option>
                                            <option :value="xiaoqu.title" v-for="xiaoqu in campus.child" :key="''+xiaoqu.id">{{xiaoqu.title}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contacts" class="col-sm-2 col-lg-4 col-md-5 control-label">安全卫生联系人：</label>
                                    <div class="col-sm-10 col-lg-8 col-md-7">
                                        <input type="text" v-model="orgInfo.contacts" id="contacts" @focus="currentKey('contacts')"
                                            class="form-control" data-toggle="modal" data-target="#userModal" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="responsible" class="col-sm-2 col-lg-4 col-md-5 control-label">安全卫生责任人：</label>
                                    <div class="col-sm-10 col-lg-8 col-md-7">
                                        <input type="text" v-model="orgInfo.responsible" id="responsible" @focus="currentKey('responsible')"
                                            class="form-control" data-toggle="modal" data-target="#userModal" readonly />
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
                                    <label for="usage" class="col-sm-2 col-lg-4 col-md-5 control-label">用途：</label>
                                    <div class="col-sm-10 col-lg-8 col-md-7">
                                        <input type="text" v-model="orgInfo.usage" id="bigcategory" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bigcategory" class="col-sm-2 col-lg-4 col-md-5 control-label">大分类：</label>
                                    <div class="col-sm-10 col-lg-8 col-md-7">
                                        <select v-model="orgInfo.bigcategory" class="form-control">
                                            <option value="">--请选择--</option>
                                            <option :value="big.title" v-for="big in bigcategory.child" :key="''+big.id">{{big.title}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smallcategory" class="col-sm-2 col-lg-4 col-md-5 control-label">小分类：</label>
                                    <div class="col-sm-10 col-lg-8 col-md-7">
                                        <select v-model="orgInfo.smallcategory" class="form-control">
                                            <option value="">--请选择--</option>
                                            <option :value="small.title" v-for="small in smallcategory.child" :key="''+small.id">{{small.title}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            
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
                        <div class="form-group">
                            <button class="btn btn-success btn-sm" @click="editOrgInfo">保存</button>
                            <button class="btn btn-danger btn-sm" @click="delOrg" v-if="orgInfo.org_id">删除</button>
                            <router-link :to="{path:pathName+'/room',query:{lab_id:orgInfo.org_id}}"
                                class="btn btn-default btn-sm" tag="button" v-if="orgInfo.org_level=='lab'">查看房间列表</router-link>
                            <router-link :to="pathName+'/orgList'" class="btn btn-default btn-sm" tag="button">返回</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">  
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">用户管理</h4>
                    </div>
                    <div class="modal-body">
                        <UserModal 
                            :sure="selectUser"
                        ></UserModal>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import UserModal from "../user/userModal";

export default {
    name: "checkorg",
    data() {
        return {
            orgInfo: {
                campus:"",
                bigcategory:"",
                smallcategory:"",
                org_level: "school",
                org_state:"yes"
            },
            showSchool: false,
            showCollege: false,
            schoolArray: [],
            collegeArray: [],
            labArray: [],
            key: "" ,//保存当前要改变的字段
            campus:{},//校区
            usage:{},//用途
            bigcategory:{},//大分类
            smallcategory:{} //小分类
        };
    },
    components: { UserModal },
    computed: {
        orgList() {
            return this.$store.state.orgList;
        },
        sysData(){ return this.$store.state.sysData},
    },
    watch: {
        orgList() {
            this.getOrgInfo();
        },
        orgInfo(){
            this.showParentId();
        },
        sysData(){
            this.filterSysData();
        }
    },
    methods: {
        getOrgList() {
            //获取单位列表
            if(this.$store.state.orgList.length == 0){
                this.$store.dispatch("getOrgList");
            }else{
                this.getOrgInfo();
            }
        },
        getOrgInfo() {
            //初始实验室信息
            const _this = this;
            this.schoolArray = [];
            this.collegeArray = [];
            this.labArray = [];
            if (this.orgList.length > 0) {
                for (let index = 0; index < this.orgList.length; index++) {
                    const element = this.orgList[index];
                    if (element.org_id == this.$route.query.org_id) {
                        _this.orgInfo = Object.assign({}, element);
                    }
                    _this[element.org_level + "Array"].push(Object.assign({}, element));
                }
            }
        },
        editOrgInfo() {
            //编辑实验室
            const URL = this.serverUrl + "/admin/org/handle";
            const _this = this;
            switch (this.orgInfo.org_level) {
                case "school":
                    this.orgInfo.pid = 0;
                    break;
                case "college":
                    this.orgInfo.pid = this.orgInfo.school_id;
                    break;
                case "lab":
                    this.orgInfo.pid = this.orgInfo.college_id
                        ? this.orgInfo.college_id
                        : this.orgInfo.school_id;
                    break;
                default:
                    break;
            }
            if (
                this.orgInfo.org_id &&
                this.orgInfo.school_id == this.orgInfo.org_id
            ) {
                alert("不能选择自身作为所属学校");
                return false;
            }
            if (
                this.orgInfo.org_id &&
                this.orgInfo.college_id == this.orgInfo.org_id
            ) {
                alert("不能选择自身作为所属院系");
                return false;
            }
            if (this.orgInfo.org_name == "") {
                alert("实验室名称为必填项！");
                return false;
            }
            this.emitAjax(URL, this.orgInfo, function(result) {
                alert("保存成功");
                _this.$router.push(_this.pathName + "/OrgList");
            });
        },
        showParentId() {
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
        },
        currentKey(type) {
            this.key = type;
        },
        selectUser(user) {
            this.orgInfo[this.key] = user.name + "(" + user.username + ")";
            this.key = "";
            $("#userModal").modal("hide");
        },
        delOrg(){
            //删除单位
            const _this = this;
            const URL = this.serverUrl+'/admin/org/del';
            if(window.confirm("是否要删除单位<"+this.orgInfo.org_name+">,此操作不可逆，请慎重！")){
                this.emitAjax(URL,{org_id:this.orgInfo.org_id},function(result){
                    _this.$router.push(_this.pathName+'/orgList');
                })
            }
        },
        getSysData(){
            //获取系统信息
            if(this.$store.state.sysData.length==0){
                this.$store.dispatch("getSysData",{type:'tree'});
            }else{
               this.filterSysData();
            }
        },
        filterSysData(){
            this.campus = Object.assign({},this.sysData.campus); 
            this.usage= Object.assign({},this.sysData.usage);//用途
            this.bigcategory= Object.assign({},this.sysData.bigcategory);//大分类
            this.smallcategory= Object.assign({},this.sysData.smallcategory);//小分类
        }
    },
    mounted() {
        this.getOrgList();
        this.getSysData();
    }
};
</script>
<style>
@media screen and (min-width: 992px) {
    .nomargin .form-group {
        margin-bottom: 0;
    }
}
.control-label {
    white-space: nowrap;
}
</style>
