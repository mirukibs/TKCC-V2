import api from '@/plugins/axios';

export default {
    async getMembers(params = {}) {
        const response = await api.get('/members', { params });
        return response.data;
    },

    async getMember(id) {
        const response = await api.get(`/members/${id}`);
        return response.data;
    },

    async createMember(memberData) {
        const response = await api.post('/members', memberData);
        return response.data;
    },

    async updateMember(id, memberData) {
        const response = await api.put(`/members/${id}`, memberData);
        return response.data;
    },

    async deleteMember(id) {
        const response = await api.delete(`/members/${id}`);
        return response.data;
    }
};
