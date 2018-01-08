<template>
    <div class="roomList">
        <table class="table table-bordered table-hover nomargin">
            <thead>
                <tr>
                    <th class="center little">房间ID</th>
                    <th>房间名</th>
                    <th class="hidden-640">责任人</th>
                    <th class="center little hidden-640">联系电话</th>
                    <th class="center hidden-640">所属院系</th>
                    <th class="center hidden-640">所属实验室</th>
                    <th class="center little hidden-640">所属分组</th>
                    <th class="center little">排序编号</th>
                    <th class="center little">操作</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(room,index) in roomList" :key="'roomList'+index"
                    v-if="index>=(page-1)*pageCount && index<page*pageCount"
                >
                    <td class="center little">{{room.room_id}}</td>
                    <td>
                        <a @click="showRoomEdit(room)">{{room.room_name}}</a>
                    </td>
                    <td class="hidden-640">{{room.agent_name}}({{room.agent_id}})</td>
                    <td class="center little hidden-640">{{room.phone}}</td>
                    <td class="center hidden-640">{{room.dept_name}}</td>
                    <td class="center hidden-640">{{room.lab_name}}</td>
                    <td class="center hidden-640">
                        <span v-if="room.zone_id == 0">无</span>
                        <span v-for="zone in zoneList" :key="'zone'+zone.zone_id" v-if="room.zone_id == zone.zone_id">{{zone.zone_name}}</span>
                    </td>
                    <td class="center little" @dblclick="isShowOrder=room.room_id">
                        <span v-if="isShowOrder != room.room_id">{{room.room_order}}</span>
                        <input type="text" 
                            v-if="isShowOrder == room.room_id" 
                            v-model="room.room_order" 
                            style="width:30px;"
                            @blur="editRoom(room,$event)"
                            @focus="currentOrder = room.room_order"
                            autofocus="autofocus"
                        >
                    </td>
                    <td class="center little">
                        <div class="hidden-xs btn-group">
                            <button class="btn btn-xs btn-success" @click="showRoomEdit(room)" title="编辑">
                                <i class="ace-icon glyphicon glyphicon-edit bigger-100"></i>
                            </button>
                            <button class="btn btn-xs btn-danger" @click="deleteRoom(room)" title="删除">
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
                                        <a class="tooltip-info blue" data-rel="tooltip" data-original-title="View" @click="showRoomEdit(room)">
                                            <i class="ace-icon glyphicon glyphicon-edit bigger-100"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="tooltip-info red" data-rel="tooltip" data-original-title="View"  @click="deleteRoom(room)">
                                            <i class="ace-icon fa fa-trash-o bigger-100"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr v-if="roomList.length == 0">
                    <td colspan="9" class="center">暂无房间</td>
                </tr>
            </tbody>
        </table>
        <page
            :pages = "Math.ceil(roomList.length/pageCount)"
            :setPage = "setPage"
        ></page>
    </div>
</template>
<script>
    import page from '../common/page';
    export default {
        name:"roomList",
        props:{
            showRoomEdit:{
                type:Function,
                default:null,
            }
        },
        components:{page},
        data(){
            return {
                roomList:[],
                zoneList:[],
                isShowOrder:0,
                currentOrder:0,
                page:1,
                pageCount:10,
            }
        },
        methods:{
            getRoomList(){
                //获取房间列表
                const _this = this;
                const URL = this.serverUrl+'/admin/room/index';
                this.emitAjax(URL,null,function(result){
                    _this.roomList = result;
                })
            },
            deleteRoom(room){
                //删除房间
                const _this = this;
                const URL = this.serverUrl+'/admin/room/del';
                if(window.confirm("是否删除房间<"+room.room_name+">,此操作不可逆，请慎重！")){
                    this.emitAjax(URL,{room_id:room.room_id},function(result){
                        _this.getRoomList();
                    })
                }
            },
            editRoom(room,event){
                //编辑房间排序
                const _this = this;
                const url = this.serverUrl + "/admin/room/edit";
                _this.isShowOrder = 0;
                if(this.currentOrder == room.room_order){
                    return false;
                }
                this.emitAjax(url,room,function(){
                    _this.getRoomList();
                })
            },
            getZoneList(){
                //获取分组列表
                let url = this.serverUrl + "/admin/zone/index";
                const _this = this;
                this.emitAjax(url,null,function(result){
                    _this.zoneList = result;
                })
            },
            setPage(page){
                this.page = page;
            }
        },
        mounted(){
            this.getRoomList();
            this.getZoneList();
        }
    };
</script>