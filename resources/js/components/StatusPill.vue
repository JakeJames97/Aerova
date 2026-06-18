<template>
  <span class="status-pill" :class="`status-pill--${status}`">
    {{ labels[status] }}
  </span>
</template>

<script setup lang="ts">
import { type PropType } from 'vue';
import type { TripStatus } from '@/types/trips.ts';

defineProps({
  status: {
    type: String as PropType<TripStatus>,
    required: true,
  },
});

const labels: Record<TripStatus, string> = {
  planned: 'Planned',
  in_progress: 'In progress',
  completed: 'Completed',
};
</script>
