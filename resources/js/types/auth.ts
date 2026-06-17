export interface User {
  id: number;
  username: string;
  email: string;
}

export interface LoginCredentials {
  login: string;
  password: string;
}

export interface RegisterCredentials {
  username: string;
  email: string;
  password: string;
}

export interface AuthResponse {
  user: User;
  token: string;
}
