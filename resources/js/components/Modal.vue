<template>
  <dialog ref="dialog" class="modal" @close="close" @cancel.prevent="close">
    <div class="modal__panel" ref="modal">
      <header v-if="title" class="modal__header">
        <h2 class="modal__title">{{ title }}</h2>
        <button type="button" class="modal__close" aria-label="Close" @click="close">
          <XMarkIcon class="modal__close-icon" />
        </button>
      </header>

      <div class="modal__body">
        <slot />
      </div>
    </div>
  </dialog>
</template>

<script setup lang="ts">
import {useTemplateRef, watch} from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/solid'
import {onClickOutside} from "@vueuse/core";

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['update:open']);

const dialog = useTemplateRef<HTMLDialogElement>('dialog');
const modal = useTemplateRef<HTMLDialogElement>('modal');

onClickOutside(modal, event => dialog.value?.close());

watch(
  () => props.open,
  (isOpen) => {
    const element = dialog.value;
    if (!element) {
      return;
    }
    if (isOpen && !element.open) {
      element.showModal();
    } else if (!isOpen && element.open) {
      element.close();
    }
  },
);

function close() {
  emit('update:open', false);
}
</script>
