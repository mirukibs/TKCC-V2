export default [
  {
    path: '/sacraments',
    name: 'SacramentsList',
    component: () => import('./views/SacramentsList.vue')
  },
  {
    path: '/sacraments/create',
    name: 'SacramentCreate',
    component: () => import('./views/SacramentCreate.vue')
  },
  {
    path: '/sacraments/:id',
    name: 'SacramentDetail',
    component: () => import('./views/SacramentDetail.vue')
  },
  {
    path: '/sacraments/:id/edit',
    name: 'SacramentEdit',
    component: () => import('./views/SacramentEdit.vue')
  }
];
