import api from '@/lib/axios';
import type {MarkAsReadResponse, Notification} from "@/types/notifications.ts";

export async function getNotifications() {
  const res = await api.get<{ data: Notification[] }>('/notifications');
  return res.data.data;
}

export async function markAsRead(notificationId: string) {
  const res = await api.patch<MarkAsReadResponse>(`/notifications/${notificationId}/read`);
  return res.data.data;
}
