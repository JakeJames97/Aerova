<template>
  <div class="task-section">
    <ul v-if="tasks.length" class="task-list">
      <li
        v-for="task in tasks"
        :key="task.id"
        class="task"
        :class="{ 'task--done': task.is_completed }"
      >
        <button
          type="button"
          class="task__toggle"
          :aria-label="task.is_completed ? 'Mark incomplete' : 'Mark complete'"
          @click="handleToggle(task)"
        >
          <CheckIcon v-if="task.is_completed" class="task__icon" />
          <CircleIcon v-else class="task__icon" />
        </button>
        <span class="task__title">{{ task.title }}</span>
        <button
          type="button"
          class="icon-button icon-button--sm"
          aria-label="Delete task"
          @click="handleDelete(task)"
        >
          <TrashIcon class="icon-button__icon" />
        </button>
      </li>
    </ul>

    <form v-if="adding" class="task-add" @submit.prevent="handleAdd">
      <input
        ref="inputEl"
        v-model="newTitle"
        type="text"
        class="task-add__input"
        placeholder="Add a task…"
        :disabled="submitting"
        @keydown.esc="closeAdd"
      />
    </form>

    <BaseButton v-else class="task-add__trigger" variant="ghost" @click="openAdd">+ Add task</BaseButton>
  </div>
</template>

<script setup lang="ts">
import { type PropType, nextTick, ref, useTemplateRef } from 'vue';
import { useApiRequest } from '@/composables/useApiRequest';
import * as tasksApi from '@/api/tasks';
import BaseButton from '@/components/BaseButton.vue';
import CheckIcon from '@/icons/check.svg?component';
import CircleIcon from '@/icons/circle.svg?component';
import TrashIcon from '@/icons/trash.svg?component';
import type { Task } from '@/types/tasks';
import {useTripStore} from "@/stores/useTripStore.ts";

const props = defineProps({
  destinationId: { type: String, required: true },
  tasks: { type: Array as PropType<Task[]>, required: true },
});

const tripStore = useTripStore();
const adding = ref(false);
const newTitle = ref('');
const inputEl = useTemplateRef<HTMLInputElement>('inputEl');

const { loading: submitting, execute: executeAdd } = useApiRequest();
const { execute: executeToggle } = useApiRequest();
const { execute: executeDelete } = useApiRequest();

async function openAdd() {
  adding.value = true;
  await nextTick();
  inputEl.value?.focus();
}

function closeAdd() {
  adding.value = false;
  newTitle.value = '';
}

async function handleAdd() {
  const title = newTitle.value.trim();
  if (!title) return;
  const result = await executeAdd(() => tasksApi.createTask(props.destinationId, title));
  if (result) {
    newTitle.value = '';
    await tripStore.reload();
    await nextTick();
  }
}

async function handleToggle(task: Task) {
  const result = await executeToggle(() => tasksApi.toggleTask(task.id));
  if (result) {
    await tripStore.reload();
  }
}

async function handleDelete(task: Task) {
  const result = await executeDelete(() => tasksApi.deleteTask(task.id));
  if (result !== undefined) {
    await tripStore.reload();
  }
}
</script>
