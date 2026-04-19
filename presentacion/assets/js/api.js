const api = {
    async request(url, options = {}) {
        options.headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...options.headers
        };

        try {
            const response = await fetch(url, options);
            if (response.status === 401) {
                // Forzar redirección limpia desde cliente por caducidad de Auth
                window.location.href = '/presentacion/login/?error=session_expired';
                return null;
            }
            if (!response.ok) {
                // Manejar error 400 y tratar de capturar el meta mensaje
                let errorData;
                try {
                    errorData = await response.json();
                } catch(e) {}
                
                throw new Error(errorData?.error || `HTTP Error: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('API Fetch Error:', error);
            throw error;
        }
    },
    
    get(url) {
        return this.request(url, { method: 'GET' });
    },
    
    post(url, data) {
        return this.request(url, { method: 'POST', body: JSON.stringify(data) });
    },
    
    put(url, data) {
        return this.request(url, { method: 'PUT', body: JSON.stringify(data) });
    },
    
    delete(url, data) {
        return this.request(url, { method: 'DELETE', body: JSON.stringify(data) });
    }
};
