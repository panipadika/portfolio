import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
    },
    withCredentials: true,
});

/**
 * Fetch complete portfolio dataset (stats, education, experience, projects, certificates, achievements, tech stack).
 */
export async function getPortfolio() {
    const response = await api.get('/portfolio');
    return response.data;
}

/**
 * Send contact message with rate limiting protection.
 * @param {{ name: string, email: string, message: string }} payload
 */
export async function submitContact(payload) {
    const response = await api.post('/contact', payload);
    return response.data;
}

export default api;
