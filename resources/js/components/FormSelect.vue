<template>
  <label class="form-field">
    <span class="form-field__label">{{ label }}</span>
    <select v-model="value" class="form-field__select" @blur="handleBlur">
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
    <span v-if="errorMessage" class="form-field__error">{{ errorMessage }}</span>
  </label>
</template>

<script setup lang="ts">
import { type PropType } from 'vue';
import { useField } from 'vee-validate';

interface Option {
  value: string;
  label: string;
}

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  options: {
    type: Array as PropType<Option[]>,
    required: true,
  },
  modelValue: {
    type: String,
    default: ''
  },
});

const { value, errorMessage, handleBlur } = useField<string>(
  () => props.name,
  undefined,
  { initialValue: props.modelValue },
);
</script>
