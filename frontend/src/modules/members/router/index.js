export default [
    {
        path: '/members',
        name: 'members.index',
        component: () => import('../views/MembersList.vue')
    }
];
