<template>
    <div class="roowEdit form-horizontal">
        <div class="row">
            <div class="col-sm-4">
                <div :class="['form-group','has-feedback',{'has-error':!room.room_name}]">
                    <label for="room_name" class="control-label col-sm-4 col-md-4 col-lg-3">房间名</label>
                    <div class="col-sm-8 col-md-8 col-lg-9">
                        <input type="text" class="form-control" v-model="room.room_name" id="room_name">
                        <span class="form-control-feedback red">*</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="agent_name" class="control-label col-sm-4 col-md-4 col-lg-3">负责人姓名</label>
                    <div class="col-sm-8 col-md-8 col-lg-9">
                        <input type="text" class="form-control" v-model="room.agent_name" id="agent_name" data-toggle="modal" data-target="#userModal" readonly>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="agent_id" class="control-label col-sm-4 col-md-4 col-lg-3">负责人学工号</label>
                    <div class="col-sm-8 col-md-8 col-lg-9">
                        <input type="text" class="form-control" v-model="room.agent_id" id="agent_id" data-toggle="modal" data-target="#userModal" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="phone" class="control-label col-sm-4 col-md-4 col-lg-3">联系电话</label>
                    <div class="col-sm-8 col-md-8 col-lg-9">
                        <input type="text" class="form-control" v-model="room.phone" id="phone">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="building_name" class="control-label col-sm-4 col-md-4 col-lg-3">楼名</label>
                    <div class="col-sm-8 col-md-8 col-lg-9">
                        <input type="text" class="form-control" v-model="room.building_name" id="building_name">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="room_order" class="control-label col-sm-4 col-md-4 col-lg-3">排序编号</label>
                    <div class="col-sm-8 col-md-8 col-lg-9">
                        <input type="text" class="form-control" v-model="room.room_order" id="room_order">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label col-sm-4 col-md-4 col-lg-3">所属学院</label>
                    <div class="col-sm-8 col-md-8 col-lg-9">
                        <select class="form-control" v-model="room.dept_id" @change="getLabArray(room.dept_id)">
                            <option :value="0">全部</option>
                            <option v-for="college in collegeArray" :value="college.org_id" :key="'college'+college.org_id">{{college.org_name}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label col-sm-4 col-md-4 col-lg-3">所属实验室</label>
                    <div class="col-sm-8 col-md-8 col-lg-9">
                        <select class="form-control" v-model="room.lab_id">
                            <option :value="0">全部</option>
                            <option v-for="lab in labArray" :value="lab.org_id" :key="'lab'+lab.org_id">{{lab.org_name}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label col-sm-4 col-md-4 col-lg-3">房间分组</label>
                    <div class="col-sm-8 col-md-8 col-lg-9">
                        <select class="form-control" v-model="room.zone_id">
                            <option :value="0">无</option>
                            <option v-for="zone in zoneArray" :value="zone.zone_id" :key="'lab'+zone.zone_id">{{zone.zone_name}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <div class="pull-right">
                    <button class="btn btn-default btn-sm" @click="showRoomList">返回</button>
                    <button class="btn btn-success btn-sm" @click="saveRoom">保存</button>
                </div>
            </div>
        </div>
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">  
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">用户管理</h4>
                    </div>
                    <div class="modal-body">
                        <UserList 
                            :sure="selectUser"
                        ></UserList>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import UserList from '../user/userList'
    export default {
        name:"roowEdit",
        props:{
            showRoomList:{
                type:Function,
                default:null,
            }
        },
        components:{UserList},
        computed:{
            room(){
                return this.$store.state.currentRoom;
            },
            orgList(){
                return this.$store.state.orgList;
            }
        },
        watch:{
            orgList(){
                this.getOrgInfo();
            }
        },
        data(){
            return {
                collegeArray:[],
                labArray:[],
                schoolArray:[],
                zoneArray:[],
            }
        },
        methods:{
            saveRoom(){
                //保存房间
                let url = this.serverUrl + "/admin/room/add";
                const _this = this;
                if(this.room.room_id){
                    url = this.serverUrl + "/admin/room/edit";
                }
                if(!this.room.room_name){
                    alert("请填写房间名称！");
                    return false;
                }
                this.emitAjax(url,this.room,function(){
                    _this.showRoomList();
                })
            },
            selectUser(user){
                //选择用户
                this.room.agent_name = user.name;
                this.room.agent_id = user.username;
                $("#userModal").modal("hide");
            },
            getOrgList(data){
                //获取单位列表
                this.$store.dispatch("getOrgList",data);
            },
            getOrgInfo(){
                //单位分类 college school lab
                const _this=this;
                this.collegeArray=[];
                this.labArray=[];
                if(this.orgList.length>0){
                    for (let index = 0; index < this.orgList.length; index++) {
                        const element = this.orgList[index];
                        if(element.org_state == "yes"){
                            _this[element.org_level+"Array"].push(Object.assign({},element));
                        }
                    }
                }
            },
            getLabArray(id){
                //获得实验室列表
                const _this = this;
                this.labArray = [];
                if(this.orgList.length>0){
                    for (let index = 0; index < _this.orgList.length; index++) {
                        const element = _this.orgList[index];
                        if(element.org_level == "lab" && element.org_state == "yes"){
                            if(id != 0 ){
                                if(element.pid == id){
                                    _this.labArray.push(Object.assign({},element));
                                }
                            }else{
                                _this.labArray.push(Object.assign({},element));
                            }
                        }
                    }
                }
            },
            getZoneList(){
                //获得分组列表
                let url = this.serverUrl + "/admin/zone/index";
                const _this = this;
                this.emitAjax(url,null,function(result){
                    _this.zoneArray = result;
                })
            }
        },
        mounted(){
            this.getOrgList();
            this.getZoneList();
        }
    };
</script>