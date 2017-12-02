import Vue from 'vue'
import Router from 'vue-router'
import Index from '../components/index'
import CheckList from '../components/checkList';
import CheckItem from '../components/checkItem/checkItem';
import CheckPlan from '../components/checkPlan/checkPlan';
import CheckPlanDetail from '../components/checkPlan/checkPlanDetail';


Vue.use(Router)
let title = "安全检查系统";
let pathName = "";
export default new Router({
	mode:"history",
	routes: [
		{
			path: pathName+'/',
			name: 'index.vue',
			component: Index,
			meta:{
				title:"首页 - "+title,
				active:pathName+"/"
			}
		},
		{
			path:pathName+"/checkList",
			name:"checkList.vue",
			component:CheckList,
			meta:{
				title:"检查指标类别管理 - "+title,
				active:pathName+"/checkList"
			}
		},
		{
			path:pathName+"/checkList/:id",
			name:"checkItem.vue",
			component:CheckItem,
			meta:{
				title:"检查指标管理 - "+title,
				active:pathName+"/checkList"
			}
		},
		{
			path:pathName+"/checkPlan",
			name:"checkPlan.vue",
			component:CheckPlan,
			meta:{
				title:"检查期次 - "+title,
				active:pathName+"/checkPlan"
			}
		},
		{
			path:pathName+"/checkPlan/:id",
			name:"checkPlanDetail.vue",
			component:CheckPlanDetail,
			meta:{
				title:"检查期次 - "+title,
				active:pathName+"/checkPlan"
			}
		}
	]
})
