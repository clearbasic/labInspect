<template>
    <div class="roomList" @click="setIsOpen(false)">
        <search :show="isOpen" :setShow="setIsOpen">
            <div class="form">
                <div class="form-group">
                    <label class="control-label">按学院</label>
                    <select v-model="college_id" class="form-control">
                        <option :value="null">--无--</option>
                        <option :value="college.org_id" v-for="college in collegeOrgList" :key="'college'+college.org_id">{{college.org_name}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">按实验室</label>
                    <select v-model="lab_id" class="form-control">
                        <option :value="null">--无--</option>
                        <option :value="lab.org_id" v-for="lab in labOrgList" :key="'lab'+lab.org_id">{{lab.org_name}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">按房间分组</label>
                    <select v-model="zone_id" class="form-control">
                        <option :value="null">--无--</option>
                        <option :value="zone.zone_id" v-for="zone in zoneList" :key="'zone'+zone.zone_id">{{zone.zone_name}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" id="keywords" class="form-control search-query" 
                            placeholder="按关键字" v-model="keywords">
                        <span class="input-group-btn">
                            <button class="btn btn-purple btn-sm"  @click="getRoomList">
                                <span class="ace-icon fa fa-search icon-on-right bigger-110">搜索</span>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </search>
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
                        <span v-if="room.zone_id == 0">--无--</span>
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
            :currentPage = "page"
        ></page>
    </div>
</template>
<script>
import page from "../common/page";
import search from "../common/search";
export default {
    name: "roomList",
    props: {
        showRoomEdit: {
            type: Function,
            default: null
        },
        setDownUrl:{
            type: Function,
            default: null
        },
        page:{
            type:Number,
            default:1,
        },
        setPage:{
            type: Function,
            default: null
        }
    },
    components: { page, search },
    data() {
        return {
            roomList: [],
            zoneList: [],
            isShowOrder: 0,
            currentOrder: 0,
            pageCount: 10,
            college_id: null,
            lab_id: null,
            keywords: "",
            zone_id: null,
            isOpen:false, //搜索控制下拉菜单
        };
    },
    computed: {
        labOrgList() {
            return this.$store.state.labOrgList;
        },
        collegeOrgList() {
            return this.$store.state.collegeOrgList;
        },
        schoolOrgList() {
            return this.$store.state.schoolOrgList;
        }
    },
    watch:{
        college_id(){
            this.getRoomList();
        },
        lab_id(){
            this.getRoomList();
        },
        zone_id(){
            this.getRoomList();
        }
    },
    methods: {
        getRoomList() {
            //获取房间列表
            const _this = this;
            const data = {
                college_id: this.college_id,
                lab_id: this.lab_id,
                keywords: this.keywords,
                zone_id: this.zone_id
            };
            if(this.lab_id||this.keywords||this.college_id||this.zone_id){
                let search = "?";
                for (const key in data) {
                    if (data.hasOwnProperty(key)) {
                        const searchName = data[key];
                        if(searchName){
                            const keyword = key+"="+searchName+"&";
                            search += keyword;
                        }
                    }
                }
                search = search.substring(0,search.length-1);
                this.setDownUrl(search);
            }else{
                this.setDownUrl("");
            }
            const URL = this.serverUrl + "/admin/room/index";
            this.emitAjax(URL, data, function(result) {
                _this.setIsOpen(false);
                _this.roomList = result;
            });
        },
        deleteRoom(room) {
            //删除房间
            const _this = this;
            const URL = this.serverUrl + "/admin/room/del";
            if (window.confirm("是否删除房间<" + room.room_name + ">,此操作不可逆，请慎重！")) {
                this.emitAjax(URL, { room_id: room.room_id }, function(result) {
                    _this.getRoomList();
                });
            }
        },
        editRoom(room, event) {
            //编辑房间排序
            const _this = this;
            const url = this.serverUrl + "/admin/room/edit";
            _this.isShowOrder = 0;
            if (this.currentOrder == room.room_order) {
                return false;
            }
            this.emitAjax(url, room, function() {
                _this.getRoomList();
            });
        },
        getZoneList() {
            //获取分组列表
            let url = this.serverUrl + "/admin/zone/index";
            const _this = this;
            this.emitAjax(url, null, function(result) {
                _this.zoneList = result;
            });
        },
        getOrgList() {
            if (this.$store.state.orgList.length == 0) {
                this.$store.dispatch("getOrgList");
            }
        },
        setIsOpen(bool){
            this.isOpen = bool;
        }
    },
    mounted() {
        this.lab_id = this.$route.query.lab_id?this.$route.query.lab_id:null;
        this.college_id = this.$route.query.college_id?this.$route.query.college_id:null;
        this.keywords = this.$route.query.keywords?this.$route.query.keywords:null;
        this.zone_id = this.$route.query.zone_id?this.$route.query.zone_id:null;
        this.getOrgList();
        this.getRoomList();
        this.getZoneList();
    }
};
</script>