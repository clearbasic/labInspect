<template>
    <div class="modal fade" id="groupModal" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" aria-hidden="true">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="" id="createItemForm">
                        <div :class="['form-group',{'has-error':!group_name}]">
                            <label><h5>实验室小组名称：<span class="red">*</span></h5></label>
                            <input type="text" class="form-control" v-model="group_name" placeholder="实验室小组名称">
                        </div>
                        <div class="form-group">
                            <label><h5>实验室小组类型：</h5></label>
                            <div class="radio">
                                <label>
                                    <input type="radio" class="ace" v-model="level" value="school">
                                    <span class="lbl">自查小组</span>
                                </label>
                                <label>
                                    <input type="radio" class="ace" v-model="level" value="college">
                                    <span class="lbl">复查小组</span>
                                </label>
                                <label>
                                    <input type="radio" class="ace" v-model="level" value="lab">
                                    <span class="lbl">抽查小组</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><h5>所属机构：</h5></label>
                            <select name="" id="">
                                <option value="org_id">机构</option>
                            </select>
                        </div>
                        <div :class="['form-group',{'has-error':!leader_name}]">
                            <label><h5>组长名称：<span class="red">*</span></h5></label>
                            <input type="text" class="form-control" v-model="leader_name" placeholder="组长名称">
                        </div>
                        <div :class="['form-group',{'has-error':!leader_id}]">
                            <label><h5>组长工号/学号：<span class="red">*</span></h5></label>
                            <input type="text" class="form-control" v-model="leader_id" placeholder="组长工号/学号">
                        </div>
                        <div :class="['form-group',{'has-error':!phone}]">
                            <label><h5>组长电话：<span class="red">*</span></h5></label>
                            <input type="text" class="form-control" v-model="phone" placeholder="组长电话">
                        </div>
                        <div class="form-group">
                            <label><h5>小组成员：</h5></label>
                            <input type="text" class="form-control" v-model="members" placeholder="张三，李四(以，隔开)">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" @click="changeGroup(null)">关闭</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="createNewItem">保存</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { serverUrl } from "../../config/server.js";
import { emitAjax } from "../../assets/common.js";
export default {
    name: "groupModal",
    data() {
        return {
            group_name: "",
            level: "school",
            leader_name: "",
            leader_id: "",
            phone: "",
            members: ""
        };
    },
    props: ["getCheckGroupList","changeObj","changeGroup"],
    methods: {
        createNewItem(){
            //创建新指标
            if(this.group_name == "" || this.leader_name == "" || this.phone == "" || this.members == ""){
                alert("信息填写不完整");
            }else{
                const _SELF = this;
                let URL = serverUrl + "/admin/add";
                let data = {
                    group_name: this.group_name,
                    level: this.level,
                    leader_name: this.leader_name,
                    leader_id: this.leader_id,
                    phone: this.phone,
                    members: this.members,
                };
                if(this.changeObj){

                }else{

                }
                this.changeGroup(null);
                $('#groupModal').modal('hide')
                /* emitAjax(URL, data, function(result) {
                    _SELF.getCheckItemList();
                }); */
            }
        }
    },
    watch:{
        changeObj(){
            if(this.changeObj != null){
                this.group_name = this.changeObj.group_name;
                this.level= this.changeObj.level;
                this.leader_name= this.changeObj.leader_name;
                this.leader_id= this.changeObj.leader_id;
                this.phone= this.changeObj.phone;
                this.members= this.changeObj.members;
            }else{
                this.group_name = "";
                this.level= "school";
                this.leader_name= "";
                this.leader_id="";
                this.phone= "";
                this.members= "";
            }
        }
    }
};
</script>
