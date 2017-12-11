import Vue from 'vue'
import Router from 'vue-router'
import Index from '../components/index'
import CheckList from '../components/checkList';
import CheckItem from '../components/checkItem/checkItem';
import CheckPlan from '../components/checkPlan/checkPlan';
import CheckPlanDetail from '../components/checkPlan/checkPlanDetail/checkPlanDetail';
import CheckGroup from "../components/checkGroup/checkGroup";
import CheckWork from "../components/checkWork/checkWork";
import WorkSetting from "../components/checkWork/workSetting.vue";
import WorkPending from "../components/checkWork/workPending.vue";
import WorkFinished from "../components/checkWork/workFinished.vue";
import OrgList from "../components/org/orgList.vue";
import OrgEdit from "../components/org/orgEdit.vue";


Vue.use(Router)
let title = "安全检查系统";
let pathName = "";
export default new Router({
	mode:"history",
	routes: [
		{
			path: pathName+'/',
			name: 'index',
			component: Index,
			meta:{
				title:"首页 - "+title,
				active:pathName+"/"
			}
		},
		{
			path:pathName+"/checkList",
			name:"checkList",
			component:CheckList,
			meta:{
				title:"检查指标类别管理 - "+title,
				active:pathName+"/checkList"
			}
		},
		{
			path:pathName+"/checkList/:id",
			name:"checkItem",
			component:CheckItem,
			meta:{
				title:"检查指标管理 - "+title,
				active:pathName+"/checkList"
			}
		},
		{
			path:pathName+"/checkPlan",
			name:"checkPlan",
			component:CheckPlan,
			meta:{
				title:"检查期次 - "+title,
				active:pathName+"/checkPlan"
			}
		},
		{
			path:pathName+"/checkPlan/:id",
			name:"checkPlanDetail",
			component:CheckPlanDetail,
			meta:{
				title:"检查期次 - "+title,
				active:pathName+"/checkPlan"
			}
		},
		{
			path:pathName+"/orgList",
			name:"OrgList",
			component:OrgList,
			meta:{
				title:"实验室单位列表 - "+title,
				active:pathName+"/orgList"
			}
		},
		{
			path:pathName+"/orgEdit",
			name:"orgEdit",
			component:OrgEdit,
			meta:{
				title:"实验室单位编辑 - "+title,
				active:pathName+"/orgList"
			}
		},
		{
			path:pathName+"/checkGroup",
			name:"checkGroup",
			component:CheckGroup,
			meta:{
				title:"检查小组 - "+title,
				active:pathName+"/checkGroup"
			}
		},
		{
			path:pathName+"/checkWork",
			name:"checkWork",
			component:CheckWork,
			meta:{
				title:"检查工作 - "+title,
				active:pathName+"/checkWork"
			}
		},
		{
			path:pathName+"/checkWork/setting",
			name:"checkWorksetting",
			component:WorkSetting,
			meta:{
				title:"任务安排 - "+title,
				active:pathName+"/checkWork"
			}
		},
		{
			path:pathName+"/checkWork/pending",
			name:"checkWorkpending",
			component:WorkPending,
			meta:{
				title:"进度检查 - "+title,
				active:pathName+"/checkWork"
			}
		},
		{
			path:pathName+"/checkWork/finished",
			name:"checkWorkfinished",
			component:WorkFinished,
			meta:{
				title:"检查结果 - "+title,
				active:pathName+"/checkWork"
			}
		}
	]
})
