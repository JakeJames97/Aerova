import api from '@/lib/axios';
import type {Destination, DestinationPayload} from "@/types/destinations.ts";

export async function updateDestination(id: string, payload: DestinationPayload) {
  const res = await api.put<{ data: Destination }>(`/destinations/${id}`, payload);
  return res.data.data;
}

export async function createDestination(tripId: string, payload: DestinationPayload) {
  const res = await api.post<{ data: Destination }>(`/trips/${tripId}/destinations`, payload);
  return res.data.data;
}

export function deleteDestination(id: string) {
  return api.delete(`/destinations/${id}`);
}
