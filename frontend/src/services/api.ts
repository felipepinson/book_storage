const BASE_URL = 'http://172.10.1.10/v1';

const api = {
    async request(endpoint, method = 'GET', body = null, headers = {}) {
        const config = {
            method,
            headers: {
                'Content-Type': 'application/json',
                ...headers,
            },
            credentials: 'include', // Garante que cookies e sessões sejam enviados
        };

        if (body) {
            config.body = JSON.stringify(body);
        }

        const response = await fetch(`${BASE_URL}${endpoint}`, config);

        if (!response.ok) {
            const errorMessage = await response.json() || {};
            throw new Error(`Erro: ${errorMessage.error || 'Erro desconhecido'}`);
        }

        return response.json() || {};
    },

    get(endpoint, headers = {}) {
        return this.request(endpoint, 'GET', null, headers);
    },

    post(endpoint, body, headers = {}) {
        return this.request(endpoint, 'POST', body, headers);
    },

    put(endpoint, body, headers = {}) {
        return this.request(endpoint, 'PUT', body, headers);
    },

    delete(endpoint, headers = {}) {
        return fetch(`${BASE_URL}${endpoint}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                ...headers,
            },
            credentials: 'include',
        }).then(response => {
            if (response.status === 204) return null;
            return response.json();
        });
    }
};

export default api;
export { BASE_URL };
