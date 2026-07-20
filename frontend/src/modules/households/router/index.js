export default [
  {
    path: '/households',
    name: 'HouseholdsList',
    component: () => import('../views/HouseholdsList.vue')
  },
  {
    path: '/households/create',
    name: 'HouseholdCreate',
    component: () => import('../views/HouseholdCreate.vue')
  },
  {
    path: '/households/:id',
    name: 'HouseholdDetail',
    component: () => import('../views/HouseholdDetail.vue')
  },
  {
    path: '/households/:id/edit',
    name: 'HouseholdEdit',
    component: () => import('../views/HouseholdEdit.vue')
  }
];
