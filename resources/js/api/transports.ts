import api from '@/lib/axios';
import type {Transport, TransportPayload} from "@/types/transports.ts";

export async function createTransport(destinationId: string, payload: TransportPayload) {
  const res = await api.post<{ data: Transport }>(`/destinations/${destinationId}/transports`, payload);
  return res.data.data;
}

export async function updateTransport(id: string, payload: TransportPayload) {
  const res = await api.put<{ data: Transport }>(`/transports/${id}`, payload);
  return res.data.data;
}

export function deleteTransport(id: string) {
  return api.delete(`/transports/${id}`);
}
