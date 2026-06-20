import { ref } from 'vue';
import { defineStore } from 'pinia';

export type NotificationType = 'success' | 'error';

export interface Notification {
  type: NotificationType;
  message: string;
}

export const useNotificationStore = defineStore('notifications', () => {
  const notification = ref<Notification | null>(null);

  let timeoutId: ReturnType<typeof setTimeout> | null = null;

  function dismiss() {
    notification.value = null;

    if (timeoutId) {
      clearTimeout(timeoutId);
      timeoutId = null;
    }
  }

  function add(type: NotificationType, message: string, duration = 5000) {
    if (timeoutId) {
      clearTimeout(timeoutId);
    }

    notification.value = { type, message };

    timeoutId = setTimeout(() => {
      dismiss();
    }, duration);
  }

  const success = (message: string, duration?: number) =>
    add('success', message, duration);

  const error = (message: string, duration?: number) =>
    add('error', message, duration);

  return {
    notification,
    success,
    error,
    dismiss,
  };
});
