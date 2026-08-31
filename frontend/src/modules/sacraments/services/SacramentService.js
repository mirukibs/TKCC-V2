import api from '@/plugins/axios';

const API_URL = '/sacraments';

class SacramentService {
  async getAll(params = {}) {
    const response = await api.get(API_URL, { params });
    return response.data;
  }

  async getById(id) {
    const response = await api.get(`${API_URL}/${id}`);
    return response.data;
  }

  async getByMemberId(memberId) {
    const response = await api.get(`${API_URL}/member/${memberId}`);
    return response.data;
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
    const response = await api.delete(`${API_URL}/${id}`);
    return response.data;
  }
}

export default new SacramentService();
