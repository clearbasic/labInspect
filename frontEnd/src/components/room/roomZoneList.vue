<template>
    <div class="zone">
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
                                <router-link :to="pathName+'/zone'">分组列表</router-link>
                            </li>
                            <li>
                                <router-link :to="pathName+'/zone'" class="active">{{currentZone.zone_name}}</router-link>
                            </li>
                        </ul>
                    </div>
                    <!-- 右侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{currentZone.zone_name}}的房间列表
                                <div class="pull-right">
                                    <router-link :to="pathName+'/zone'" tag="button" class="btn btn-primary btn-sm">返回</router-link>
                                    <router-link :to="pathName+'/room'" tag="button" class="btn btn-primary btn-sm">房间列表</router-link>
                                </div>
                            </h1>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center little">房间ID</th>
                                    <th>房间名</th>
                                    <th class="hidden-640">责任人</th>
                                    <th class="center little hidden-640">联系电话</th>
                                    <th class="center hidden-640">所属院系</th>
                                    <th class="center hidden-640">所属实验室</th>
                                    <th class="center little hidden-640">所属分组</th>
                                    <th class="center little">排序编号</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(room,index) in roomZoneList" :key="'roomList'+index">
                                    <td class="center little">{{room.room_id}}</td>
                                    <td>{{room.room_name}}</td>
                                    <td class="hidden-640">{{room.agent_name}}({{room.agent_id}})</td>
                                    <td class="center little hidden-640">{{room.phone}}</td>
                                    <td class="center hidden-640">{{room.dept_name}}</td>
                                    <td class="center hidden-640">{{room.lab_name}}</td>
                                    <td class="center hidden-640">
                                        <span v-if="room.zone_id == 0">无</span>
                                        <span v-for="zone in zone_list" :key="'zone'+zone.zone_id" v-if="room.zone_id == zone.zone_id">{{zone.zone_name}}</span>
                                    </td>
                                    <td class="center little" @dblclick="isShowOrder=room.room_id">
                                        {{room.room_order}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
    name: "roomZone",
    components: { VueHead, VueLeft},
    data() {
        return {
            title:"",
            roomZoneList:[],
            currentZone:{},
            zone_list:[],
        };
    },
    methods: {
        getZoneRoomList(){
             //获取房间列表
            const _this = this;
            const URL = this.serverUrl+'/admin/room/index';
            this.emitAjax(URL,{zone_id:this.$route.params.id},function(result){
                _this.roomZoneList = result;
            })
        },
        getZoneList(){
            //获得分组列表
            const _this = this;
            const URL = this.serverUrl+'/admin/zone/index';
            this.emitAjax(URL,null,function(result){
                _this.zone_list = result;
                for (let index = 0; index < result.length; index++) {
                    const zone = result[index];
                    if(zone.zone_id == _this.$route.params.id){
                        _this.currentZone = zone;
                        break;
                    }
                }
            })
        },
    },
    mounted(){
        if(this.checkPermission(this)){
            this.getZoneRoomList();
            this.getZoneList();
        };
    }
};
</script>
