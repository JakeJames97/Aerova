<template>
  <li class="destination">
    <div class="destination__head">
      <div class="destination__head-left">
        <h3 class="destination__name">{{ destination.name }}</h3>
        <span v-if="destination.arrival_date" class="destination__dates">
          {{ formatDateRange(destination.arrival_date, destination.departure_date ?? destination.arrival_date) }}
        </span>
      </div>
      <div class="destination__actions">
        <button type="button" class="icon-button" aria-label="Edit destination" @click="emit('edit', destination)">
          <PencilIcon class="icon-button__icon" />
        </button>
        <button type="button" class="icon-button" aria-label="Delete destination" @click="emit('delete', destination)">
          <TrashIcon class="icon-button__icon" />
        </button>
      </div>
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
</template>

<script setup lang="ts">
import { type PropType } from 'vue';
import { formatDateRange } from '@/lib/date';
import PencilIcon from '@/icons/pencil.svg?component';
import TrashIcon from '@/icons/trash.svg?component';
import type { Destination } from '@/types/destinations';

defineProps({
  destination: {
    type: Object as PropType<Destination>,
    required: true
  },
});

const emit = defineEmits({
  edit: (destination: Destination) => !!destination,
  delete: (destination: Destination) => !!destination,
});
</script>
