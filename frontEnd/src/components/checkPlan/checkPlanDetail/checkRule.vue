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
                <div :id="ruleConfig.type+'Rule'" :class="['tab-pane', 'fade', {active:ruleConfig.type == 'lab'},{in:ruleConfig.type == 'lab'}]" 
                        v-for="(ruleConfig,ruleIndex) in ruleTemplateConfig" :key="'ruleconfig'+ruleIndex">
                    <div class="clearfix">
                        <button class="btn btn-primary btn-sm" @click="showAddRule(ruleConfig.type)">添加{{ruleConfig.name}}</button>
                        <p class="pull-right">
                            从
                            <select name="" id="">
                                <option :value="copy.value" v-for="copy in ruleConfig.other" :key="'copy'+copy.value">{{copy.name}}</option>
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
                                <tr v-for="(rule,index) in ruleConfig.dataArray" :key="ruleConfig.type+'Rule'+index">
                                    <td style="text-align:left;">
                                        <span v-for="(college,index) in orgList" :key="'collegeArray'+index" v-if="college.org_id == rule.college_id">{{college.org_name}}</span>
                                        <span v-if="!rule.college_id">全部</span>
                                        - 
                                        <span v-for="(lab,index) in orgList" :key="'collegeArray'+index"  v-if="lab.org_id == rule.lab_id">{{lab.org_name}}</span>
                                        <span v-if="!rule.lab_id">全部</span>
                                   </td>
                                   <td v-for="checkList in checkListArray" :key="'checklist'+checkList.id">
                                        <input type="text" 
                                            :value="rule.checklist[''+checkList.id+'']?rule.checklist[''+checkList.id+''].score:0"
                                            style="width:50px;"
                                            @blur="changeScore(checkList.id,index,ruleConfig.type,$event)"
                                            @keyup="changeScore(checkList.id,index,ruleConfig.type,$event)"
                                        />
                                   </td>
                                   <td v-html="computeTotalScore(index,ruleConfig.type)"></td>
                                   <td class="center little">
                                       <button class="btn btn-danger btn-xs" @click="delRule(rule)">
                                           <i class="ace-icon fa fa-trash-o bigger-110"></i>
                                       </button>
                                   </td>
                                </tr>
                                <!-- 添加新规则 -->
                                <tr v-if="addRule && addRuleType == ruleConfig.type">
                                    <td style="text-align:left;">
                                       <select v-model="newRule.college_id" @change="getLabList(newRule.college_id)">
                                           <option value="0">--全部--</option>
                                           <option v-for="(college,index) in collegeArray" :key="'collegeArray'+index" 
                                                :value="college.org_id"
                                           >{{college.org_name}}</option>
                                       </select>
                                       <select v-model="newRule.lab_id">
                                           <option value="0">--全部--</option>
                                            <option v-for="(lab,index) in labArray" :key="'collegeArray'+index" 
                                                :value="lab.org_id"
                                           >{{lab.org_name}}</option>
                                       </select>
                                   </td>
                                   <td v-for="checkList in checkListArray" :key="'checklist'+checkList.id" class="newRuleScore">
                                        <input type="text" 
                                            :value="newRule.checklist[''+checkList.id+'']?newRule.checklist[''+checkList.id+''].score:0"
                                            style="width:50px;"
                                            :data-checkList="checkList.id"
                                            @blur="changeScore(checkList.id,null,null,$event)"
                                            @keyup="changeScore(checkList.id,null,null,$event)"
                                        />
                                   </td>
                                   <td id="totalScore"></td>
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
            isKeyUp: false, //是否按键了
            addRule:false,
            addRuleType:"",
            newRule:{
                plan_id:0,
                level:"lab",
                college_id:0,
                lab_id:0,
                checklist:{},
            },
            ruleTemplateConfig:[
                {
                    type:"lab",
                    dataArray:[],
                    name:"自查规则",
                    other:[
                        {
                            name:"复查规则",
                            value:"college",
                        },
                        {
                            name:"抽查规则",
                            value:"school",
                        }
                    ]
                },
                {
                    type:"college",
                    dataArray:[],
                    name:"复查规则",
                    other:[
                        {
                            name:"自查规则",
                            value:"lab",
                        },
                        {
                            name:"抽查规则",
                            value:"school",
                        }
                    ]
                },
                {
                    type:"school",
                    dataArray:[],
                    name:"抽查规则",
                    other:[
                        {
                            name:"自查规则",
                            value:"lab",
                        },
                        {
                            name:"复查规则",
                            value:"college",
                        }
                    ]
                }
            ]
        };
    },
    methods: {
        computeTotalScore(index, type) {
            //计算总分
            let ruleArry = [];
            const _this = this;
            if(type == "lab"){
                ruleArry = _this.ruleTemplateConfig[0].dataArray
            }else if(type == "college"){
                ruleArry = _this.ruleTemplateConfig[1].dataArray
            }else if(type == "school"){
                ruleArry = _this.ruleTemplateConfig[2].dataArray
            }
            let rule = null;
            if(!type){
                rule = _this.newRule;
            }else{
                rule = ruleArry[index];
            }
            let { checklist } = rule;
            let score = 0;
            for (const key in checklist) {
                if (checklist.hasOwnProperty(key)) {
                    const element = checklist[key];
                    score += parseFloat(element.score);
                }
            }
            if (parseFloat(this.$store.state.checkPlan.plan.plan_score) == score) {
                !type && $("#totalScore").html("<span class='green'>" + score + "</span>");
                return "<span class='green'>" + score + "</span>";
            } else {
                !type && $("#totalScore").html("<span class='red'>" + score + "</span>");
                return "<span class='red'>" + score + "</span>";
            }
        },
        changeScore(checkListId, index, type, event) {
            //修改分数
            if (event.type === "keyup" && event.key != "Enter") {
                return false;
            }
            const _this= this;
            let ruleArry = [];
            if(type == "lab"){
                ruleArry = _this.ruleTemplateConfig[0].dataArray
            }else if(type == "college"){
                ruleArry = _this.ruleTemplateConfig[1].dataArray
            }else if(type == "school"){
                ruleArry = _this.ruleTemplateConfig[2].dataArray
            }
            if (!this.isKeyUp) {
                let rule = null;
                if(!type){
                    rule = _this.newRule;
                }else{
                    rule = ruleArry[index];
                }
                let { checklist } = rule;
                
                let value = event.target.value ? event.target.value : 0;
                if (checklist[checkListId]) {
                    checklist[checkListId].score = value;
                } else {
                    checklist[checkListId] = {
                        score: value
                    };
                }
                //修改规则
                if(type){
                    const URL = _this.serverUrl + "/admin/rule/edit";
                    _this.emitAjax(URL, rule, null,function(result) {
                        _this.getCheckPlan();
                    });
                }else {
                    _this.computeTotalScore();
                }
            }
            if (event.type === "blur") {
                this.isKeyUp = false;
            }else if(event.type === "keyup"){
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
            const data = Object.assign({},this.newRule,{
                plan_id:this.$route.params.id,
                level:this.addRuleType,
            })
            
            this.emitAjax(URL, data, function(result) {
                _SELF.newRule={
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
        delRule(rule){
            //删除规则
            if(window.confirm("是否要删除这条规则，此操作不可逆，请慎重！")){
                const URL = this.serverUrl + "/admin/rule/del";
                const _SELF = this;
                const data = {
                    plan_id:rule.plan_id,
                    college_id:rule.college_id,
                    lab_id:rule.lab_id,
                    level:rule.level,
                }
                this.emitAjax(URL, data, function(result) {
                    _SELF.getCheckPlan();
                });
            }
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
                    if(element.org_state != "no"){
                        _this[element.org_level+"Array"].push(Object.assign({},element));
                    }
                }
            }
        },
        getLabList(id){
            const _this = this;
            this.labArray = [];
            if(this.orgList.length>0){
                for (let index = 0; index < _this.orgList.length; index++) {
                    const element = _this.orgList[index];
                    if(element.org_level == "lab" && element.org_state != "no"){
                        if(id != 0 ){
                            if(element.pid == id){
                                _this.labArray.push(Object.assign({},element));
                            }
                        }else{
                            _this.labArray.push(Object.assign({},element));
                        }
                    }
                }
            }
        },
        setCount() {
            //规则分类
            const _this = this;
            const ruleList = this.checkPlan.rule_list;
            this.ruleTemplateConfig[0].dataArray = [];
            this.ruleTemplateConfig[1].dataArray = [];
            this.ruleTemplateConfig[2].dataArray = [];
            if (ruleList) {
                for (let index = 0; index < ruleList.length; index++) {
                    const ruleListItem = ruleList[index];
                    switch (ruleListItem.level) {
                        case "school":
                            _this.ruleTemplateConfig[2].dataArray.push(ruleListItem);
                            break;
                        case "college":
                            _this.ruleTemplateConfig[1].dataArray.push(ruleListItem);
                            break;
                        case "lab":
                            _this.ruleTemplateConfig[0].dataArray.push(ruleListItem);
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