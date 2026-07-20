import api from '@/plugins/axios';

export default {
    async getHouseholds(params = {}) {
        const response = await api.get('/households', { params });
        return response.data;
    },
    
    async getHousehold(id) {
        const response = await api.get(`/households/${id}`);
        return response.data;
    },
    
    async createHousehold(householdData) {
        const response = await api.post('/households', householdData);
        return response.data;
    },

    async updateHousehold(id, householdData) {
        const response = await api.put(`/households/${id}`, householdData);
        return response.data;
    },

    async deleteHousehold(id) {
        const response = await api.delete(`/households/${id}`);
        return response.data;
    }
};
