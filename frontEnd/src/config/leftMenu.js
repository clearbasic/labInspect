//左侧菜单配置
let pathName = "";
export default [
    {
        name:"检查安排",
        url:pathName+"",
        icon:"ace-icon fa fa-book",
        child:[
            {
                name:"检查指标",
                url:pathName+"/checkList",
                icon:"",
            },
            {
                name:"检查期次",
                url:pathName+"/checkPlan",
                icon:"",
            }
        ]
    },
    {
        name:"检查工作",
        url:pathName+"",
        icon:"ace-icon glyphicon glyphicon-check",
        child:[
            {
                name:"检查小组",
                url:pathName+"/checkGroup",
                icon:"",
            },
            {
                name:"检查工作",
                url:pathName+"/checkWork",
                icon:"",
            },
            {
                name:"问题反馈",
                url:pathName+"",
                icon:"",
            }
        ]
    },
    {
        name:"统计总结",
        url:pathName+"",
        icon:"ace-icon fa fa-bar-chart-o",
        child:[
            {
                name:"安全责任人登录表",
                url:pathName+"",
                icon:"",
            },
            {
                name:"检查统计表",
                url:pathName+"",
                icon:"",
            },
            {
                name:"评奖评优",
                url:pathName+"",
                icon:"",
            }
        ]
    },
    {
        name:"系统设置",
        url:pathName+"",
        icon:"ace-icon fa fa-cog",
        child:[
            {
                name:"实验室单位设置",
                url:pathName+"/orgList",
                icon:'',
            },
            {
                name:"用户设置",
                url:pathName+"/userList",
                icon:'',
            }
        ]
    },
    {
        name:"退出系统",
        url:pathName+"",
        icon:"ace-icon glyphicon glyphicon-off"
    }
]