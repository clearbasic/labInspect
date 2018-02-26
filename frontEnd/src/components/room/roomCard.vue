<template>
    <div class="roowCard">
        <table width="100%" class="center">
            <caption class="center h2">房间标识卡</caption>
            <tr>
                <th width="25%" class="center">实验室名称</th>
                <td width="25%" class="center">{{room.lab_name}}</td>
                <th width="25%" class="center">房间号</th>
                <td width="25%" class="center">{{room.room_name}}</td>
            </tr>
            <tr>
                <th class="center">实验室类别</th>
                <td class="center">{{room.smallcategory}}</td>
                <th class="center">危险等级</th>
                <td class="center"></td>
            </tr>
            <tr>
                <th class="center">所属学院</th>
                <td class="center">{{room.dept_name}}</td>
                <th class="center">是否安全达标</th>
                <td class="center"></td>
            </tr>
            <tr>
                <th class="center">实验室负责人</th>
                <td class="center">{{room.responsible}}</td>
                <th class="center">手机号码</th>
                <td class="center">{{room.responsible_mobile}}</td>
            </tr>
            <tr>
                <th class="center">实验室联系人</th>
                <td class="center">{{room.contacts}}</td>
                <th class="center">手机号码</th>
                <td class="center">{{room.contacts_mobile}}</td>
            </tr>
            <tr>
                <th class="center red">实验室资质</th>
                <th class="center red">危险源</th>
                <th class="center red">防护措施</th>
                <th class="center red">灭火要点</th>
            </tr>
            <tr v-for="item in 5" :key="'zizhi'+item">
                <td class="center">{{room.zizhi&&room.zizhi.qualified&&room.zizhi.qualified[item-1]}}</td>
                <td class="center">{{room.zizhi&&room.zizhi.hazard_source&&room.zizhi.hazard_source[item-1]}}</td>
                <td class="center">{{room.zizhi&&room.zizhi.precautions&&room.zizhi.precautions[item-1]}}</td>
                <td class="center">{{room.zizhi&&room.zizhi.main_outfire&&room.zizhi.main_outfire[item-1]}}</td>
            </tr>
        </table>
        <p class="h4">
            <b>报警110  火警119 保卫处：83262110 国资处：83262265 国资处监制</b>
        </p>
    </div>
</template>
<script>
    export default {
        name:"roowCard",
        data(){
            return {
                room:{
                    zizhi:{},
                },
            }
        },
        methods:{
            getRoomInfo(){
                const URL = this.serverUrl + "/admin/room/roomCard";
                const _this = this;
                this.emitAjax(URL, {room_id:this.$route.query.id}, function(result) {
                    _this.room = result;
                });
            }
        },
        mounted(){
            this.getRoomInfo();
        }
    };
</script>
<style scoped>
    .roowCard {
        width:900px;
        margin:100px auto;
    }
    .roowCard table,.roowCard td,.roowCard th {
        border:2px solid #f00;
        height: 35px;
        font-size: 16px;
    }
</style>
