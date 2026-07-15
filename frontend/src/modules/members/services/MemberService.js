import api from '@/plugins/axios';

export default {
    async getMembers() {
        const response = await api.get('/members');
        return response.data;
    },

    async getMember(id) {
        const response = await api.get(`/members/${id}`);
        return response.data;
    },

    async createMember(memberData) {
        const response = await api.post('/members', memberData);
        return response.data;
    }
};
