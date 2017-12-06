import {serverUrl} from "../config/server.js";

export default {
    setCheckPlan(context,data){
        const url = serverUrl + "";
        const result = {
            plan_id:1,
            plan_name:"2018年实验室检查",
            current:"no",
            plan_score:100,
            intro:"期次说明",
            task_list:[
                {
                    plan_id:1,
                    task_id:1,
                    task_name:"第1次",
                    task_level:"school",
                    dt_begin:"2017-12-04",
                    dt_end:"2018-01-30",
                },
                {
                    plan_id:1,
                    task_id:2,
                    task_name:"第2次",
                    task_level:"school",
                    dt_begin:"2017-12-04",
                    dt_end:"2018-01-30",
                },
                {
                    plan_id:1,
                    task_id:3,
                    task_name:"第1次",
                    task_level:"college",
                    dt_begin:"2017-12-04",
                    dt_end:"2018-01-30",
                },
                {   
                    plan_id:1,
                    task_id:4,
                    task_name:"第1次",
                    task_level:"lab",
                    dt_begin:"2017-12-04",
                    dt_end:"2018-01-30",
                }
            ],
            rule_list:[
                {
                    rule_id:1,
                    plan_id:1,
                    level:"school",
                    college_id:1,
                    lab_id:0,
                    checklist:{
                        "1":{
                            score:12
                        },
                        "2":{
                            score:32
                        }
                    },
                },{
                    rule_id:1,
                    plan_id:1,
                    level:"school",
                    college_id:1,
                    lab_id:0,
                    checklist:{
                        "1":{
                            score:12
                        },
                        "3":{
                            score:11
                        }
                    }
                },
                {
                    rule_id:1,
                    plan_id:1,
                    level:"college",
                    college_id:1,
                    lab_id:0,
                    checklist:{
                        "3":{
                            score:12
                        },
                        "2":{
                            score:11
                        }
                    }
                },
                {
                    rule_id:1,
                    plan_id:1,
                    level:"lab",
                    college_id:1,
                    lab_id:0,
                    checklist:{
                        "2":{
                            score:12
                        },
                        "3":{
                            score:11
                        }
                    }
                }
            ]
        }

        /* $.post(url,data,function(result){
        }); */
        if(data){
            context.commit("setCheckPlan",data);
        }else{
            context.commit("setCheckPlan",result);
        }
    }
}