import {ref} from "vue";
import type {Notification} from "@/types/notifications.ts";
import {defineStore} from "pinia";
import * as notificationApi from '@/api/notifications.ts';
import {dayjs} from "@/lib/date.ts";

export const useApiNotificationStore = defineStore('apiNotification', () => {
  const notifications = ref<Notification[]>([]);
  const unreadCount = ref(0);

  async function load() {
    notifications.value = await notificationApi.getNotifications();
    unreadCount.value = notifications.value.filter((n) => !n.read_at).length;
  }

  async function markRead(id: string) {
    const data = await notificationApi.markAsRead(id);

    const notification = notifications.value.find(
      notification => notification.id === id
    );

    if (notification) {
      notification.read_at = dayjs().toISOString();
    }

    unreadCount.value = data.unread_count;
  }

  return {notifications, unreadCount, load, markRead};
});
