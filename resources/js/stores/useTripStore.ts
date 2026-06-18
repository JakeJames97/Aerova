import { ref } from 'vue';
import { defineStore } from 'pinia';
import * as tripsApi from '@/api/trips';
import type { Trip } from '@/types/trips.ts';

export const useTripStore = defineStore('trip', () => {
  const trip = ref<Trip | null>(null);
  const loading = ref(false);
  const error = ref<string | null>(null);

  async function fetchTrip(id: string) {
    loading.value = true;
    error.value = null;
    trip.value = null;
    try {
      trip.value = await tripsApi.getTrip(id);
    } catch (e) {
      const err = e as { response?: { status?: number } };
      error.value = err.response?.status === 404 ? 'This trip doesn’t exist or has been deleted.' : 'Could not load this trip.';
    } finally {
      loading.value = false;
    }
  }

  return { trip, loading, error, fetchTrip };
});
