

import axios from 'axios';
import StorageService from '../services/storageService';

const axiosClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL
});

// Add a request interceptor to include the token in headers
axiosClient.interceptors.request.use((config) => {
  const token = StorageService.getToken();
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
    config.headers['Content-Type'] = 'application/json';
  }
  return config;
});

// Add a response interceptor to handle 401 errors
axiosClient.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    const { response } = error;
    
    if (response && response.status === 401) {
      StorageService.clearAuthData();
    }

    throw error;
  }
);

export default axiosClient;