<template>
  <div class="dashboard">
    <TripFilters
      v-if="!tripsStore.loading && !tripsStore.error && tripsStore.trips.length"
      v-model="activeFilter"
    />

    <p v-if="tripsStore.loading">Loading…</p>
    <p v-else-if="tripsStore.error" class="dashboard__error">{{ tripsStore.error }}</p>
    <p v-else-if="tripsStore.trips.length === 0" class="dashboard__empty">
      No trips yet. Create your first one to get started.
    </p>
    <p v-else-if="visibleTrips.length === 0" class="dashboard__empty">
      No matching trips.
    </p>

    <div v-else class="dashboard__grid">
      <TripCard v-for="trip in visibleTrips" :key="trip.id" :trip="trip" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { useTripsStore } from '@/stores/useTripsStore.ts';
import TripCard from '@/components/TripCard.vue';
import TripFilters from '@/components/TripFilters.vue';
import type { TripStatus } from '@/types/trips.ts';

const tripsStore = useTripsStore();

const activeFilter = ref<TripStatus | 'all'>('all');

const visibleTrips = computed(() =>
  activeFilter.value === 'all'
    ? tripsStore.trips
    : tripsStore.trips.filter((trip) => trip.status === activeFilter.value),
);

onMounted(() => {
  tripsStore.fetchTrips();
});
</script>
