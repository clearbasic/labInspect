<template>
    <div class="checkGroup">
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
                                <router-link :to="pathName+'/checkGroup'" class="active">{{title}}</router-link>
                            </li>
                        </ul>
                    </div>
                    <!-- 左侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{title}}
                                <div class="pull-right">
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#groupModal">添加</button>
                                </div>
                            </h1>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="center" width="60px">小组ID</th>
                                        <th>小组名称</th>
                                        <th class="center">单位级别</th>
                                        <th class="center">组长名称</th>
                                        <th class="center little">组长电话</th>
                                        <th class="center">组员名称</th>
                                        <th class="center little">排序</th>
                                        <th class="center little">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(group,index) in checkGroupArray" :key="'checkGroupArray'+index">
                                        <td class="center">{{group.group_id}}</td>
                                        <td>{{group.group_name}}</td>
                                        <td class="center">
                                            <span v-if="group.level === 'school'">学校抽查小组</span>
                                            <span v-if="group.level === 'college'">院系复查小组</span>
                                            <span v-if="group.level === 'lab'">实验室自查小组</span>
                                        </td>
                                        <td class="center">{{group.leader_name}} ({{group.leader_id}})</td>
                                        <td class="center">{{group.phone}}</td>
                                        <td class="center">{{group.members}}</td>
                                        <td class="center little">{{group.group_order}}</td>
                                        <td class="center">
                                            <button class="btn btn-success btn-xs" data-target="#groupModal" data-toggle="modal" @click="changeGroup(group)">
                                                <i class="ace-icon glyphicon glyphicon-edit bigger-100"></i>
                                            </button>
                                            <button class="btn btn-danger btn-xs" @click="delGroup(group.group_id,group.group_name)">
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
        <GroupModal
            :getCheckGroupList="getCheckGroupList"
            :changeObj = "changeObj"
            :changeGroup = "changeGroup"
        ></GroupModal>
    </div>
</template>
<script>
    import VueHead from "../common/header";
    import VueLeft from "../common/leftMenu";
    import GroupModal from './groupModal';

    export default {
        name:"checkGroup",
        data(){
            return {
                title:"检查小组管理",
                changeObj:null,
                checkGroupArray:[
                    {
                        group_id:1,
                        group_name:"实验室小组",
                        level:"lab",
                        leader_name:"程兴华",
                        leader_id:"1104857839",
                        phone:"1875637897",
                        members:"刘成，跃兴莹",
                        group_order:"1",
                    }
                ]
            }
        },
        components:{VueHead,VueLeft,GroupModal},
        methods:{
            delGroup(id,name){
                if(confirm("是否要删除<"+name+">检查小组，此操作不可恢复")){
                    //删除代码
                }
            },
            changeGroup(obj){
                if(obj){
                    this.changeObj = Object.assign({},obj);
                }else {
                    this.changeObj = null;
                }
            },
            getCheckGroupList(){
                //获取检查小组列表
            }
        },
        mounted(){

        }
    };
</script>