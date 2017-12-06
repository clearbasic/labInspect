import Vue from 'vue'
import Router from 'vue-router'
import Index from '../components/index'
import CheckList from '../components/checkList';
import CheckItem from '../components/checkItem/checkItem';
import CheckPlan from '../components/checkPlan/checkPlan';
import CheckPlanDetail from '../components/checkPlan/checkPlanDetail/checkPlanDetail';
import CheckGroup from "../components/checkGroup/checkGroup";


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
			path:pathName+"/checkGroup",
			name:"checkGroup",
			component:CheckGroup,
			meta:{
				title:"检查小组 - "+title,
				active:pathName+"/checkGroup"
			}
		}
	]
})
