<template>
    <div class="main-content importRoom">
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
                        <div class="pull-right">
                            <router-link class="btn btn-primary btn-sm" :to="pathName+'/room'" tag="button">
                                <i class="ace-icon fa fa-reply icon-only hidden-480"></i>
                                房间列表
                            </router-link>
                        </div>
                    </h1>
                </div>
                <div class="import" v-if="room_list.length == 0">
                    <div class="dropzone dz-clickable">
                        <div class="dz-default dz-message" v-if="!file">
                            <span class="bigger-150 bolder">
                                <i class="ace-icon fa fa-caret-right red"></i>
                                点击下面的图标上传
                            </span>
                            <br>
                            <label class="pointer">
                                <i class="upload-icon ace-icon fa fa-cloud-upload blue fa-5x"></i>
                                <input type="file" class="hide" @change="setFileObject($event)">
                            </label>
                        </div>
                        <div class="dz-preview dz-file-preview dz-processing dz-error" v-if="file">
                            <div class="dz-details">
                                <div class="dz-filename">
                                    <span>
                                        {{file.name}}
                                    </span>
                                </div>
                            </div>
                            <div class="dz-size">
                                {{file.size/1000}} KB
                            </div>
                            <a  class="dz-remove" @click="file=null">删除</a>
                            <div class="dz-error-mark" @click="file=null"></div>
                        </div>
                    </div>
                    <button class="btn btn-success btn-sm" @click="submitFile" v-if="file">提交文件</button>
                    <button class="btn btn-defualt btn-sm" v-if="!file">提交文件</button>
                    <a class="file" :href="pathName+'/static/importTemplate/room.xls'">
                        <i class="ace-icon fa fa-cloud-download">下载示例表格</i>
                    </a>
                    <span class="red">
                        请按照示例模板的格式填写数据，否则导入信息有误
                    </span>
                </div>
                <div class="room_list" v-if="room_list.length > 0">
                    <h4>
                        上传数据预览
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered nomargin">
                            <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>所属院系</th>
                                    <th>实验室名称</th>
                                    <th>房间分组</th>
                                    <th>房间名称</th>
                                    <th>楼宇</th>
                                    <th>安全责任人学工号</th>
                                    <th>安全责任人姓名</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(room,index) in room_list" :key="'room'+index" 
                                    v-if="index>=(page-1)*pageCount && index<page*pageCount">
                                    <td>{{index+1}}</td>
                                    <td>{{room[0]}}</td>
                                    <td>{{room[1]}}</td>
                                    <td>{{room[2]}}</td>
                                    <td>{{room[3]}}</td>
                                    <td>{{room[4]}}</td>
                                    <td>{{room[5]}}</td>
                                    <td>{{room[6]}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <page
                        :pages = "pages"
                        :setPage = "setPage"
                        :currentPage = "page"
                    ></page>
                    <button class="btn btn-success btn-sm" @click="submitRoom">导入</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import page from '../common/page';
export default {
    name: "importRoom",
    data() {
        return {
            title: "房间导入",
            room_list: [],
            file: null,
            fileName: "",//上传后保存的文件名称
            roomCount: 0,
            page:1,
            pages:1,
            pageCount:20,
        };
    },
    components:{page},
    methods: {
        setFileObject(event) {
            const file = event.target.files[0];
            const type = file.name.split(".").pop();
            if (type != "xlsx" && type != "xls") {
                alert("文件格式必须为xls、xlsx");
                return false;
            }
            this.file = file;
        },
        submitFile() {
            const URL = this.serverUrl + "/admin/room/roomImport";
            const _this = this;
            let formData = new FormData();
            formData.append("file", this.file);
            this.emitAjaxFile(URL, formData, function(result) {
                _this.file = null;
                _this.room_list = result.room_list;
                _this.pages = Math.ceil(result.length/_this.pageCount)>0?Math.ceil(result.length/_this.pageCount):1;
                _this.fileName = result.SaveName;
                _this.roomCount = result.count;
            });
        },
        submitRoom() {
            //提交房间
            if(confirm("是否确定导入？")){
                const URL = this.serverUrl + "/admin/room/roomRunimport";
                const _this = this;
                this.emitAjax(URL, { SaveName:this.fileName }, function(result) {
                    _this.$store.commit("showToast", { isShow: true, msg: "导入完成" });
                    _this.$router.push(_this.pathName + "/room");
                });
            }
        },
        setPage(page){
            this.page = page;
        }
    }
};
</script>

