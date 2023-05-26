import axios from 'axios';
import jwtDecode from 'jwt-decode';
import {useNavigate} from "react-router-dom";

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    timeout: 1000,
});

const refreshTokenIfNeeded = (token) => {
    return new Promise(async (resolve) => {
        const decodedToken = jwtDecode(token);
        const currentTime = Date.now() / 1000;
        const bufferTime = 60; // Refresh the token if it's going to expire within the next 60 seconds

        if (decodedToken.exp - currentTime < bufferTime) {
            try {
                const { data } = await api.post('/refresh-token', {}, { headers: { Authorization: `Bearer ${token}` } });
                const newAccessToken = data.access_token;
                localStorage.setItem('access_token', newAccessToken);
                resolve(newAccessToken);
            } catch (error) {
                localStorage.removeItem('access_token');
                const navigate = useNavigate();
                navigate("/", { replace: true });
            }
        } else {
            resolve(token);
        }
    });
};


async function handleRequestWithTokenRefresh(requestConfig) {
    let token = localStorage.getItem('access_token');
    if (token) {
        token = await refreshTokenIfNeeded(token);
        requestConfig.headers = requestConfig.headers || {};
        requestConfig.headers.Authorization = `Bearer ${token}`;
    }

    try {
        return await api(requestConfig);
    } catch (error) {
        if (error.response && error.response.status === 401) {
            localStorage.removeItem('access_token');
        }
        throw error;
    }
}
const apiWrapper = {
    post: (url, data, config) => handleRequestWithTokenRefresh({ method: 'post', url, data, ...config }),
    get: (url, config) => handleRequestWithTokenRefresh({ method: 'get', url, ...config }),
    put: (url, data, config) => handleRequestWithTokenRefresh({ method: 'put', url, data, ...config }),
    delete: (url, config) => handleRequestWithTokenRefresh({ method: 'delete', url, ...config }),
};

export default apiWrapper;
