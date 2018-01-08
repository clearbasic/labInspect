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
                    <input type="file" @change="setFileObject($event)">
                    <button class="btn btn-success btn-sm" @click="submitFile">提交文件</button>
                    <a class="btn btn-primary btn-sm" :href="pathName+'static/importTemplate/room.xls'">下载示例表格</a>
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
            importFile:null,
            fileName:"",
            isfile:false,
            file:null
        };
    },
    methods: {
        setFileObject(event){
            this.file = event.target.files[0]
        },
        submitFile(){
            const URL = this.serverUrl + "/admin/room/roomImport";
            const _this = this;
            let formData = new FormData();
            formData.append("file",this.file);
            this.emitAjaxFile(URL,formData,function(result){
                _this.file = null
               _this.room_list = result;
            })
        }
    },
    mounted() {
        
    }
};
</script>
<style>
    .footer {
        width: auto;
        height: auto;
        padding-top: 10px;
    }
</style>

