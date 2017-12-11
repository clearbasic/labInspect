<template>
    <div class="checkRule">
        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#labRule" data-toggle="tab">自查规则</a></li>
                <li><a href="#collegeRule" data-toggle="tab">复查规则</a></li>
                <li><a href="#schoolRule" data-toggle="tab">抽查规则</a></li>
            </ul>
            <div class="tab-content">
                <!-- 自查规则 -->
                <div id="labRule" class="tab-pane fade active in">
                    <div class="clearfix">
                        <button class="btn btn-primary btn-sm" @click="showAddRule('lab')">添加自查规则</button>
                        <p class="pull-right">
                            从
                            <select name="" id="">
                                <option value="">复查规则</option>
                                <option value="">抽查规则</option>
                            </select>
                            拷贝
                        </p>
                    </div>
                   <div class="table-responsive">
                       <table class="table table-striped table-bordered table-hover cutomize_table">
                           <thead>  
                            <tr>
                                <th style="text-align:left;">适用单位</th>
                                <th v-for="(checkList,index) in checkListArray" :key="'checklist'+index" style="text-align:center">
                                    {{checkList.name}}
                                </th>
                                <th width="50px">合计</th>
                                <th class="center little">操作</th>
                            </tr>
                           </thead>
                           <tbody>
                                <tr v-for="(rule,index) in labRule" :key="'labRule'+index">
                                    <td style="text-align:left;">
                                       <select v-model="rule.college_id">
                                           <option value="0">全部</option>
                                           <option v-for="(college,index) in collegeArray" :key="'collegeArray'+index" 
                                                :value="college.org_id"
                                           >{{college.org_name}}</option>
                                       </select>
                                       <select v-model="rule.lab_id">
                                            <option value="0">全部</option>
                                            <option v-for="(lab,index) in labArray" :key="'collegeArray'+index" 
                                                :value="lab.org_id"
                                           >{{lab.org_name}}</option>
                                       </select>
                                   </td>
                                   <td v-for="checkList in checkListArray" :key="'checklist'+checkList.id">
                                        <input type="text" 
                                            :value="rule.checklist[''+checkList.id+'']?rule.checklist[''+checkList.id+''].score:0"
                                            style="width:30px;"
                                            @blur="changeScore(checkList.id,index,'lab',$event)"
                                            @keyup="changeScore(checkList.id,index,'lab',$event)"
                                        />
                                   </td>
                                   <td v-html="computeTotalScore(index,'lab')"></td>
                                   <td class="center little">
                                       <button class="btn btn-danger btn-xs" @click="delRule(rule)">
                                           <i class="ace-icon fa fa-trash-o bigger-110"></i>
                                       </button>
                                   </td>
                                </tr>
                                <!-- 添加新规则 -->
                                <tr v-if="addRule && addRuleType == 'lab'">
                                    <td style="text-align:left;">
                                       <select v-model="newRule.college_id">
                                           <option value="0">全部</option>
                                           <option v-for="(college,index) in collegeArray" :key="'collegeArray'+index" 
                                                :value="college.org_id"
                                           >{{college.org_name}}</option>
                                       </select>
                                       <select v-model="newRule.lab_id">
                                           <option value="0">全部</option>
                                            <option v-for="(lab,index) in labArray" :key="'collegeArray'+index" 
                                                :value="lab.org_id"
                                           >{{lab.org_name}}</option>
                                       </select>
                                   </td>
                                   <td v-for="checkList in checkListArray" :key="'checklist'+checkList.id" class="newRuleScore">
                                        <input type="text" 
                                            :value="newRule.checklist[''+checkList.id+'']?newRule.checklist[''+checkList.id+''].score:0"
                                            style="width:30px;"
                                            :data-checkList="checkList.id"
                                        />
                                   </td>
                                   <td></td>
                                   <td class="center little">
                                       <button class="btn btn-success btn-xs" @click="sureAddRule">
                                           <i class="ace-icon glyphicon glyphicon-ok bigger-110"></i>
                                       </button>
                                   </td>
                                </tr>
                           </tbody>
                       </table>
                   </div>
                </div>
                <!-- 复查规则 -->
                <div id="collegeRule" class="tab-pane fade">
                    <div class="clearfix">
                        <button class="btn btn-primary btn-sm" @click="showAddRule('college')">添加复查规则</button>
                        <p class="pull-right">
                            从
                            <select name="" id="">
                                <option value="">自查规则</option>
                                <option value="">抽查规则</option>
                            </select>
                            拷贝
                        </p>
                    </div>
                    <div class="table-responsive">
                       <table class="table table-striped table-bordered table-hover cutomize_table">
                           <thead>  
                            <tr>
                                <th style="text-align:left;">适用单位</th>
                                <th v-for="(checkList,index) in checkListArray" :key="'checklist'+index" style="text-align:center">
                                    {{checkList.name}}
                                </th>
                                <th width="50px">合计</th>
                                <th class="center little">操作</th>
                            </tr>
                           </thead>
                           <tbody>
                                <tr v-for="(rule,index) in collegeRule" :key="'collegeRule'+index">
                                   <td style="text-align:left;">
                                       <select v-model="rule.college_id">
                                           <option value="0">全部</option>
                                           <option v-for="(college,index) in collegeArray" :key="'collegeArray'+index" 
                                                :value="college.org_id"
                                           >{{college.org_name}}</option>
                                       </select>
                                       <select v-model="rule.lab_id">
                                            <option value="0">全部</option>
                                            <option v-for="(lab,index) in labArray" :key="'collegeArray'+index" 
                                                :value="lab.org_id"
                                           >{{lab.org_name}}</option>
                                       </select>
                                   </td>
                                   <td v-for="checkList in checkListArray" :key="'checklist'+checkList.id">
                                        <input type="text" 
                                            :value="rule.checklist[''+checkList.id+'']?rule.checklist[''+checkList.id+''].score:0"
                                            style="width:30px;"
                                            @blur="changeScore(checkList.id,index,'college',$event)"
                                            @keyup="changeScore(checkList.id,index,'college',$event)"
                                        />
                                   </td>
                                   <td v-html="computeTotalScore(index,'college')"></td>
                                   <td class="center little">
                                       <button class="btn btn-danger btn-xs" @click="delRule(rule)">
                                           <i class="ace-icon fa fa-trash-o bigger-110"></i>
                                       </button>
                                   </td>
                                </tr>
                                <!-- 添加新规则 -->
                                <tr v-if="addRule && addRuleType == 'college'">
                                    <td style="text-align:left;">
                                       <select v-model="newRule.college_id">
                                           <option value="0">全部</option>
                                           <option v-for="(college,index) in collegeArray" :key="'collegeArray'+index" 
                                                :value="college.org_id"
                                           >{{college.org_name}}</option>
                                       </select>
                                       <select v-model="newRule.lab_id">
                                           <option value="0">全部</option>
                                            <option v-for="(lab,index) in labArray" :key="'collegeArray'+index" 
                                                :value="lab.org_id"
                                           >{{lab.org_name}}</option>
                                       </select>
                                   </td>
                                   <td v-for="checkList in checkListArray" :key="'checklist'+checkList.id" class="newRuleScore">
                                        <input type="text" 
                                            :value="newRule.checklist[''+checkList.id+'']?newRule.checklist[''+checkList.id+''].score:0"
                                            style="width:30px;"
                                            :data-checkList="checkList.id"
                                        />
                                   </td>
                                   <td></td>
                                   <td class="center little">
                                       <button class="btn btn-success btn-xs" @click="sureAddRule">
                                           <i class="ace-icon glyphicon glyphicon-ok bigger-110"></i>
                                       </button>
                                   </td>
                                </tr>
                           </tbody>
                       </table>
                    </div>
                </div>
                <!-- 抽查规则 -->
                <div id="schoolRule" class="tab-pane fade">
                    <div class="clearfix">
                        <button class="btn btn-primary btn-sm" @click="showAddRule('school')">添加抽查规则</button>
                        <p class="pull-right">
                            从
                            <select name="" id="">
                                <option value="">自查规则</option>
                                <option value="">复查规则</option>
                            </select>
                            拷贝
                        </p>
                    </div>
                    <div class="table-responsive">
                       <table class="table table-striped table-bordered table-hover cutomize_table">
                           <thead>  
                            <tr>
                                <th style="text-align:left;">适用单位</th>
                                <th v-for="(checkList,index) in checkListArray" :key="'checklist'+index" style="text-align:center">
                                    {{checkList.name}}
                                </th>
                                <th width="50px">合计</th>
                                <th class="center little">操作</th>
                            </tr>
                           </thead>
                           <tbody>
                                <tr v-for="(rule,index) in schoolRule" :key="'schoolRule'+index">
                                   <td style="text-align:left;">
                                       <select v-model="rule.college_id">
                                           <option value="0">全部</option>
                                           <option v-for="(college,index) in collegeArray" :key="'collegeArray'+index" 
                                                :value="college.org_id"
                                           >{{college.org_name}}</option>
                                       </select>
                                       <select v-model="rule.lab_id">
                                            <option value="0">全部</option>
                                            <option v-for="(lab,index) in labArray" :key="'collegeArray'+index" 
                                                :value="lab.org_id"
                                           >{{lab.org_name}}</option>
                                       </select>
                                   </td>
                                   <td v-for="checkList in checkListArray" :key="'checklist'+checkList.id">
                                        <input type="text" 
                                            :value="rule.checklist[''+checkList.id+'']?rule.checklist[''+checkList.id+''].score:0"
                                            style="width:30px;"
                                            @blur="changeScore(checkList.id,index,'school',$event)"
                                            @keyup="changeScore(checkList.id,index,'school',$event)"
                                        />
                                   </td>
                                   <td v-html="computeTotalScore(index,'school')"></td>
                                   <td class="center little">
                                       <button class="btn btn-danger btn-xs" @click="delRule(rule)">
                                           <i class="ace-icon fa fa-trash-o bigger-110"></i>
                                       </button>
                                   </td>
                               </tr>
                               <!-- 添加新规则 -->
                                <tr v-if="addRule && addRuleType == 'school'">
                                    <td style="text-align:left;">
                                       <select v-model="newRule.college_id">
                                           <option value="0">全部</option>
                                           <option v-for="(college,index) in collegeArray" :key="'collegeArray'+index" 
                                                :value="college.org_id"
                                           >{{college.org_name}}</option>
                                       </select>
                                       <select v-model="newRule.lab_id">
                                           <option value="0">全部</option>
                                            <option v-for="(lab,index) in labArray" :key="'collegeArray'+index" 
                                                :value="lab.org_id"
                                           >{{lab.org_name}}</option>
                                       </select>
                                   </td>
                                   <td v-for="checkList in checkListArray" :key="'checklist'+checkList.id" class="newRuleScore">
                                        <input type="text" 
                                            :value="newRule.checklist[''+checkList.id+'']?newRule.checklist[''+checkList.id+''].score:0"
                                            style="width:30px;"
                                            :data-checkList="checkList.id"
                                        />
                                   </td>
                                   <td></td>
                                   <td class="center little">
                                       <button class="btn btn-success btn-xs" @click="sureAddRule">
                                           <i class="ace-icon glyphicon glyphicon-ok bigger-110"></i>
                                       </button>
                                   </td>
                                </tr>
                           </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "checkRule",
    computed: {
        checkPlan() {
            return this.$store.state.checkPlan;
        },
        orgList(){
            return this.$store.state.orgList;
        }
    },
    watch: {
        checkPlan: function() {
            this.setCount();
        },
        orgList(){
            this.getOrgInfo();
        }
    },
    mounted() {
        this.getCheckList();
        this.getOrgList();
    },
    data() {
        return {
            checkListArray: [], //指标库列表
            schoolArray:[], //学校列表
            collegeArray:[],//院系列表
            labArray:[],//实验室列表
            schoolRule: [], //自查规则列表数组
            collegeRule: [], //复查规则列表数组
            labRule: [], //抽查规则列表数组
            isKeyUp: false, //是否按键了
            addRule:false,
            addRuleType:"",
            newRule:{
                rule_id:0,
                plan_id:0,
                level:"lab",
                college_id:0,
                lab_id:0,
                checklist:{},
            }
        };
    },
    methods: {
        computeTotalScore(index, type) {
            //计算总分
            let ruleArry = this[type + "Rule"];
            let rule = ruleArry[index];
            let { checklist } = rule;
            let score = 0;
            for (const key in checklist) {
                if (checklist.hasOwnProperty(key)) {
                    const element = checklist[key];
                    score += parseFloat(element.score);
                }
            }
            if (this.$store.state.checkPlan.plan_score == score) {
                return "<span class='green'>" + score + "</span>";
            } else {
                return "<span class='red'>" + score + "</span>";
            }
        },
        changeScore(checkListId, index, type, event) {
            //修改分数
            if (event.type === "keyup" && event.key != "Enter") {
                return false;
            }
            if (!this.isKeyUp) {
                let rule = this[type + "Rule"][index];
                let { checklist } = rule;
                let value = event.target.value ? event.target.value : 0;
                if (checklist[checkListId]) {
                    checklist[checkListId].score = value;
                } else {
                    checklist[checkListId] = {
                        score: value
                    };
                }
                this.$store.dispatch("getCheckPlan", this.checkPlan);
                console.log("zhixingle ");
            }
            if (event.type === "blur") {
                this.isKeyUp = false;
            }
            if (event.type === "keyup") {
                this.isKeyUp = true;
            }
        },
        showAddRule(type){
            //显示添加规则输入框
            this.addRule=true;
            this.addRuleType=type;
        },
        sureAddRule(){
            //添加规则
            const URL = this.serverUrl + "/admin/rule/add";
            const _SELF = this;
            const data = {
                plan_id:this.$route.params.id,
                level:this.addRuleType,
                college_id:this.newRule.college_id,
                lab_id:this.newRule.lab_id,
                checklist:{},
            }
            $(".newRuleScore").each(function(){
                let score = parseFloat($(this).find("input").val());
                let checklist = $(this).find("input").attr("data-checkList");
                if (score>0){
                    data.checklist[checklist] = Object.assign({},{
                        score
                    });
                }
            })
            this.emitAjax(URL, data, function(result) {
                _SELF.newRule={
                    rule_id:0,
                    plan_id:0,
                    level:"lab",
                    college_id:0,
                    lab_id:0,
                    checklist:{},
                }
                _SELF.addRule=false;
                _SELF.addRuleType="";
                _SELF.getCheckPlan();
            });
            
        },
        getCheckPlan(){
            //获取期次信息
            this.$store.dispatch("getCheckPlan",{plan_id:this.$route.params.id});
        },
        getCheckList() {
            //刷新指标库列表
            const URL = this.serverUrl + "/admin/checklist/index";
            const _SELF = this;
            this.emitAjax(URL, null, function(result) {
                _SELF.checkListArray = result;
            });
        },
        getOrgList(){
            //获取单位信息
            this.$store.dispatch("getOrgList");
        },
        getOrgInfo(){
            //获取单位信息
            const _this=this;
            this.schoolArray=[];
            this.college=[];
            this.labArray=[];
            if(this.orgList.length>0){
                for (let index = 0; index < this.orgList.length; index++) {
                    const element = this.orgList[index];
                    _this[element.org_level+"Array"].push(Object.assign({},element));
                }
            }
        },
        setCount() {
            //规则分类
            const _this = this;
            this.schoolRule = [];
            this.collegeRule = [];
            this.labRule = [];
            const ruleList = this.checkPlan.rule_list;
            if (ruleList) {
                for (let index = 0; index < ruleList.length; index++) {
                    const ruleListItem = ruleList[index];
                    switch (ruleListItem.level) {
                        case "school":
                            _this.schoolRule.push(ruleListItem);
                            break;
                        case "college":
                            _this.collegeRule.push(ruleListItem);
                            break;
                        case "lab":
                            _this.labRule.push(ruleListItem);
                        default:
                            break;
                    }
                }
            }
        },
    }
};
</script>
<style>
.cutomize_table th,
.cutomize_table td {
    text-align: center;
    vertical-align: middle;
}
</style>