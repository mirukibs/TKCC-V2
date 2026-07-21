import axios from 'axios';

const API_URL = '/api/communities';

class CommunityService {
    async getAll() {
        const response = await axios.get(API_URL);
        return response.data.data;
    }

    async getById(id) {
        const response = await axios.get(`${API_URL}/${id}`);
        return response.data.data;
    }

    async create(data) {
        const response = await axios.post(API_URL, data);
        return response.data;
    }

    async update(id, data) {
        const response = await axios.put(`${API_URL}/${id}`, data);
        return response.data;
    }

    async delete(id) {
        await axios.delete(`${API_URL}/${id}`);
    }
}

export default new CommunityService();
