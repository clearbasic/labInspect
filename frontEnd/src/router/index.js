import Vue from 'vue'
import Router from 'vue-router'
import Index from '../components/index'

Vue.use(Router)

export default new Router({
	mode: 'history',
	routes: [
		{
			path: '/',
			name: 'index.vue',
			component: Index,
			meta:{
				title:"首页 - 安全检查系统"
			}
		}
	]
})
