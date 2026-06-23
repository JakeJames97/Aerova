<template>
  <section class="itinerary">
    <div class="itinerary__head">
      <h2 class="itinerary__title">Itinerary</h2>
      <BaseButton v-if="editable" @click="openCreate">Add destination</BaseButton>
    </div>

    <p v-if="!destinations.length" class="empty-state">
      No destinations yet.
    </p>

    <ul v-else class="destination-list">
      <DestinationCard
        v-for="destination in destinations"
        :key="destination.id"
        :destination="destination"
        :editable="editable"
        @edit="openEdit"
        @delete="confirmDelete"
      />
    </ul>

    <DestinationForm
      v-if="editable"
      v-model:open="formOpen"
      :trip-id="tripId"
      :destination="destinationToEdit"
      @saved="tripStore.reload"
    />

    <ConfirmDialog
      v-if="editable"
      v-model:open="deleteOpen"
      title="Delete destination?"
      message="Remove this destination and its tasks from this trip?"
      confirm-label="Delete"
      :loading="deletingLoading"
      @confirm="handleDelete"
    />
  </section>
</template>

<script setup lang="ts">
import { type PropType, ref } from 'vue';
import { useApiRequest } from '@/composables/useApiRequest';
import * as destinationsApi from '@/api/destinations';
import BaseButton from '@/components/BaseButton.vue';
import DestinationCard from '@/components/DestinationCard.vue';
import DestinationForm from '@/components/modals/DestinationForm.vue';
import ConfirmDialog from '@/components/modals/ConfirmDialog.vue';
import type { Destination } from '@/types/destinations';
import {useTripStore} from "@/stores/useTripStore.ts";
import { useNotificationStore } from '@/stores/useNotificationStore.ts';

defineProps({
  tripId: {
    type: String,
    required: true,
  },
  destinations: {
    type: Array as PropType<Destination[]>,
    required: true,
  },
  editable: {
    type: Boolean,
    required: true,
  }
});

const tripStore = useTripStore();
const notify = useNotificationStore();

const formOpen = ref(false);
const deleteOpen = ref(false);
const destinationToEdit = ref<Destination | null>(null);
const destinationToDelete = ref<Destination | null>(null);

const { loading: deletingLoading, execute: executeDelete } = useApiRequest();

function openCreate() {
  destinationToEdit.value = null;
  formOpen.value = true;
}

function openEdit(destination: Destination) {
  destinationToEdit.value = destination;
  formOpen.value = true;
}

function confirmDelete(destination: Destination) {
  destinationToDelete.value = destination;
  deleteOpen.value = true;
}

async function handleDelete() {
  if (!destinationToDelete.value) {
    return;
  }
  const result = await executeDelete(() => destinationsApi.deleteDestination(destinationToDelete.value!.id));
  if (result !== undefined) {
    deleteOpen.value = false;
    await tripStore.reload();
    notify.success('Destination has successfully been deleted');
  }
}
</script>
