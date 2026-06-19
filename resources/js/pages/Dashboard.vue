<template>
  <div class="dashboard">
    <TripFilters
      v-if="!loading && !error && tripsStore.trips.length"
      v-model="activeFilter"
    />

    <p v-if="loading">Loading…</p>
    <p v-else-if="error" class="dashboard__error">{{ error }}</p>
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
import * as tripsApi from '@/api/trips';
import type { TripStatus } from '@/types/trips.ts';
import {useApiRequest} from "@/composables/useApiRequest.ts";

const tripsStore = useTripsStore();
const { loading, error, execute } = useApiRequest();

const activeFilter = ref<TripStatus | 'all'>('all');

const visibleTrips = computed(() =>
  activeFilter.value === 'all'
    ? tripsStore.trips
    : tripsStore.trips.filter((trip) => trip.status === activeFilter.value),
);

async function loadTrips() {
  const result = await execute(() => tripsApi.getTrips());
  if (result) {
    tripsStore.setTrips(result);
  }
}

onMounted(() => {
  loadTrips();
});
</script>
