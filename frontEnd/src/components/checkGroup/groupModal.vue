<template>
    <div class="modal fade" id="groupModal" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document" aria-hidden="true">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="" id="createItemForm">
                        <div :class="['form-group','has-feedback',{'has-error':!changeObj.group_name}]">
                            <label><h5>实验室小组名称：</h5></label>
                            <input type="text" class="form-control" v-model="changeObj.group_name" placeholder="实验室小组名称">
                            <span class="form-control-feedback red">*</span>
                        </div>
                        <div class="form-group has-feedback">
                            <label><h5>组长名称：</h5></label>
                            <input type="text" class="form-control" v-model="changeObj.leader_name" placeholder="组长名称" readonly data-target="#userModal" data-toggle="modal">
                            <span class="form-control-feedback red">*</span>
                        </div>
                        <div class="form-group has-feedback">
                            <label><h5>组长工号/学号：</h5></label>
                            <input type="text" class="form-control" v-model="changeObj.leader_id" placeholder="组长工号/学号" readonly data-target="#userModal" data-toggle="modal">
                            <span class="form-control-feedback red">*</span>
                        </div>
                        <div class="form-group has-feedback">
                            <label><h5>组长电话：</h5></label>
                            <input type="text" class="form-control" v-model="changeObj.phone" placeholder="组长电话" readonly data-target="#userModal" data-toggle="modal">
                            <span class="form-control-feedback red">*</span>
                        </div>
                        <div class="form-group">
                            <label><h5>小组成员：</h5></label>
                            <input type="text" class="form-control" v-model="changeObj.members" placeholder="张三，李四(以，隔开)">
                        </div>
                        <div class="form-group">
                            <label><h5>排序：</h5></label>
                            <input type="text" class="form-control" v-model="changeObj.group_order">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" @click="closeModal">关闭</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="saveGroup">保存</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "groupModal",
    data() {
        return {};
    },
    props: ["getCheckGroupList","changeObj","orgList"],
    methods: {
        saveGroup(){
            //创建新指标
            if(this.changeObj.group_name == ""){
                alert("请填写小组名称！");
                return false;
            }
            const _SELF = this;
            let URL = this.serverUrl + "/admin/group/add";
            if(this.changeObj.group_id){
                URL = this.serverUrl + "/admin/group/edit";
            }
            this.changeObj.level = this.loginUser.user_level;
            this.changeObj.org_id = this.loginUser.org_id;
            this.emitAjax(URL, this.changeObj, function(result) {
                _SELF.getCheckGroupList();
                _SELF.closeModal();
            });
        },
        closeModal(){
            //关闭模态框
            $('#groupModal').modal('hide');
        },
    }
};
</script>
