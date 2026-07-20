import MembersList from '../views/MembersList.vue';
import MemberCreate from '../views/MemberCreate.vue';
import MemberDetail from '../views/MemberDetail.vue';

import MemberEdit from '../views/MemberEdit.vue';

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
    },
    {
        path: '/members/:id/edit',
        name: 'members.edit',
        component: MemberEdit
    }
];
