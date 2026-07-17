<template>
  <div class="transport-section">
    <div class="transport-section__head">
      <h4 class="transport-section__title">
        <MapPinIcon class="transport-section__title-icon"/>
        Getting there
      </h4>
    </div>
    <div class="transport-list">
      <p v-if="!transports.length" class="transport-list__empty">
        No transport added yet.
      </p>

      <ul v-else class="transport-list__items">
        <TransportItem
          v-for="transport in transports"
          :key="transport.id"
          :transport="transport"
          :editable="editable"
          @edit="openEdit"
          @delete="confirmDelete"
        />
      </ul>

      <BaseButton v-if="editable" class="transport-add__trigger" variant="ghost" @click="openCreate">
        + Add transport
      </BaseButton>
    </div>
    <TransportForm
      v-if="editable"
      v-model:open="formOpen"
      :destination-id="destinationId"
      :transport="editing"
    />
    <ConfirmDialog
      v-if="editable"
      v-model:open="deleteOpen"
      title="Delete transport?"
      message="Remove this transport?"
      confirm-label="Delete"
      :loading="deletingLoading"
      @confirm="handleDelete"
    />
  </div>
</template>

<script setup lang="ts">
import {type PropType, ref} from 'vue';
import MapPinIcon from '@/icons/map-pin.svg?component';
import type {Transport} from '@/types/transports';
import TransportItem from '@/components/TransportItem.vue';
import ConfirmDialog from "@/components/modals/ConfirmDialog.vue";
import {useApiRequest} from "@/composables/useApiRequest.ts";
import * as transportApi from "@/api/transports";
import {useTripStore} from "@/stores/useTripStore.ts";
import {useNotificationStore} from "@/stores/useNotificationStore.ts";
import TransportForm from "@/components/modals/TransportForm.vue";
import BaseButton from "@/components/BaseButton.vue";

defineProps({
  destinationId: {
    type: String,
    required: true,
  },
  transports: {
    type: Array as PropType<Transport[]>,
    default: () => [],
  },
  editable: {
    type: Boolean,
    required: true,
  },
});

const tripStore = useTripStore();
const notify = useNotificationStore();

const { loading: deletingLoading, execute: executeDelete } = useApiRequest();

const formOpen = ref(false);
const editing = ref<Transport | null>(null);
const deleteOpen = ref(false);
const transportToDelete = ref<Transport | null>(null);

function openEdit(transport: Transport) {
  editing.value = transport;
  formOpen.value = true;
}
function openCreate() {
  editing.value = null;
  formOpen.value = true;
}

function confirmDelete(transport: Transport) {
  transportToDelete.value = transport;
  deleteOpen.value = true;
}

async function handleDelete() {
  if (!transportToDelete.value) {
    return;
  }
  const result = await executeDelete(() => transportApi.deleteTransport(transportToDelete.value!.id));
  if (result !== undefined) {
    deleteOpen.value = false;
    await tripStore.reload();
    notify.success('Transport has successfully been deleted');
  }
}
</script>
