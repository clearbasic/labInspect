<template>
    <div class="modal fade" id="myModal" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" aria-hidden="true">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="" id="createItemForm">
                        <div class="form-group">
                            <label for=""><h5>指标名称：</h5></label>
                            <input type="text" class="form-control" v-model="item_name" placeholder="请输入指标名称">
                        </div>
                        <div class="form-group">
                            <label for=""><h5>指标类型：</h5></label>
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
                            <label for=""><h5>是否一票否决：</h5></label>
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
                        <div class="form-group">
                            <label for=""><h5>排序：</h5></label>
                            <input type="text" class="form-control" v-model="length" placeholder="请输入指标名称">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="createNewIetm" data-dismiss="modal">保存指标</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { serverUrl } from "../../config/server.js";
import { emitAjax } from "../../assets/common.js";
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
        createNewIetm() {
            //创建新指标
            if(this.item_name == "" || this.item_order == ""){
                alert("信息填写不完整");
            }else{
                const URL = serverUrl + "/admin/item/add";
                const _SELF = this;
                let data = {
                    checklist_id: this.$route.params.id,
                    item_type: this.item_type,
                    item_name: this.item_name,
                    item_level: this.item_level,
                    item_order: this.length
                };
                emitAjax(URL, data, function(result) {
                    _SELF.checkListDate = result;
                    //刷新指标列表
                    _SELF.getCheckItemList();
                });
            }
        }
    }
};
</script>
