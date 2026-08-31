const ZonesList = () => import('./views/ZonesList.vue');
const ZoneCreate = () => import('./views/ZoneCreate.vue');
const ZoneEdit = () => import('./views/ZoneEdit.vue');
const ZoneDetail = () => import('./views/ZoneDetail.vue');

const routes = [
    {
        path: '/zones',
        name: 'zones.index',
        component: ZonesList
    },
    {
        path: '/zones/create',
        name: 'zones.create',
        component: ZoneCreate
    },
    {
        path: '/zones/:id',
        name: 'zones.show',
        component: ZoneDetail,
        props: true
    },
    {
        path: '/zones/:id/edit',
        name: 'zones.edit',
        component: ZoneEdit,
        props: true
    }
];

export default routes;
