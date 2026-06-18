<template>
  <div
    class="trip-card"
    :class="`trip-card--${trip.status}`"
  >
    <div class="trip-card__body">
      <div class="trip-card__top">
        <h3 class="trip-card__name">{{ trip.name }}</h3>
        <span class="trip-card__status" :class="`trip-card__status--${trip.status}`">
          {{ statusLabels[trip.status] }}
        </span>
      </div>

      <p class="trip-card__dates">{{ formatDateRange(trip.start_date, trip.end_date) }}</p>

      <p class="trip-card__meta">
        {{ trip.destinations_count }}
        {{ trip.destinations_count === 1 ? 'destination' : 'destinations' }}
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { formatDateRange } from '@/lib/date';
import type { Trip } from '@/types/trips.ts';
import type {PropType} from "vue";

defineProps({
  trip: {
    type: Object as PropType<Trip>,
    required: true,
  },
});

const statusLabels: Record<Trip['status'], string> = {
  planned: 'Planned',
  in_progress: 'In progress',
  completed: 'Completed',
};
</script>
