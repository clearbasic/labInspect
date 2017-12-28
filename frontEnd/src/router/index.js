import Vue from 'vue'
import Router from 'vue-router'
import { pathName } from "../config/server";
import Index from '../components/index'
import CheckList from '../components/checkList.vue';
import CheckItem from '../components/checkItem/checkItem.vue';
import CheckPlan from '../components/checkPlan/checkPlan.vue';
import CheckPlanDetail from '../components/checkPlan/checkPlanDetail/checkPlanDetail.vue';
import CheckPlanSummary from '../components/checkPlan/checkPlanSummary.vue';
import CheckGroup from "../components/checkGroup/checkGroup.vue";
import CheckWork from "../components/checkWork/checkWork.vue";
import CheckWorkSetting from "../components/checkWork/checkWorkSetting.vue";
import CheckWorkProgress from "../components/checkWork/checkWorkProgress.vue";
import CheckWorkResult from "../components/checkWork/checkWorkResult.vue";
import MyCheck from "../components/myCheck/myCheck.vue";
import OrgList from "../components/org/orgList.vue";
import OrgEdit from "../components/org/orgEdit.vue";
import User from "../components/user/user.vue";
import Room from "../components/room/room.vue";
import Zone from "../components/room/zone.vue";
import RoomZoneList from "../components/room/roomZoneList.vue";
import Login from "../components/login/login.vue";
import Feedback from "../components/feedback/feedback.vue";
import Menu from "../components/admin/menu.vue";
import Rule from "../components/admin/rule.vue";
import Role from "../components/admin/role.vue";


Vue.use(Router)
let title = "实验室安全检查管理系统";

export default new Router({
	mode:"history",
	routes: [
		{
			path: pathName+'/',
			name: 'index',
			component: Index,
			meta:{
				title:"首页 - "+title,
				active:pathName+"/",
				permission:0,
			},
			children:[
				{
					path:pathName+"/checkList",
					name:"checkList",
					component:CheckList,
					meta:{
						title:"检查指标类别管理 - "+title,
						active:pathName+"/checkList",
						permission:9,
					}
				},{
					path:pathName+"/checkList/:id",
					name:"checkItem",
					component:CheckItem,
					meta:{
						title:"检查指标管理 - "+title,
						active:pathName+"/checkList",
						permission:9,
					}
				},{
					path:pathName+"/checkPlan",
					name:"checkPlan",
					component:CheckPlan,
					meta:{
						title:"检查期次 - "+title,
						active:pathName+"/checkPlan",
						permission:9,
					}
				},{
					path:pathName+"/checkPlan/:id",
					name:"checkPlanDetail",
					component:CheckPlanDetail,
					meta:{
						title:"检查期次 - "+title,
						active:pathName+"/checkPlan",
						permission:9,
					}
				},{
					path:pathName+"/checkPlanSummary",
					name:"checkPlanSummary",
					component:CheckPlanSummary,
					meta:{
						title:"工作说明 - "+title,
						active:pathName+"/checkWork",
						permission:0,
					}
				},{
					path:pathName+"/orgList",
					name:"OrgList",
					component:OrgList,
					meta:{
						title:"实验室单位列表 - "+title,
						active:pathName+"/orgList",
						permission:5,
					}
				},{
					path:pathName+"/orgEdit",
					name:"orgEdit",
					component:OrgEdit,
					meta:{
						title:"实验室单位编辑 - "+title,
						active:pathName+"/orgList",
						permission:5,
					}
				},{
					path:pathName+"/userList",
					name:"userList",
					component:User,
					meta:{
						title:"实验室用户管理 - "+title,
						active:pathName+"/userList",
						permission:5,
					}
				},{
					path:pathName+"/checkGroup",
					name:"checkGroup",
					component:CheckGroup,
					meta:{
						title:"检查小组 - "+title,
						active:pathName+"/checkGroup",
						permission:5,
					}
				},{
					path:pathName+"/checkWork",
					name:"checkWork",
					component:CheckWork,
					meta:{
						title:"检查工作 - "+title,
						active:pathName+"/checkWork",
						permission:5,
					}
				},{
					path:pathName+"/checkWork/setting/:id",
					name:"checkWorkSetting",
					component:CheckWorkSetting,
					meta:{
						title:"检查工作分配 - "+title,
						active:pathName+"/checkWork",
						permission:5,
					}
				},{
					path:pathName+"/checkWork/progress/:id",
					name:"checkWorkProgress",
					component:CheckWorkProgress,
					meta:{
						title:"检查工作进度 - "+title,
						active:pathName+"/checkWork",
						permission:5,
					}
				},{
					path:pathName+"/checkWork/result/:id",
					name:"checkWorkResult",
					component:CheckWorkResult,
					meta:{
						title:"检查工作结果 - "+title,
						active:pathName+"/checkWork",
						permission:5,
					}
				},{
					path:pathName+"/myCheck",
					name:"myCheck",
					component:MyCheck,
					meta:{
						title:"我的检查工作 - "+title,
						active:pathName+"/myCheck",
						permission:1,
					}
				},{
					path:pathName+"/room",
					name:"room",
					component:Room,
					meta:{
						title:"房间列表 - "+title,
						active:pathName+"/zone",
						permission:5,
					}
				},{
					path:pathName+"/zone",
					name:"zone",
					component:Zone,
					meta:{
						title:"房间分组列表 - "+title,
						active:pathName+"/zone",
						permission:5,
					}
				},{
					path:pathName+"/zone/:id",
					name:"roomZone",
					component:RoomZoneList,
					meta:{
						title:"房间分组 - "+title,
						active:pathName+"/zone",
						permission:5,
					}
				},{
					path:pathName+"/feedback",
					name:"feedback",
					component:Feedback,
					meta:{
						title:"问题反馈 - "+title,
						active:pathName+"/feedback",
						permission:1,
					}
				},{
					path:pathName+"/menu",
					name:"menu",
					component:Menu,
					meta:{
						title:"菜单设置 - "+title,
						active:pathName+"/menu",
						permission:1,
					}
				},{
					path:pathName+"/rule",
					name:"rule",
					component:Rule,
					meta:{
						title:"权限点设置 - "+title,
						active:pathName+"/rule",
						permission:1,
					}
				},{
					path:pathName+"/role",
					name:"role",
					component:Role,
					meta:{
						title:"角色设置 - "+title,
						active:pathName+"/role",
						permission:1,
					}
				}
			]
		},
		{
			path: pathName+'/login',
			name: 'login',
			component: Login,
			meta:{
				title:"登录 - "+title,
				active:pathName+"/login",
				permission:0,
			}
		}
	]
})
