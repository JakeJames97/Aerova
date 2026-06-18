import api from '@/lib/axios';
import type { Trip } from '@/types/trips.ts';

export async function getTrips() {
  const res = await api.get<{ data: Trip[]; }>('/trips');
  return res.data.data;
}

export async function getTrip(id: string) {
  const res = await api.get<{ data: Trip; }>(`/trips/${id}`);
  return res.data.data;
}
