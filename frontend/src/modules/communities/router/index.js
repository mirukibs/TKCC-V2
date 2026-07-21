import CommunitiesList from '../views/CommunitiesList.vue';
import CommunityCreate from '../views/CommunityCreate.vue';
import CommunityEdit from '../views/CommunityEdit.vue';
import CommunityDetail from '../views/CommunityDetail.vue';

export default [
    {
        path: '/communities',
        name: 'CommunitiesList',
        component: CommunitiesList
    },
    {
        path: '/communities/create',
        name: 'CommunityCreate',
        component: CommunityCreate
    },
    {
        path: '/communities/:id',
        name: 'CommunityDetail',
        component: CommunityDetail,
        props: true
    },
    {
        path: '/communities/:id/edit',
        name: 'CommunityEdit',
        component: CommunityEdit,
        props: true
    }
];
