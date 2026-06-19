import { ref } from 'vue';
import { defineStore } from 'pinia';
import type { Trip } from '@/types/trips.ts';

export const useTripStore = defineStore('trip', () => {
  const trip = ref<Trip | null>(null);

  function setTrip(value: Trip | null) {
    trip.value = value;
  }

  return { trip, setTrip };
});
