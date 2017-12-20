import { pathName } from "./server";
//左侧菜单配置
export default [
    {
        name:"检查安排",
        url:pathName+"",
        icon:"ace-icon fa fa-book",
        permission:9,
        child:[
            {
                name:"检查指标",
                url:pathName+"/checkList",
                permission:9,
                icon:"",
            },
            {
                name:"检查期次",
                url:pathName+"/checkPlan",
                permission:9,
                icon:"",
            }
        ]
    },
    {
        name:"检查工作",
        url:pathName+"",
        icon:"ace-icon glyphicon glyphicon-check",
        permission:1,
        child:[
            {
                name:"检查小组",
                url:pathName+"/checkGroup",
                permission:5,
                icon:"",
            },
            {
                name:"检查工作",
                url:pathName+"/checkWork",
                permission:5,
                icon:"",
            },
            {
                name:"问题反馈",
                url:pathName+"",
                permission:1,
                icon:"",
            }
        ]
    },
    {
        name:"统计总结",
        url:pathName+"",
        icon:"ace-icon fa fa-bar-chart-o",
        permission:7,
        child:[
            {
                name:"安全责任人登录表",
                url:pathName+"",
                permission:7,
                icon:"",
            },
            {
                name:"检查统计表",
                url:pathName+"",
                permission:7,
                icon:"",
            },
            {
                name:"评奖评优",
                url:pathName+"",
                permission:7,
                icon:"",
            }
        ]
    },
    {
        name:"系统设置",
        url:pathName+"",
        icon:"ace-icon fa fa-cog",
        permission:5,
        child:[
            {
                name:"实验室单位设置",
                url:pathName+"/orgList",
                permission:5,
                icon:'',
            },
            {
                name:"用户设置",
                url:pathName+"/userList",
                permission:5,
                icon:'',
            },
            {
                name:"房间设置",
                url:pathName+"/zone",
                permission:5,
                icon:'',
            }
        ]
    },
    {
        name:"退出系统",
        url:pathName+"/logout",
        icon:"ace-icon glyphicon glyphicon-off",
        permission:0,
    }
]