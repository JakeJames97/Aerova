import api from '@/lib/axios';
import type { LoginCredentials, RegisterCredentials, AuthResponse, User } from '@/types/auth.ts';

export async function login(credentials: LoginCredentials): Promise<AuthResponse> {
  const res = await api.post<AuthResponse>('/login', credentials);
  return res.data;
}

export async function register(payload: RegisterCredentials): Promise<AuthResponse> {
  const res = await api.post<AuthResponse>('/register', payload);
  return res.data;
}

export async function fetchUser(): Promise<User> {
  const res = await api.get<{ data: User }>('/user');
  return res.data.data;
}

export function logout() {
  return api.post('/logout');
}
