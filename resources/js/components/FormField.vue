<template>
  <div class="form-field">
    <label class="form-field__label">
      <span>{{ label }}</span>
    </label>
    <input v-model="value" :type="type ?? 'text'" @blur="handleBlur" :maxlength="maxLength" :placeholder="placeholder" />
    <span v-if="errorMessage" class="form-field__error">{{ errorMessage }}</span>
  </div>
</template>

<script setup lang="ts">
import { useField } from 'vee-validate';
import type {PropType} from "vue";

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  type: {
    type: String,
  },
  modelValue: {
    type: [String, null] as PropType<string | null>,
    default: null,
  },
  maxLength: {
    type: Number,
  },
  placeholder: {
    type: String,
  },
});

const { value, errorMessage, handleBlur } = useField<string>(
  () => props.name,
  undefined,
  { initialValue: props.modelValue ?? '' },
);
</script>
