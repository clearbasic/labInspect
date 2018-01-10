<template>
    <!-- 右侧内容 -->
    <div class="main-content room">
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
                            <span class="dropdown" slot="right">
                                <button class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                    操作
                                    <i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-info">
                                    <li>
                                        <a @click="showRoomEdit(null)">
                                            <i class="ace-icon glyphicon glyphicon-plus"></i>
                                            添加房间
                                        </a>
                                    </li>
                                    <li>
                                        <router-link class="" :to="pathName+'/importRoom'">
                                            <i class="ace-icon fa fa-cloud-upload"></i>
                                            导入房间
                                        </router-link>
                                    </li>
                                    <li>
                                        <a :href="downUrl" target="_blank">
                                            <i class="ace-icon fa fa-cloud-download"></i>
                                            导出房间
                                        </a>
                                    </li>
                                </ul>
                            </span>
                            <router-link class="btn btn-primary btn-sm" :to="pathName+'/zone'" tag="button">
                                <i class="ace-icon fa fa-reply icon-only hidden-480"></i>
                                房间分组
                            </router-link>
                        </div>
                    </h1>
                </div>
                <RoomList v-if="showComponentType == 'roomList'"
                    :showRoomEdit = "showRoomEdit" :setDownUrl="setDownUrl"
                ></RoomList>
                <RoomEdit v-if="showComponentType == 'roomEdit'"
                    :showRoomList="showRoomList"
                ></RoomEdit>
            </div>
        </div>
    </div>
</template>
<script>
import RoomList from './roomList';
import RoomEdit from './roomEdit.vue';
export default {
    name:"room",
    components: { RoomList,RoomEdit },
    data(){
        return {
            title:"房间管理",
            showComponentType:"roomList",
            downUrl:this.serverUrl+'/admin/room/roomExport',
        }
    },
    methods:{
        showRoomEdit(room){
            //显示房间编辑页面
            this.showComponentType = "roomEdit";
            const length = $(".roomList tbody tr").length;
            if(room){
                this.$store.commit("setCurrentRoom",room);
            }else{
                this.$store.commit("setCurrentRoom",{room_order:length+1});
            }
        },
        showRoomList(){
            //显示房间列表页面
            this.showComponentType = "roomList";
            this.$store.commit("setCurrentRoom");
        },
        setDownUrl(url){
            this.downUrl = this.serverUrl+'/admin/room/roomExport' + url;
        }
    }
};
</script>
