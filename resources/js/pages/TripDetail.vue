<template>
  <div class="trip-detail">
    <button type="button" class="trip-detail__back" @click="router.push({ name: 'dashboard' })">
      <ChevronLeftComponent class="trip-detail__back-icon" />
      Back to trips
    </button>

    <p v-if="loading">Loading…</p>

    <p v-else-if="error" class="empty-state">
      {{ error }}
    </p>

    <template v-else-if="trip">
      <header class="trip-detail__header">
        <div>
          <div class="trip-detail__title">
            <h1 class="trip-detail__name">{{ trip.name }}</h1>
            <StatusPill :status="trip.status" />
          </div>
          <p class="trip-detail__dates">{{ formatDateRange(trip.start_date, trip.end_date) }}</p>
        </div>
        <div class="trip-detail__actions">
          <BaseButton variant="primary" @click="editOpen = true">Edit</BaseButton>
          <BaseButton variant="danger" @click="confirmOpen = true">Delete</BaseButton>
        </div>
      </header>

      <p v-if="trip.description" class="trip-detail__description">{{ trip.description }}</p>

      <DestinationList
        :trip-id="trip.id"
        :destinations="trip.destinations ?? []"
      />

      <ConfirmDialog
        v-model:open="confirmOpen"
        title="Delete trip?"
        message="Deleting this trip will also remove its destinations and tasks. This can’t be undone."
        confirm-label="Delete trip"
        :loading="deleting"
        @confirm="handleDelete"
      />

      <TripForm v-model:open="editOpen" :trip="trip" @saved="onSaved" :key="trip.id" />
    </template>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useTripStore } from '@/stores/useTripStore.ts';
import { useApiRequest } from '@/composables/useApiRequest';
import * as tripsApi from '@/api/trips';
import { formatDateRange } from '@/lib/date';
import ChevronLeftComponent from '@/icons/chevron-left.svg?component';
import StatusPill from '@/components/StatusPill.vue';
import BaseButton from '@/components/BaseButton.vue';
import ConfirmDialog from '@/components/modals/ConfirmDialog.vue';
import TripForm from '@/components/modals/TripForm.vue';
import DestinationList from '@/components/DestinationList.vue';
import type { Trip } from '@/types/trips.ts';

const route = useRoute();
const router = useRouter();
const tripStore = useTripStore();
const { trip } = storeToRefs(tripStore);

const { loading, error, execute: executeLoad } = useApiRequest();
const { loading: deleting, execute: executeDelete } = useApiRequest();

const confirmOpen = ref(false);
const editOpen = ref(false);

const id = route.params.id as string;

async function loadTrip() {
  const result = await executeLoad(() => tripsApi.getTrip(id));
  if (result) {
    tripStore.setTrip(result);
  } else {
    error.value = 'This trip doesn’t exist or has been deleted.';
  }
}

async function handleDelete() {
  if (!trip.value) return;
  const result = await executeDelete(() => tripsApi.deleteTrip(trip.value!.id));
  if (result !== undefined) {
    router.push({ name: 'dashboard' });
  } else {
    confirmOpen.value = false;
  }
}

function onSaved(updated: Trip) {
  tripStore.setTrip(updated);
}

onMounted(loadTrip);
</script>
