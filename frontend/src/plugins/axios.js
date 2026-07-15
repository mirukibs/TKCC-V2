import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    withCredentials: true,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    }
});

// Setup CSRF interceptor for Sanctum
api.interceptors.request.use(async config => {
    if (['post', 'put', 'patch', 'delete'].includes(config.method)) {
        await axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
    }
    return config;
});

export default api;
