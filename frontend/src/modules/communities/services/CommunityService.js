import api from '@/plugins/axios';

const API_URL = '/communities';

class CommunityService {
    async getAll() {
        const response = await api.get(API_URL);
        return response.data.data;
    }

    async getById(id) {
        const response = await api.get(`${API_URL}/${id}`);
        return response.data.data;
    }

    async create(data) {
        const response = await api.post(API_URL, data);
        return response.data;
    }

    async update(id, data) {
        const response = await api.put(`${API_URL}/${id}`, data);
        return response.data;
    }

    async delete(id) {
        await api.delete(`${API_URL}/${id}`);
    }
}

export default new CommunityService();
