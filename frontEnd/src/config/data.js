const checkPlan = {
    plan:{
        plan_id:1,
        plan_name:"2018年实验室检查",
        current:"no",
        plan_score:100,
        intro:"期次说明",
    },
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
const checkWork = {
    plan:{
        plan_id:1,
        plan_name:"2018年实验室检查",
        current:"yes",
        plan_score:100,
        intro:"期次说明",
    },
    task_list:[
        {   
            org:{
                org_id:1,
                parent_id:1,
                org_name:"化学品实验室",
                org_level:"school"
            },
            tasks:{
                lab:[{   
                        task_id:3,
                        check_id:3,
                        task_name:"第2次",
                        task_level:"lab",
                        dt_begin:"2017-12-04",
                        dt_end:"2018-01-30",
                        college_dt_begin:"2017-12-04",
                        college_dt_end:"2018-01-30",
                        check_state:"finished",
                        check_score:65,
                        problem_fatal:10,
                        problem_common:19,
                        review_state:"no-start",
                        review:"",
                    }
                ],
                college:[{   
                        task_id:2,
                        check_id:2,
                        task_name:"第2次",
                        task_level:"college",
                        dt_begin:"2017-12-04",
                        dt_end:"2018-01-30",
                        college_dt_begin:"2017-12-04",
                        college_dt_end:"2018-01-30",
                        check_state:"pending",
                        check_score:65,
                        problem_fatal:10,
                        problem_common:19,
                        review_state:"no-start",
                        review:"",
                    }],
                school:[
                    {   
                        task_id:1,
                        check_id:1,
                        task_name:"第1次",
                        task_level:"school",
                        dt_begin:"2017-12-04",
                        dt_end:"2018-01-30",
                        college_dt_begin:"2017-12-04",
                        college_dt_end:"2018-01-30",
                        check_state:"no-start",
                        check_score:65,
                        problem_fatal:10,
                        problem_common:19,
                        review_state:"no-need",
                        review:"",
                    }]
            }
        },
        {   
            org:{
                org_id:2,
                parent_id:2,
                org_name:"物理实验室",
                org_level:"lab"
            },
            tasks:{
                lab:[
                    {   
                        task_id:1,
                        check_id:1,
                        task_name:"第1次",
                        task_level:"lab",
                        dt_begin:"2017-12-04",
                        dt_end:"2018-01-30",
                        college_dt_begin:"2017-12-04",
                        college_dt_end:"2018-01-30",
                        check_state:"no-start",
                        check_score:65,
                        problem_fatal:10,
                        problem_common:19,
                        review_state:"no-need",
                        review:"",
                    }],
                college:[{   
                        task_id:2,
                        check_id:2,
                        task_name:"第2次",
                        task_level:"college",
                        dt_begin:"2017-12-04",
                        dt_end:"2018-01-30",
                        college_dt_begin:"2017-12-04",
                        college_dt_end:"2018-01-30",
                        check_state:"pending",
                        check_score:65,
                        problem_fatal:10,
                        problem_common:19,
                        review_state:"no-start",
                        review:"",
                    }],
                school:[{   
                        task_id:3,
                        check_id:3,
                        task_name:"第2次",
                        task_level:"school",
                        dt_begin:"2017-12-04",
                        dt_end:"2018-01-30",
                        college_dt_begin:"2017-12-04",
                        college_dt_end:"2018-01-30",
                        check_state:"finished",
                        check_score:65,
                        problem_fatal:10,
                        problem_common:19,
                        review_state:"no-start",
                        review:"",
                    }
                ]
            }
        }
    ]
}
const orgList = [{
    org_id:1,
    parent_id:0,
    org_name:"浙江大学",
    org_level:"school",
    org_alias:"浙江大学别名",
    org_address:"浙江大学地址",
},{
    org_id:2,
    parent_id:0,
    org_name:"杭州师范大学",
    org_level:"school",
    org_alias:"杭州师范大学别名",
    org_address:"杭州师范大学地址",
}];
export {
    checkWork,
    checkPlan,
    orgList,
};