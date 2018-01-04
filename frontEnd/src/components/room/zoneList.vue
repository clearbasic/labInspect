<template>
    <div class="zoneList">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th class="center little">分组ID</th>
                    <th>分组名</th>
                    <th>所属单位</th>
                    <th class="center little">排序编号</th>
                    <th class="center little">操作</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(zone,index) in zoneList" :key="'zoneList'+index">
                    <td class="center little">{{zone.zone_id}}</td>
                    <td>
                        <router-link :to="pathName+'/zone/'+zone.zone_id">{{zone.zone_name}}</router-link>
                    </td>
                    <td>
                        <span v-if="zone.org_id == 0">全部</span>
                        <span v-for="org in orgList" :key="'org'+org.org_id" v-if="zone.org_id == org.org_id">{{org.org_name}}</span>
                    </td>
                    <td class="center little" @dblclick="isShowOrder=zone.zone_id">
                        <span v-if="isShowOrder != zone.zone_id">{{zone.zone_order}}</span>
                        <input type="text" 
                            v-if="isShowOrder == zone.zone_id" 
                            v-model="zone.zone_order" 
                            style="width:30px;"
                            @blur="editZone(zone)"
                            @focus="currentOrder = zone.zone_order"
                            autofocus="autofocus"
                        >
                    </td>
                    <td class="center little">
                        <div class="hidden-xs btn-group">
                            <button class="btn btn-xs btn-success" @click="showZoneEdit(zone)" title="编辑">
                                <i class="ace-icon glyphicon glyphicon-edit bigger-100"></i>
                            </button>
                            <button class="btn btn-xs btn-danger" @click="deleteZone(zone)" title="删除">
                                <i class="ace-icon fa fa-trash-o bigger-100"></i>
                            </button>
                        </div>
                        <div class="hidden-sm hidden-md hidden-lg">
                            <div class="inline pos-rel">
                                <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto" aria-expanded="false">
                                    <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                    <li>
                                        <a class="tooltip-info blue" data-rel="tooltip" data-original-title="View" @click="showZoneEdit(zone)">
                                            <i class="ace-icon glyphicon glyphicon-edit bigger-100"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="tooltip-info red" data-rel="tooltip" data-original-title="View"  @click="deleteZone(zone)">
                                            <i class="ace-icon fa fa-trash-o bigger-100"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr v-if="zoneList.length == 0">
                    <td colspan="5" class="center">暂无房间分组</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        name:"zoneList",
        props:{
            showZoneEdit:{
                type:Function,
                default:null,
            }
        },
        data(){
            return {
                zoneList:[],
                isShowOrder:0,
                currentOrder:0,
            }
        },
        computed:{
            orgList(){
                return this.$store.state.orgList;
            }
        },
        methods:{
            getZoneList(){
                //获得分组列表
                const _this = this;
                const URL = this.serverUrl+'/admin/zone/index';
                this.emitAjax(URL,null,function(result){
                    _this.zoneList = result;
                })
            },
            deleteZone(zone){
                //删除分组
                const _this = this;
                const URL = this.serverUrl+'/admin/zone/del';
                if(window.confirm("是否删除房间<"+zone.zone_name+">,此操作不可逆，请慎重！")){
                    this.emitAjax(URL,{zone_id:zone.zone_id},function(result){
                        _this.getZoneList();
                    })
                }
            },
            editZone(zone,event){
                //编辑分组排序
                const _this = this;
                const url = this.serverUrl + "/admin/zone/edit";
                this.isShowOrder = 0;
                if(this.currentOrder == zone.zone_order){
                    return false;
                }
                this.emitAjax(url,zone,function(){
                    _this.getZoneList();
                })
            },
            getOrgList(data){
                //获取单位列表
                if(this.$store.state.orgList.length == 0){
                    this.$store.dispatch("getOrgList",data);
                }
            },
        },
        mounted(){
            this.getOrgList();
            this.getZoneList();
        }
    };
</script>
