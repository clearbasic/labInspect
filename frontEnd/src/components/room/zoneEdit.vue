<template>
    <div class="zoneEdit form-horizontal">
        <div class="row">
            <div class="col-sm-4">
                <div :class="['form-group','has-feedback',{'has-error':!zone.zone_name}]">
                    <label for="zone_name" class="control-label col-sm-4 col-md-4 col-lg-3">房间名</label>
                    <div class="col-sm-8 col-md-8 col-lg-9">
                        <input type="text" class="form-control" v-model="zone.zone_name" id="zone_name">
                        <span class="form-control-feedback red">*</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="zone_order" class="control-label col-sm-4 col-md-4 col-lg-3">排序编号</label>
                    <div class="col-sm-8 col-md-8 col-lg-9">
                        <input type="text" class="form-control" v-model="zone.zone_order" id="zone_order">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label col-sm-4 col-md-4 col-lg-3">所属单位</label>
                    <div class="col-sm-8 col-md-8 col-lg-9">
                        <select class="form-control" v-model="zone.org_id">
                            <option :value="0">全部</option>
                            <option v-for="org in orgList" :value="org.org_id" :key="'orgList'+org.org_id">{{org.org_name}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <div class="pull-right">
                    <button class="btn btn-default btn-sm" @click="showZoneList">返回</button>
                    <button class="btn btn-success btn-sm" @click="saveZone">保存</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name:"zoneEdit",
        props:{
            showZoneList:{
                type:Function,
                default:null,
            }
        },
        computed:{
            zone(){
                return this.$store.state.currentZone;
            },
            orgList(){
                return this.$store.state.orgList;
            }
        },
        data(){
            return {}
        },
        methods:{
            saveZone(){
                //保存分组
                let url = this.serverUrl + "/admin/zone/add";
                const _this = this;
                if(this.zone.zone_id){
                    url = this.serverUrl + "/admin/zone/edit";
                }
                if(!this.zone.zone_name){
                    alert("分组名必须填写！");
                    return false;
                }
                this.emitAjax(url,this.zone,function(){
                    _this.showZoneList();
                })
            },
            getOrgList(data){
                //获取单位列表
                this.$store.dispatch("getOrgList",data);
            },
        },
        mounted(){
            this.getOrgList();
        }
    };
</script>