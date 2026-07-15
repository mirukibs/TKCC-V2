import { createRouter, createWebHistory } from 'vue-router';
import membersRoutes from '../modules/members/router';

const routes = [
    {
        path: '/',
        name: 'home',
        redirect: '/members'
    },
    ...membersRoutes
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

export default router;
