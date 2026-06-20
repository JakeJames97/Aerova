<template>
  <TransitionGroup v-if="notification" name="notification">
    <div
      class="notification"
      :class="`notification--${notification.type}`"
      role="status"
    >
        <span class="notification__icon">
          <CheckIcon v-if="notification.type === 'success'"/>
          <AlertIcon v-else/>
        </span>
      <span class="notification__message">{{ notification.message }}</span>
      <button
        type="button"
        class="notification__close"
        aria-label="Dismiss"
        @click="dismiss()"
      >
        <CloseIcon class="notification__close-icon"/>
      </button>
    </div>
  </TransitionGroup>
</template>

<script setup lang="ts">
import {storeToRefs} from 'pinia';
import {useNotificationStore} from '@/stores/useNotificationStore.ts';
import CheckIcon from '@/icons/check.svg?component';
import AlertIcon from '@/icons/alert.svg?component';
import CloseIcon from '@/icons/close.svg?component';

const store = useNotificationStore();
const {notification} = storeToRefs(store);
const {dismiss} = store;
</script>
