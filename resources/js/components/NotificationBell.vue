<template>
  <div class="notification-bell" ref="rootEl">
    <button
      type="button"
      class="notification-bell__trigger"
      @click="toggle"
    >
      <BellIcon class="notification-bell__icon" />
      <span v-if="store.unreadCount" class="notification-bell__badge">
        {{ store.unreadCount > 9 ? '9+' : store.unreadCount }}
      </span>
    </button>

    <div v-if="open" class="notification-bell__panel" role="menu">
      <div class="notification-bell__head">
        <span class="notification-bell__title">Notifications</span>
      </div>

      <p v-if="!store.notifications.length" class="notification-bell__empty">
        You're all caught up.
      </p>

      <ul v-else class="notification-bell__list">
        <li
          v-for="notification in store.notifications"
          :key="notification.id"
          class="notification-item"
          :class="{ 'notification-item--unread': !notification.read_at }"
          @click="onSelect(notification)"
        >
          <span v-if="!notification.read_at" class="notification-item__dot" />
          <div class="notification-item__body">
            <p class="notification-item__message">{{ notification.data.message }}</p>
            <time class="notification-item__time">{{ formatRelativeTime(notification.created_at) }}</time>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import { useApiNotificationStore } from '@/stores/useApiNotificationStore.ts';
import {formatRelativeTime} from "@/lib/date.ts";
import type { Notification } from '@/types/notifications';
import { BellIcon } from '@heroicons/vue/24/outline'

const store = useApiNotificationStore();
const router = useRouter();

const open = ref(false);
const rootEl = ref<HTMLElement | null>(null);

function toggle() {
  open.value = !open.value;
}

async function onSelect(notification: Notification) {
  await store.markRead(notification.id);
  if (notification.data.trip_id) {
    router.push({ name: 'trip', params: { id: notification.data.trip_id } });
  }
  open.value = false;
}

function onClickOutside(event: MouseEvent) {
  if (rootEl.value && !rootEl.value.contains(event.target as Node)) {
    open.value = false;
  }
}

onMounted(() => {
  store.load();
  document.addEventListener('click', onClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', onClickOutside);
});
</script>
