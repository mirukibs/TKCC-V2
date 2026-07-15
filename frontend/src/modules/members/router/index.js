import MembersList from '../views/MembersList.vue';
import MemberCreate from '../views/MemberCreate.vue';
import MemberDetail from '../views/MemberDetail.vue';

export default [
    {
        path: '/members',
        name: 'members.index',
        component: MembersList
    },
    {
        path: '/members/create',
        name: 'members.create',
        component: MemberCreate
    },
    {
        path: '/members/:id',
        name: 'members.show',
        component: MemberDetail
    }
];
