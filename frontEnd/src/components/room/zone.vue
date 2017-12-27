<template>
    <!-- 右侧内容 -->
    <div class="main-content zone">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/zone'" class="active">{{title}}</router-link>
                    </li>
                </ul>
            </div>
            <!-- 右侧主要内容 -->
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{title}}
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm" @click="showZoneEdit(null)">添加分组</button>
                            <router-link :to="pathName+'/room'" tag="button" class="btn btn-primary btn-sm">房间管理</router-link>
                        </div>
                    </h1>
                </div>
                <ZoneList
                    v-if="showComponentType === 'zoneList'"
                    :showZoneEdit="showZoneEdit"
                ></ZoneList>
                <zoneEdit
                    v-if="showComponentType === 'zoneEdit'"
                    :showZoneList = "showZoneList"
                ></zoneEdit>
            </div>
        </div>
    </div>
</template>
<script>
import VueHead from "../common/header";
import VueLeft from "../common/leftMenu";
import ZoneList from "./zoneList";
import zoneEdit from "./zoneEdit";

export default {
    name: "zone",
    components: { VueHead, VueLeft, ZoneList, zoneEdit },
    data() {
        return {
            title: "房间分组管理",
            showComponentType:"zoneList",
        };
    },
    methods: {
        showZoneList(){
            //显示分组列表
            this.showComponentType = "zoneList";
            this.$store.commit("setCurrentZone");
        },
        showZoneEdit(zone){
            //显示分组编辑页面
            const length = $(".zoneList tbody tr").length;
            this.showComponentType = "zoneEdit";
            if(zone){
                this.$store.commit("setCurrentZone",zone);
            }else{
                this.$store.commit("setCurrentZone",{zone_order:length+1});
            }
        }
    },
    mounted(){
        this.checkPermission(this);
    }
};
</script>
