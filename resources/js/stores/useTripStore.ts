import { ref } from 'vue';
import { defineStore } from 'pinia';
import type { Trip } from '@/types/trips.ts';
import {getTrip} from "@/api/trips.ts";

export const useTripStore = defineStore('trip', () => {
  const trip = ref<Trip | null>(null);

  function setTrip(value: Trip | null) {
    trip.value = value;
  }

  async function reload() {
    if (!trip.value) {
      return;
    }
    const result = await getTrip(trip.value.id);
    if (result) {
      setTrip(result);
    }
  }

  return { trip, setTrip, reload };
});
