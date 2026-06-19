import api from '@/lib/axios';
import type {Trip, TripPayload} from '@/types/trips.ts';

export async function getTrips() {
  const res = await api.get<{ data: Trip[]; }>('/trips');
  return res.data.data;
}

export async function getTrip(id: string) {
  const res = await api.get<{ data: Trip; }>(`/trips/${id}`);
  return res.data.data;
}

export async function updateTrip(id: string, payload: TripPayload) {
  const res = await api.put<{ data: Trip }>(`/trips/${id}`, payload);
  return res.data.data;
}

export function deleteTrip(id: string) {
  return api.delete(`/trips/${id}`);
}
