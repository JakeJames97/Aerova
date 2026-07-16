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
        />
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
import {type PropType} from 'vue';
import MapPinIcon from '@/icons/map-pin.svg?component';
import type {Transport} from '@/types/transports';
import TransportItem from '@/components/TransportItem.vue';

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
</script>
