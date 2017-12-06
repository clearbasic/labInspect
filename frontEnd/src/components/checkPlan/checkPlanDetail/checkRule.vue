<template>
    <div class="checkRule">
        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#schoolRule" data-toggle="tab">自查规则</a></li>
                <li><a href="#collegeRule" data-toggle="tab">复查规则</a></li>
                <li><a href="#labRule" data-toggle="tab">抽查规则</a></li>
            </ul>
            <div class="tab-content">
                <!-- 自查规则 -->
                <div id="schoolRule" class="tab-pane fade active in">
                    <div class="clearfix">
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
                            </tr>
                           </thead>
                           <tbody>
                               <tr v-for="(rule,index) in schoolRule" :key="'schoolRule'+index">
                                   <td style="text-align:left;">
                                       <select name="" id="">
                                           <option :value="rule.college_id">{{rule.college_id}}</option>
                                       </select>
                                       <select name="" id="">
                                            <option :value="rule.lab_id">{{rule.lab_id}}</option>
                                       </select>
                                   </td>
                                   <td v-for="checkList in checkListArray" :key="'checklist'+checkList.id">
                                        <input type="text" 
                                            :value="rule.checklist[''+checkList.id+'']?rule.checklist[''+checkList.id+''].score:0"
                                            style="width:30px;"
                                            @blur="changeScore(checkList.id,index,'school',$event)"
                                        />
                                   </td>
                                   <td v-html="computeTotalScore(index,'school')"></td>
                               </tr>
                           </tbody>
                       </table>
                   </div>
                </div>
                <!-- 复查规则 -->
                <div id="collegeRule" class="tab-pane fade">
                    <div class="clearfix">
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
                            </tr>
                           </thead>
                           <tbody>
                               <tr v-for="(rule,index) in collegeRule" :key="'collegeRule'+index">
                                   <td style="text-align:left;">
                                       <select name="" id="">
                                           <option :value="rule.college_id">{{rule.college_id}}</option>
                                       </select>
                                       <select name="" id="">
                                            <option :value="rule.lab_id">{{rule.lab_id}}</option>
                                       </select>
                                   </td>
                                   <td v-for="checkList in checkListArray" :key="'checklist'+checkList.id">
                                        <input type="text" 
                                            :value="rule.checklist[''+checkList.id+'']?rule.checklist[''+checkList.id+''].score:0"
                                            style="width:30px;"
                                            @blur="changeScore(checkList.id,index,'college',$event)"
                                        />
                                   </td>
                                   <td v-html="computeTotalScore(index,'college')"></td>
                               </tr>
                           </tbody>
                       </table>
                    </div>
                </div>
                <!-- 抽查规则 -->
                <div id="labRule" class="tab-pane fade">
                    <div class="clearfix">
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
                            </tr>
                           </thead>
                           <tbody>
                               <tr v-for="(rule,index) in labRule" :key="'labRule'+index">
                                   <td style="text-align:left;">
                                       <select name="" id="">
                                           <option :value="rule.college_id">{{rule.college_id}}</option>
                                       </select>
                                       <select name="" id="">
                                            <option :value="rule.lab_id">{{rule.lab_id}}</option>
                                       </select>
                                   </td>
                                   <td v-for="checkList in checkListArray" :key="'checklist'+checkList.id">
                                        <input type="text" 
                                            :value="rule.checklist[''+checkList.id+'']?rule.checklist[''+checkList.id+''].score:0"
                                            style="width:30px;"
                                            @blur="changeScore(checkList.id,index,'lab',$event)"
                                        />
                                   </td>
                                   <td v-html="computeTotalScore(index,'lab')"></td>
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
        name:"checkRule",
        data(){
            return {
                collegeArray:[{
                    dept_id:1,
                    dept_name:"学院名字"
                }],//储存学院选项
                labArray:[{
                    lab_id:1,
                    lab_name:"实验室名字"
                }],//实验室选项
                checkListArray:[{
                    id:1,
                    name:"教师检查指标库",
                    intro:"描述",
                    group_order:"",
                    creator:"李四",
                    dt_create:"2017-10-13",
                    count:12
                },
                {
                    id:2,
                    name:"课桌检查指标库",
                    intro:"描述",
                    group_order:"",
                    creator:"李四",
                    dt_create:"2017-10-13",
                    count:12
                },
                {
                    id:3,
                    name:"药品检查指标库",
                    intro:"描述",
                    group_order:"",
                    creator:"李四",
                    dt_create:"2017-10-13",
                    count:12
                }],//指标库列表
                schoolRule:[], //自查规则列表数组
                collegeRule:[],//复查规则列表数组
                labRule:[],//抽查规则列表数组
            }
        },
        methods:{
            setCount(){//规则分类
                const _this = this;
                this.schoolRule = [];
                this.collegeRule = [];
                this.labRule = [];
                const ruleList = this.checkPlan.rule_list;
                if(ruleList){
                    for (let index = 0; index < ruleList.length; index++) {
                        const ruleListItem = ruleList[index];
                        switch (ruleListItem.level) {
                            case "school":
                                _this.schoolRule.push(ruleListItem)
                                break;
                            case "college":
                                _this.collegeRule.push(ruleListItem)
                                break;
                            case "lab":
                                _this.labRule.push(ruleListItem)
                            default:
                                break;
                        }
                    }
                }
            },
            computeTotalScore(index,type){
                //计算总分
                let ruleArry = this[type+"Rule"];
                let rule = ruleArry[index];
                let {checklist} = rule;
                let score = 0;
                for (const key in checklist) {
                    if (checklist.hasOwnProperty(key)) {
                        const element = checklist[key];
                        score+= parseFloat(element.score)
                    }
                }
                if(this.$store.state.checkPlan.plan_score == score){
                    return "<span class='green'>"+score+"</span>";
                }else{
                    return "<span class='red'>"+score+"</span>";
                }
            },
            changeScore(checkListId,index,type,event){
                let rule = this[type+"Rule"][index];
                let {checklist} = rule;
                let value = event.target.value?event.target.value:0;
                if(checklist[checkListId]){
                    checklist[checkListId].score = value;
                }else{
                    checklist[checkListId] = {
                        score:value
                    }
                }
                this.$store.dispatch("setCheckPlan",this.checkPlan);
            }   
        },
        computed:{
            checkPlan(){
                return this.$store.state.checkPlan
            }
        },
        watch:{
            checkPlan:function(){
                this.setCount();
            }
        }
    };
</script>
<style>
    .cutomize_table th,.cutomize_table td {
        text-align:center;
        vertical-align:middle;
    }
</style>