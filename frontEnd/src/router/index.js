import Vue from 'vue'
import Router from 'vue-router'
import { pathName } from "../config/server";
import Index from '../components/index'
import CheckList from '../components/checkList';
import CheckItem from '../components/checkItem/checkItem';
import CheckPlan from '../components/checkPlan/checkPlan';
import CheckPlanDetail from '../components/checkPlan/checkPlanDetail/checkPlanDetail';
import CheckGroup from "../components/checkGroup/checkGroup";
import CheckWork from "../components/checkWork/checkWork";
import CheckWorkList from "../components/checkWork/CheckWorkList";
import OrgList from "../components/org/orgList";
import OrgEdit from "../components/org/orgEdit";
import User from "../components/user/user";
import Room from "../components/room/room";
import Zone from "../components/room/zone";
import Login from "../components/login/login";


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
			}
		},{
			path: pathName+'/login',
			name: 'login',
			component: Login,
			meta:{
				title:"登录 - "+title,
				active:pathName+"/login",
				permission:0,
			}
		},{
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
				permission:7,
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
			path:pathName+"/checkWork/:id",
			name:"checkWorkList",
			component:CheckWorkList,
			meta:{
				title:"检查工作列表 - "+title,
				active:pathName+"/checkWork",
				permission:5,
			}
		},{
			path:pathName+"/room",
			name:"room",
			component:Room,
			meta:{
				title:"房间列表 - "+title,
				active:pathName+"/room",
				permission:5,
			}
		},{
			path:pathName+"/zone",
			name:"zone",
			component:Zone,
			meta:{
				title:"房间分组列表 - "+title,
				active:pathName+"/room",
				permission:5,
			}
		}
	]
})
