<template>
  <div class="trip-detail">
    <button type="button" class="trip-detail__back" @click="router.push({ name: 'dashboard' })">
      <ChevronLeftComponent class="trip-detail__back-icon" />
      Back to trips
    </button>

    <p v-if="loading">Loading…</p>

    <p v-else-if="error" class="trip-detail__empty">
      {{ error }}
    </p>

    <template v-else-if="trip">
      <header class="trip-detail__header">
        <div>
          <h1 class="trip-detail__name">{{ trip.name }}</h1>
          <p class="trip-detail__dates">{{ formatDateRange(trip.start_date, trip.end_date) }}</p>
        </div>
        <StatusPill :status="trip.status" />
      </header>

      <p v-if="trip.description" class="trip-detail__description">{{ trip.description }}</p>

      <section class="trip-detail__itinerary">
        <h2 class="trip-detail__section-title">Itinerary</h2>

        <p v-if="!trip.destinations?.length" class="trip-detail__empty">
          No destinations yet.
        </p>

        <ol v-else class="destination-list">
          <li v-for="destination in trip.destinations" :key="destination.id" class="destination">
            <div class="destination__head">
              <h3 class="destination__name">{{ destination.name }}</h3>
              <span v-if="destination.arrival_date" class="destination__dates">
                {{ formatDateRange(destination.arrival_date, destination.departure_date ?? destination.arrival_date) }}
              </span>
            </div>

            <ul v-if="destination.tasks.length" class="task-list">
              <li
                v-for="task in destination.tasks"
                :key="task.id"
                class="task"
                :class="{ 'task--done': task.is_completed }"
              >
                <span class="task__check">{{ task.is_completed ? '✓' : '○' }}</span>
                {{ task.title }}
              </li>
            </ul>
          </li>
        </ol>
      </section>
    </template>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useTripStore } from '@/stores/useTripStore.ts';
import { formatDateRange } from '@/lib/date';
import {storeToRefs} from "pinia";
import StatusPill from "@/components/StatusPill.vue";
import ChevronLeftComponent from '@/icons/chevron-left.svg?component';

const route = useRoute();
const router = useRouter();
const tripStore = useTripStore();

const { trip, loading, error } = storeToRefs(tripStore);

onMounted(() => {
  tripStore.fetchTrip(route.params.id as string);
});
</script>
