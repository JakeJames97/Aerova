<template>
  <Modal :open="open" :title="title" @update:open="emit('update:open', $event)">
    <p class="confirm-dialog__message">{{ message }}</p>
    <div class="confirm-dialog__actions">
      <BaseButton variant="outline" :disabled="loading" @click="emit('update:open', false)">
        Cancel
      </BaseButton>
      <BaseButton variant="danger" :disabled="loading" @click="emit('confirm')">
        {{ loading ? 'Deleting…' : confirmLabel }}
      </BaseButton>
    </div>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/Modal.vue';
import BaseButton from '@/components/BaseButton.vue';

defineProps({
  open: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'Are you sure?'
  },
  message: {
    type: String,
    default: ''
  },
  confirmLabel: {
    type: String,
    default: 'Confirm'
  },
  loading: {
    type: Boolean,
    default: false
  },
});

const emit = defineEmits({
  'update:open': (value: boolean) => typeof value === 'boolean',
  confirm: () => true,
});
</script>
