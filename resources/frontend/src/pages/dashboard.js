/*eslint no-unused-vars:0 */
import _base from 'src/pages/_base'
import VueRouter from 'vue-router'
_base.Vue.use(VueRouter)

var App = {}
var Router = new VueRouter({
  // mode: 'history',
  routes: [
    {
      path: '/',
      component: function (resolve) {
        require(['src/components/views/dashboard/index.vue'], resolve)
      }
    }
  ]
})

_base.initialize(App, Router)
