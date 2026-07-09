import api from '@/lib/axios';

export const profileApi = {
  async deleteAccount(): Promise<void> {
    await api.delete('/profile');
  },
};
