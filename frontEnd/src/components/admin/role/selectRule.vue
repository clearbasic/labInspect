<template>
    <div class="widget-box ui-sortable-handle">
        <div class="widget-header">
           <h5 class="widget-title">权限分配</h5>
        </div>
        <div class="widget-body selectRule">
            <div class="rule_box widget-main" v-for="(rule,ruleIndex) in rule_tree" :key="'rule'+rule.id">
                <h4>
                    <label>
                        <input type="checkbox" class="ace" :value="rule.id" v-model="rule.checked" @change="firstRuleSelect(rule)">
                        <span class="lbl"> {{rule.title}}</span>
                    </label>
                </h4>
                <ul class="list-group" v-if="rule.child&&rule.child.length>0">
                    <li v-for="(secondRule,index) in rule.child" :key="'rule'+secondRule.id" class="list-group-item">
                        <h5>
                            <label>
                                <input type="checkbox" class="ace" :value="secondRule.id" v-model="secondRule.checked" @change="selectRules(secondRule,rule_tree,ruleIndex)">
                                <span class="lbl"> {{secondRule.title}}</span>
                            </label>
                        </h5>
                        <label v-if="secondRule.child&&secondRule.child.length>0" v-for="threeRule in secondRule.child" :key="'rule'+threeRule.id">
                            <input type="checkbox" class="ace" :value="threeRule.id" v-model="threeRule.checked" @change="selectRules(threeRule,rule.child,index,rule_tree,ruleIndex)">
                            <span class="lbl"> {{threeRule.title}}</span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "selectRule",
    props: {
        returnRules: {
            type: Function,
            default: null
        },
        rules: {
            type: String,
            default: ""
        },
        pid:{
            type: Number,
            default: 0
        }
    },
    data() {
        return {
            rule_tree: [],
            rulesString: "",
            relesArray:[],
        };
    },
    methods: {
        getRuleTree() {
            //获取权限点树状结构
            const URL = this.serverUrl + "/admin/groups/getRules";
            const _this = this;
            this.emitAjax(URL, {group_id:this.pid}, function(result) {
                _this.rule_tree = result;
                _this.initChecked(_this.rule_tree);
            });
        },
        selectRules(rule, pRules, index, psRules, pIndex) {
            //rule选中的权限，pRules选中的权限父级所属的数组，index父级的索引,psRules第一级的权限
            let isTure = rule.checked;
            rule = Object.assign({}, rule, {
                checked: isTure
            });
            if (rule.child) {
                this.forEachChild(rule.child, isTure);
            }
            if (isTure && pRules) {
                this.$set(pRules,index,Object.assign({}, pRules[index], {
                        checked: isTure
                    })
                );
            }
            if (isTure && psRules) {
                this.$set(psRules,pIndex,Object.assign({}, psRules[pIndex], {
                        checked: isTure
                    })
                );
            }
            //重置rules
            this.rulesString = this.resetRules(this.rule_tree);
            this.rulesString = this.rulesString.substring(0,this.rulesString.length - 1);
        },
        forEachChild(rule, bool) {
            for (let index = 0; index < rule.length; index++) {
                this.$set(
                    rule,
                    index,
                    Object.assign({}, rule[index], {
                        checked: bool
                    })
                );
                if (rule[index].child) {
                    this.forEachChild(rule[index].child, bool);
                }
            }
        },
        resetRules(rules) {
            //组织权限字符串
            let string = "";
            for (let index = 0; index < rules.length; index++) {
                const rule = rules[index];
                if (rule.checked) {
                    string += rule.id + ",";
                }
                if (rule.child) {
                    string += this.resetRules(rule.child);
                }
            }
            return string;
        },
        firstRuleSelect(rule) {
            //第一级权限没有  其他的都没有
            if (!rule.checked) {
                this.selectRules(rule);
            } else {
                this.rulesString = this.resetRules(this.rule_tree);
                this.rulesString = this.rulesString.substring(0,this.rulesString.length - 1);
            }
        },
        initChecked(rule) {
            //根据规则回显
            for (let index = 0; index < rule.length; index++) {
                if (this.relesArray.indexOf(""+rule[index].id) >= 0) {
                    this.$set(
                        rule,
                        index,
                        Object.assign({}, rule[index], {
                            checked: true
                        })
                    );
                }
                if (rule[index].child) {
                    this.initChecked(rule[index].child);
                }
            }
        }
    },
    watch: {
        rulesString() {
            this.returnRules(this.rulesString);
        },
        pid(){
            this.relesArray = [];
            this.getRuleTree();
        }
    },
    mounted() {
        this.relesArray = this.rules.split(",");
        this.getRuleTree();
    }
};
</script>

