import api from '@/lib/axios';
import type {Country} from '@/types/countries.ts';

export async function getCountries() {
  const res = await api.get<{ data: Country[]; }>('/countries');
  return res.data.data;
}
