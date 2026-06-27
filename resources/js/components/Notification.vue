<template>
  <TransitionGroup v-if="notification" name="notification">
    <div
      class="notification"
      :class="`notification--${notification.type}`"
      role="status"
    >
        <span class="notification__icon">
          <CheckCircleIcon v-if="notification.type === 'success'"/>
          <BellAlertIcon v-else/>
        </span>
      <span class="notification__message">{{ notification.message }}</span>
      <button
        type="button"
        class="notification__close"
        aria-label="Dismiss"
        @click="dismiss()"
      >
        <XMarkIcon class="notification__close-icon"/>
      </button>
    </div>
  </TransitionGroup>
</template>

<script setup lang="ts">
import {storeToRefs} from 'pinia';
import {useNotificationStore} from '@/stores/useNotificationStore.ts';
import { CheckCircleIcon, BellAlertIcon, XMarkIcon } from '@heroicons/vue/24/solid'

const store = useNotificationStore();
const {notification} = storeToRefs(store);
const {dismiss} = store;
</script>
