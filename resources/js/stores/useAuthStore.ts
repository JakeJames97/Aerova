import { computed, ref } from 'vue';
import { defineStore } from 'pinia';
import * as authApi from '@/api/auth';
import type { User, LoginCredentials, RegisterCredentials } from '@/types/auth.ts';

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null);
  const token = ref<string | null>(sessionStorage.getItem('auth_token'));

  const isAuthenticated = computed(() => !!token.value);

  function setSession(newToken: string, newUser: User) {
    token.value = newToken;
    user.value = newUser;
    sessionStorage.setItem('auth_token', newToken);
  }

  function clearSession() {
    token.value = null;
    user.value = null;
    sessionStorage.removeItem('auth_token');
  }

  async function login(credentials: LoginCredentials) {
    const data = await authApi.login(credentials);
    setSession(data.token, data.user);
  }

  async function register(payload: RegisterCredentials) {
    const data = await authApi.register(payload);
    setSession(data.token, data.user);
  }

  async function logout() {
    await authApi.logout().catch(() => {});
    clearSession();
  }

  return { user, token, isAuthenticated, login, register, logout };
});
