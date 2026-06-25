import axios, { type AxiosError } from 'axios';
import { useAuthStore } from '@/stores/useAuthStore.ts';
import router from '@/router';

const api = axios.create({
  baseURL: '/api',
  headers: { Accept: 'application/json' },
});

api.interceptors.request.use((config) => {
  const auth = useAuthStore();
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`;
  }
  return config;
});

api.interceptors.response.use(
  (response) => response,
  async (error: AxiosError) => {
    if (error.response?.status === 401) {
      useAuthStore().clearSession();
      router.push({ name: 'login' });
    }
    return Promise.reject(error);
  },
);

export default api;

