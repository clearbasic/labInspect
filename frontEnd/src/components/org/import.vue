<template>
    <div class="main-content importOrg">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/importOrg'" class="active">{{title}}</router-link>
                    </li>
                </ul>
            </div>
            <!-- 右侧主要内容 -->
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{title}}
                        <div class="pull-right">
                            <router-link class="btn btn-primary btn-sm" :to="pathName+'/orgList'" tag="button">
                                <i class="ace-icon fa fa-reply icon-only hidden-480"></i>
                                单位列表
                            </router-link>
                        </div>
                    </h1>
                </div>
                <div class="import" v-if="org_list.length == 0">
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
                    <a class="file" :href="pathName+'/static/importTemplate/person.xls'">
                        <i class="ace-icon fa fa-cloud-download">下载示例表格</i>
                    </a>
                    <span class="red">
                        请按照示例模板的格式填写数据，否则导入信息有误
                    </span>
                </div>

                <div class="org_list" v-if="org_list.length > 0">
                    <h4>
                        上传数据预览
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered nomargin">
                            <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>单位名称</th>
                                    <th>父级单位</th>
                                    <th>单位级别</th>
                                    <th>地址</th>
                                    <th>校区</th>
                                    <th>单位责任人学工号</th>
                                    <th>单位责任人姓名</th>
                                    <th>单位联系人学工号</th>
                                    <th>单位联系人姓名</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(org,index) in org_list" :key="'org'+index"
                                    v-if="index>=(page-1)*pageCount && index<page*pageCount">
                                    <td>{{index+1}}</td>
                                    <td>{{org[0]}}</td>
                                    <td>{{org[1]}}</td>
                                    <td>{{org[2]}}</td>
                                    <td>{{org[3]}}</td>
                                    <td>{{org[4]}}</td>
                                    <td>{{org[5]}}</td>
                                    <td>{{org[6]}}</td>
                                    <td>{{org[7]}}</td>
                                    <td>{{org[8]}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <page
                        :pages = "pages"
                        :setPage = "setPage"
                        :currentPage = "page"
                    ></page>
                    <button class="btn btn-success btn-sm" @click="submitPerson">导入</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import page from '../common/page';
export default {
    name: "importOrg",
    data() {
        return {
            title: "单位导入",
            org_list: [],
            file: null,
            fileName: "",//上传后保存的文件名称
            orgCount: 0,
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
            const URL = this.serverUrl + "/admin/org/orgImport";
            const _this = this;
            let formData = new FormData();
            formData.append("file", this.file);
            this.emitAjaxFile(URL, formData, function(result) {
                _this.file = null;
                _this.org_list = result.org_list;
                _this.pages = Math.ceil(result.length/_this.pageCount)>0?Math.ceil(result.length/_this.pageCount):1;
                _this.fileName = result.SaveName;
                _this.orgCount = result.count;
            });
        },
        submitPerson() {
            //提交人员
            if(confirm("是否确定导入？")){
                const URL = this.serverUrl + "/admin/org/orgRunimport";
                const _this = this;
                this.emitAjax(URL, { SaveName:this.fileName }, function(result) {
                    _this.$store.commit("showToast", { isShow: true, msg: "导入完成" });
                    _this.$router.push(_this.pathName + "/orgList");
                });
            }
        },
        setPage(page){
            this.page = page;
        }
    }
};
</script>

