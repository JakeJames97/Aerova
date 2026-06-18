<template>
  <div class="trip-filters">
    <button
      v-for="filter in filters"
      :key="filter.value"
      type="button"
      class="trip-filters__filter"
      :class="{ 'trip-filters__filter--active': modelValue === filter.value }"
      @click="$emit('update:modelValue', filter.value)"
    >
      {{ filter.label }}
    </button>
  </div>
</template>

<script setup lang="ts">
import type { TripStatus } from '@/types/trips.ts';
import type {PropType} from "vue";

type Filter = TripStatus | 'all';

defineProps({
  modelValue: {
    type: String as PropType<Filter>,
    required: true,
  },
});

defineEmits({
  'update:modelValue': (value: Filter) => true,
});

const filters: { value: Filter; label: string }[] = [
  { value: 'all', label: 'All' },
  { value: 'planned', label: 'Planned' },
  { value: 'in_progress', label: 'In progress' },
  { value: 'completed', label: 'Completed' },
];
</script>
