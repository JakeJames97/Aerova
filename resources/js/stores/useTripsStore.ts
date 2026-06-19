import { ref } from 'vue';
import { defineStore } from 'pinia';
import type { Trip } from '@/types/trips.ts';

export const useTripsStore = defineStore('trips', () => {
  const trips = ref<Trip[]>([]);

  function setTrips(value: Trip[]) {
    trips.value = value;
  }

  function addTrip(value: Trip) {
    trips.value = [value, ...trips.value];
  }

  return { trips, setTrips, addTrip };
});
