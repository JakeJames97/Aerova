<template>
  <li class="transport">
    <div class="transport__main">
      <component :is="icon" class="transport__icon" />

      <div class="transport__detail">
        <p class="transport__route">{{ route }}</p>
        <p v-if="meta" class="transport__meta">{{ meta }}</p>
      </div>
    </div>

    <div class="transport__right">
      <div class="transport__prices">
        <span class="transport__price">
          {{ transport.price_formatted }}
        </span>
        <span v-if="transport.current_price_formatted" class="transport__current">
          now {{ transport.current_price_formatted }}
          <span v-if="checkedAgo" class="transport__checked">· {{ checkedAgo }}</span>
        </span>
      </div>

      <div v-if="editable" class="transport__actions">
        <button type="button" class="icon-button" aria-label="Edit transport" @click="emit('edit', transport)">
          <PencilSquareIcon class="icon-button__icon" />
        </button>
        <button type="button" class="icon-button" aria-label="Delete transport" @click="emit('delete', transport)">
          <TrashIcon class="icon-button__icon" />
        </button>
      </div>
    </div>
  </li>
</template>

<script setup lang="ts">
import { computed, type PropType } from 'vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import PlaneIcon from '@/icons/plane.svg?component';
import TrainIcon from '@/icons/train.svg?component';
import CarIcon from '@/icons/car.svg?component';
import MapPinIcon from '@/icons/map-pin.svg?component';
import {dayjs} from '@/lib/date.ts';
import type { Transport } from '@/types/transports';

const props = defineProps({
  transport: {
    type: Object as PropType<Transport>,
    required: true,
  },
  editable: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits({
  edit: (transport: Transport) => !!transport,
  delete: (transport: Transport) => !!transport,
});

const icons = {
  flight: PlaneIcon,
  train: TrainIcon,
  car: CarIcon,
  other: MapPinIcon,
};

const icon = computed(() => icons[props.transport.type] ?? MapPinIcon);

const route = computed(() => {
  const { from, to, from_iata, to_iata, identifier } = props.transport;

  const origin = from_iata ?? from;
  const destination = to_iata ?? to;

  return identifier
    ? `${origin} → ${destination} · ${identifier}`
    : `${origin} → ${destination}`;
});

const meta = computed(() => {
  const { airline, departure_at, arrival_at } = props.transport;

  const time = `${dayjs(departure_at).format('D MMM, HH:mm')} – ${dayjs(arrival_at).format('HH:mm')}`;

  return airline ? `${airline} · ${time}` : time;
});

const checkedAgo = computed(() =>
  props.transport.price_checked_at
    ? dayjs(props.transport.price_checked_at).fromNow()
    : null,
);
</script>
