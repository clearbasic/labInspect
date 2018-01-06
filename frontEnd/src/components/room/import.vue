<template>
    <div class="main-content checkList">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/importRoom'" class="active">{{title}}</router-link>
                    </li>
                </ul>
            </div>
            <!-- 右侧主要内容 -->
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{title}}
                    </h1>
                </div>
                <div class="room_list" v-if="room_list.length == 0">
                    
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    name: "importRoom",
    data() {
        return {
            title: "房间导入",
            room_list:[],
        };
    },
    methods: {
        
    },
    mounted() {
        
    }
};
</script>
