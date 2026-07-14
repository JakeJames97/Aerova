<template>
  <div class="trips">
    <div class="trips__controls" v-if="!error">
      <TripFilters />
      <BaseButton @click="goToTripCreation">Create trip</BaseButton>
    </div>

    <p v-if="error" class="trips__error">{{ error }}</p>
    <p v-else-if="!loading && tripsStore.trips.length === 0" class="trips__empty">
      No trips yet. Create your first one to get started.
    </p>

    <div v-else class="trips__grid">
      <TripCardSkeleton v-if="loading" v-for="index in 10" :key="index" />
      <TripCard v-else v-for="trip in tripsStore.trips" :key="trip.id" :trip="trip"/>
    </div>
  </div>

  <Pagination
    v-if="tripsStore.paginationMeta"
    :last-page="tripsStore.paginationMeta.last_page"
    :current-page="tripsStore.paginationMeta.current_page"
  />
</template>

<script setup lang="ts">
import {onMounted, ref, watch} from 'vue';
import {useTripsStore} from '@/stores/useTripsStore.ts';
import TripCard from '@/components/TripCard.vue';
import TripFilters from '@/components/TripFilters.vue';
import * as tripsApi from '@/api/trips';
import {useApiRequest} from "@/composables/useApiRequest.ts";
import BaseButton from "@/components/BaseButton.vue";
import Pagination from "@/components/Pagination.vue";
import {useRoute, useRouter} from "vue-router";
import TripCardSkeleton from "@/components/placeholders/TripCardSkeleton.vue";

const route = useRoute();
const router = useRouter();
const tripsStore = useTripsStore();
const {loading, error, execute} = useApiRequest();

async function loadTrips(page: number, status?: string) {
  tripsStore.setTrips([]);
  const result = await execute(() => tripsApi.getTrips(page, status));
  if (result) {
    tripsStore.setTrips(result.data);
    tripsStore.setPaginationMeta(result.meta);
  }
}

function goToTripCreation() {
  router.push({ name: 'trip-create' });
}

onMounted(() => {
  const page = Number(route.query.page) || 1;
  const status = (route.query.status as string) || undefined;
  loadTrips(page, status);
});

watch(
  () => route.query,
  () => {
    const page = Number(route.query.page) || 1;
    const status = (route.query.status as string) || undefined;
    loadTrips(page, status);
  },
);
</script>
