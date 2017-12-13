// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router';
import { serverUrl } from "./config/server.js";
import { emitAjax } from "./assets/common.js";
import store from './vuex';

Vue.config.productionTip = false;
Vue.prototype.pathName = "";
Vue.prototype.serverUrl = serverUrl;
Vue.prototype.emitAjax = emitAjax;
//这是钩子进入页面之前,就修改title
router.beforeEach((to, from, next) => {
  document.title = to.meta.title    //这 to.meta.title 是在router里设置的
  next()
})

/* eslint-disable no-new */
new Vue({
  el: '#myApp',
  router,
  template: '<App/>',
  components: { App },
  store,
})
