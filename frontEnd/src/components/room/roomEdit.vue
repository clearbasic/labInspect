<template>
    <div class="roowEdit">
        <div class="form-horizontal">
            <div class="row form-group">
                <div class="col-xs-12 red" v-if="!room.room_id">
                    注：房间名不能含有(，~)这两种符号 。如需批量添加请以逗号隔开，中、英文逗号不能混用。例如：A103,A104,A104~110。~表示房间名按顺序添加
                </div>
            </div>
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
                            <select class="form-control" v-model="room.dept_id">
                                <option :value="0">--全部--</option>
                                <option v-for="college in collegeArray" :value="college.org_id" :key="'college'+college.org_id">{{college.org_name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" v-if="room.dept_id">
                    <div class="form-group">
                        <label class="control-label col-sm-4 col-md-4 col-lg-3">所属实验室</label>
                        <div class="col-sm-8 col-md-8 col-lg-9">
                            <select class="form-control" v-model="room.lab_id">
                                <option :value="0">--全部--</option>
                                <option v-for="lab in labArray" :value="lab.org_id" :key="'lab'+lab.org_id" v-if="lab.pid == room.dept_id">{{lab.org_name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" v-if="room.lab_id">
                    <div class="form-group">
                        <label class="control-label col-sm-4 col-md-4 col-lg-3">房间分组</label>
                        <div class="col-sm-8 col-md-8 col-lg-9">
                            <select class="form-control" v-model="room.zone_id">
                                <option :value="0">--无--</option>
                                <option v-for="zone in zoneArray" :value="zone.zone_id" :key="'lab'+zone.zone_id" v-if="zone.org_id == room.lab_id">{{zone.zone_name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-md-offset-4 col-lg-offset-3 col-sm-8 col-md-8 col-lg-9">
                            <button class="btn btn-success btn-sm" @click="editRoom" v-if="room.room_id">修改</button>
                            <button class="btn btn-success btn-sm" @click="createRoom" v-if="!room.room_id">保存</button>
                            <button class="btn btn-success btn-sm" @click="getNewRoomList" v-if="!room.room_id" data-toggle="modal" data-target="#roomNameModal">预览房间名</button>
                            <button class="btn btn-default btn-sm" @click="showRoomList">返回</button>
                        </div>
                    </div>
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
                        <UserModal 
                            :sure="selectUser"
                        ></UserModal>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="roomNameModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">  
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">房间名称预览</h4>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group clearfix">
                            <li v-for="roomName in roomNameList" :key="roomName" class="list-group-item col-sm-2">
                                {{roomName}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import UserModal from '../user/userModal'
    export default {
        name:"roowEdit",
        props:{
            showRoomList:{
                type:Function,
                default:null,
            }
        },
        components:{UserModal},
        computed:{
            room(){return this.$store.state.currentRoom},
            orgList(){ return this.$store.state.orgList}
        },
        watch:{
            orgList(){this.getOrgInfo()}
        },
        data(){
            return {
                collegeArray:[],
                labArray:[],
                schoolArray:[],
                zoneArray:[],
                roomNameList:[],
            }
        },
        methods:{
            editRoom(){
                //保存房间
                const _this = this;
                const url = this.serverUrl + "/admin/room/edit";
                if(!this.isSubmit()){
                    return false;
                }
                this.emitAjax(url,this.room,function(){
                    _this.showRoomList();
                })
            },
            createRoom(){
                //新建房间
                const _this = this;
                const url = this.serverUrl + "/admin/room/add";
                if(!this.isSubmit()){
                    return false;
                }
                const data = {
                    room_name:this.getNewRoomList(),
                    agent_name:this.room.agent_name,
                    agent_id:this.room.agent_id,
                    phone:this.room.phone,
                    building_name:this.room.building_name,
                    room_order:this.room.room_order,
                    dept_id:this.room.dept_id,
                    lab_id:this.room.lab_id,
                    zone_id:this.room.zone_id
                };
                if(data.room_name.length>100){
                    return confirm("您添加的房间数量达到"+data.room_name.length+"间，数据量较大，请确认是否正确！");
                }
                this.emitAjax(url,data,function(){
                    _this.showRoomList();
                })
            },
            isSubmit(){
                if(!this.room.room_name){
                    alert("请填写房间名称！");
                    return false;
                }
                if(!this.room.dept_id){
                    alert("请选择所属学院！");
                    return false;
                }
                if(!this.room.lab_id){
                    alert("请选择所属实验室！");
                    return false;
                }
                return true;
            },
            getNewRoomList(){
                //获得房间信息
                const roomName = this.room.room_name;
                let roomNameArray = [];
                let roomArray = [];
                let roomInfoArray = [];
                if(!roomName){return false}
                //根据，分割字符串。
                if(roomName.search(",")>0){
                    roomNameArray = roomName.split(",");
                }else if(roomName.search("，")>0){
                    roomNameArray = roomName.split("，");
                }else{
                    roomNameArray.push(roomName);
                }
                //循环分割的字符串数组
                for (let index = 0; index < roomNameArray.length; index++) {
                    const room_name = roomNameArray[index];
                    //如果有~分割循环
                    if(room_name.search("~")>0){
                        const room = room_name.split("~");
                        const minRoom = parseInt(room[0].match(/(\d+)$/)[1]);
                        const maxRoom = parseInt(room[1].match(/(\d+)$/)[1]);
                        //获取房间前缀
                        const roomNamePrefix = room[0].substring(0,room[0].match(/(\d+)$/)["index"]);
                        for (let index = minRoom; index <= maxRoom; index++) {
                            roomArray.push(roomNamePrefix+index);
                        }
                    }else{
                        roomArray.push(room_name);
                    }
                }
                this.roomNameList = roomArray;
                return roomArray;
            },
            selectUser(user){
                //选择用户
                this.room.agent_name = user.name;
                this.room.agent_id = user.username;
                if(!this.room.phone){
                    this.room.phone = user.mobile;
                }
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
                        if(element.org_state != "no"){
                            _this[element.org_level+"Array"].push(Object.assign({},element));
                        }
                    }
                }
            },
            getLabArray(){
                //获得实验室列表
                const _this = this;
                this.labArray = [];
                if(this.orgList.length>0){
                    for (let index = 0; index < _this.orgList.length; index++) {
                        const element = _this.orgList[index];
                        if(element.org_level == "lab" && element.org_state == "yes"){
                            _this.labArray.push(Object.assign({},element));
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