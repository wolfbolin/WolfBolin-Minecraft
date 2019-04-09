import Vue from 'vue'
import Router from 'vue-router'
import Index from '@/components/Index'
import Document from '@/components/Document'
import Data from '@/components/Data'

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Index',
      component: Index
    },
    {
      path: '/document',
      name: 'Document',
      component: Document
    },
    {
      path: '/data',
      name: 'Data',
      component: Data
    }
  ]
})
