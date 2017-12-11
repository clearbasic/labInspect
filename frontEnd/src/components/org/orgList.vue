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
                                <router-link :to="pathName+'/orgList'" class="active">{{title}}</router-link>
                            </li>
                        </ul>
                    </div>
                    <!-- 右侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{title}}
                                <div class="pull-right">
                                    <router-link class="btn btn-primary btn-sm" tag="button"
                                    :to="{path:pathName+'/orgEdit'}">添加</router-link>
                                </div>
                            </h1>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="center" width="60px">单位ID</th>
                                        <th>单位名称</th>
                                        <th class="center">单位地址</th>
                                        <th>所属单位</th>
                                        <th class="center little">单位级别</th>
                                        <th class="center little">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(org,index) in orgList" :key="'orgList'+index">
                                        <td class="center">{{org.org_id}}</td>
                                        <td>
                                            <router-link :to="{path:pathName+'/orgEdit',query:{org_id:org.org_id}}">{{org.org_name}}</router-link>
                                        </td>
                                        <td>{{org.org_address}}</td>
                                        <td>{{getParentName(org.parent_id)}}</td>
                                        <td class="center">
                                            <span v-if="org.org_level === 'school'">学校</span>
                                            <span v-if="org.org_level === 'college'">院系</span>
                                            <span v-if="org.org_level === 'lab'">实验室</span>
                                        </td>
                                        <td class="center">
                                            <router-link class="btn btn-success btn-xs" tag="button" 
                                                :to="{path:pathName+'/orgEdit',query:{org_id:org.org_id}}">
                                                <i class="ace-icon glyphicon glyphicon-edit bigger-100"></i>    
                                            </router-link>
                                            <button class="btn btn-danger btn-xs" @click="delOrg(org)">
                                                <i class="ace-icon fa fa-trash-o bigger-110"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
                title:"单位列表",
                changeObj:null,
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
            getParentName(id){
                //获取所属单位名称
                let org_name = '无';
                for (let index = 0; index < this.orgList.length; index++) {
                    const element = this.orgList[index];
                    if(element.org_id ==id){
                        org_name = element.org_name;
                        break;
                    }
                }
                return org_name;
            },
            delOrg(org){
                //删除单位
            }
        },
        mounted(){
            this.getOrgList();
        }
    };
</script>