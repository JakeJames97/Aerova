import { ref } from 'vue';
import { defineStore } from 'pinia';
import * as tripsApi from '@/api/trips';
import type { Trip } from '@/types/trips.ts';

export const useTripsStore = defineStore('trips', () => {
  const trips = ref<Trip[]>([]);
  const loading = ref<boolean>(false);
  const error = ref<string | null>(null);

  async function fetchTrips() {
    loading.value = true;
    error.value = null;
    try {
      trips.value = await tripsApi.getTrips();
    } catch {
      error.value = 'Could not load your trips.';
    } finally {
      loading.value = false;
    }
  }

  return { trips, loading, error, fetchTrips };
});
