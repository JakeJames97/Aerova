import api from '@/lib/axios';
import type { Task } from '@/types/tasks';

export async function createTask(destinationId: string, title: string) {
  const res = await api.post<{ data: Task }>(`/destinations/${destinationId}/tasks`, { title });
  return res.data.data;
}

export async function toggleTask(id: string) {
  const res = await api.patch<{ data: Task }>(`/tasks/${id}/toggle`);
  return res.data.data;
}

export function deleteTask(id: string) {
  return api.delete(`/tasks/${id}`);
}
