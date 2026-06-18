<template>
  <label class="form-field">
    <span class="form-field__label">{{ label }}</span>
    <input v-model="value" :type="type ?? 'text'" @blur="handleBlur" />
    <span v-if="errorMessage" class="form-field__error">{{ errorMessage }}</span>
  </label>
</template>

<script setup lang="ts">
import { useField } from 'vee-validate';

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
});

const { value, errorMessage, handleBlur } = useField<string>(() => props.name);
</script>
