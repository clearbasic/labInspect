<template>
    <div class="room">
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
                                <router-link :to="pathName+'/room'" class="active">{{title}}</router-link>
                            </li>
                        </ul>
                    </div>
                    <!-- 右侧主要内容 -->
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                {{title}}
                                <div class="pull-right">
                                    <button class="btn btn-primary btn-sm" @click="showRoomEdit">添加房间</button>
                                    <router-link class="btn btn-primary btn-sm" :to="pathName+'/zone'" tag="button">分组管理</router-link>
                                </div>
                            </h1>
                        </div>
                        <RoomList v-if="showComponentType == 'roomList'"
                            :showRoomEdit = "showRoomEdit"
                        ></RoomList>
                        <RoomEdit v-if="showComponentType == 'roomEdit'"
                            :showRoomList="showRoomList"
                        ></RoomEdit>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import VueHead from "../common/header";
import VueLeft from "../common/leftMenu";
import RoomList from './roomList';
import RoomEdit from './roomEdit.vue';
export default {
    name:"room",
    components: { VueHead,VueLeft,RoomList,RoomEdit },
    data(){
        return {
            title:"房间管理",
            showComponentType:"roomList",
        }
    },
    methods:{
        showRoomEdit(room){
            //显示房间编辑页面
            this.showComponentType = "roomEdit";
            this.$store.commit("setCurrentRoom",room);
        },
        showRoomList(){
            //显示房间列表页面
            this.showComponentType = "roomList";
            this.$store.commit("setCurrentRoom");
        },
    },
    mounted(){
        this.checkPermission(this);
    }
};
</script>
