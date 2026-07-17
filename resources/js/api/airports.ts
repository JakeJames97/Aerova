import api from '@/lib/axios';
import type {Airport} from '@/types/airports.ts';

export async function searchAirports(search: string) {
  const res = await api.get<{ data: Airport[] }>('/airports', {
    params: { search },
  });

  return res.data.data;
}
