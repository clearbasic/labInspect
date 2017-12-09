<template>
    <div class="modal fade" id="myModal" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" aria-hidden="true">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="" id="createItemForm">
                        <div :class="['form-group',{'has-error':!item_name}]" >
                            <label><h5>指标名称：<span class="red">*</span></h5></label>
                            <input type="text" class="form-control" v-model="item_name" placeholder="请输入指标名称">
                        </div>
                        <div class="form-group">
                            <label><h5>指标类型：</h5></label>
                            <div class="radio">
                                <label>
                                    <input type="radio" class="ace" v-model="item_type" value="header">
                                    <span class="lbl">小标题</span>
                                </label>
                                <label>
                                    <input type="radio" class="ace" v-model="item_type" value="common">
                                    <span class="lbl">普通指标</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><h5>是否一票否决：</h5></label>
                            <div class="radio">
                                <label>
                                    <input type="radio" class="ace" v-model="item_level" value="fatal">
                                    <span class="lbl">是</span>
                                </label>
                                <label>
                                    <input type="radio" class="ace" v-model="item_level" value="common">
                                    <span class="lbl">否</span>
                                </label>
                            </div>
                        </div>
                        <div :class="['form-group',{'has-error':!length}]">
                            <label><h5>排序：<span class="red">*</span></h5></label>
                            <input type="text" class="form-control" v-model="length" placeholder="请输入排序">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="createNewItem">保存</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "checkItemModal",
    data() {
        return {
            item_type: "common",
            item_name: "",
            item_level: "common",
        };
    },
    props: ["getCheckItemList", "length"],
    methods: {
        createNewItem() {
            //创建新指标
            if(this.item_name == "" || this.item_order == ""){
                alert("信息填写不完整");
            }else{
                const URL = this.serverUrl + "/admin/item/add";
                const _SELF = this;
                let data = {
                    checklist_id: this.$route.params.id,
                    item_type: this.item_type,
                    item_name: this.item_name,
                    item_level: this.item_level,
                    item_order: this.length
                };
                this.emitAjax(URL, data, function(result) {
                    $('#myModal').modal('hide');
                    _SELF.getCheckItemList();
                });
            }
        }
    }
};
</script>
