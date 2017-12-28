<template>
    <div class="widget-box ui-sortable-handle">
        <div class="widget-header">
           <h5 class="widget-title">权限分配</h5>
        </div>
        <div class="widget-body selectRule">
            <div class="rule_box widget-main" v-for="rule in rule_tree" :key="'rule'+rule.id">
                <h4>
                    <label>
                        <input type="checkbox" class="ace" :value="rule.id" v-model="rule.checked" @click="selectModule(rule)">
                        <span class="lbl">{{rule.title}}</span>
                    </label>
                </h4>
                <ul class="list-group" v-if="rule.child&&rule.child.length>0">
                    <li v-for="secondRule in rule.child" :key="'rule'+secondRule.id" class="list-group-item">
                        <h5>
                            <label>
                                <input type="checkbox" class="ace" :value="secondRule.id" v-model="secondRule.checked">
                                <span class="lbl">{{secondRule.else}}</span>
                            </label>
                        </h5>
                        <label v-if="secondRule.child&&secondRule.child.length>0" v-for="threeRule in secondRule.child" :key="'rule'+threeRule.id">
                            <input type="checkbox" class="ace" :value="threeRule.id" v-model="threeRule.checked">
                            <span class="lbl">{{threeRule.else}}</span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
    
    export default {
        name:"selectRule",
        props:{
            returnRules:{
                type:Function,
                default:null
            },
            rules:{
                type:String,
                default:"",
            }
        },
        data(){
            return {
                rule_tree:[],
            }
        },
        methods:{
            getRuleTree(){
                //获取权限点树状结构
                const URL = this.serverUrl + '/admin/rules/index';
                const _this = this;
                this.emitAjax(URL,{type:"tree"},function(result){
                    _this.rule_tree = result;
                    _this.initChecked();
                })
            },
            selectModule(rule){
                if(rule.checked){
                    rule.checked = false;
                    for (let index = 0; index < rule.child.length; index++) {
                        const secondRule = rule.child[index];
                        secondRule.checked = false;
                        for (let i = 0; i < secondRule.child.length; i++) {
                            const threeRule = secondRule.child[i];
                            threeRule.checked = false;
                        }
                    }
                }else {
                    rule.checked = true;
                    for (let index = 0; index < rule.child.length; index++) {
                        const secondRule = rule.child[index];
                        secondRule.checked = true;
                        for (let i = 0; i < secondRule.child.length; i++) {
                            const threeRule = secondRule.child[i];
                            threeRule.checked = true;
                        }
                    }
                }
            },
            initChecked(){
                for (let k = 0; k < this.rule_tree.length; k++) {
                    const rule = this.rule_tree[k];
                    rule.checked = this.rules.indexOf(rule.id)>0?true:false;

                    for (let index = 0; index < rule.child.length; index++) {
                        const secondRule = rule.child[index];
                        secondRule.checked = this.rules.indexOf(secondRule.id)>0?true:false;
                        
                        for (let i = 0; i < secondRule.child.length; i++) {
                            const threeRule = secondRule.child[i];
                            threeRule.checked = this.rules.indexOf(threeRule.id)>0?true:false;
                        }
                    }
                }
            }
        },
        mounted(){
            this.getRuleTree();
        }
    };
</script>
<style>
    .selectRule {
        max-height:400px;
        overflow-y: auto;
    }
    .selectRule label {
        padding:0 5px;
    }
</style>

