import api from '@/lib/axios';
import type { Trip } from '@/types/trips.ts';

export function getTrips() {
  return api.get<{ data: Trip[] }>('/trips').then((res) => res.data.data);
}
