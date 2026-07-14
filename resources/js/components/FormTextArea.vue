<template>
  <div class="form-field">
    <label v-if="label" :for="name" class="form-field__label">{{ label }}</label>
    <textarea
      :id="name"
      v-model="value"
      :placeholder="placeholder"
      class="form-field__textarea"
      :class="{ 'form-field__textarea--error': errorMessage }"
      @blur="handleBlur"
    />
    <span v-if="errorMessage" class="form-field__error">{{ errorMessage }}</span>
  </div>
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
    default: '',
  },
  placeholder: {
    type: String,
    default: '',
  },
  modelValue: {
    type: String,
    default: undefined,
  },
});

const { value, errorMessage, handleBlur } = useField<string>(() => props.name, undefined, {
  initialValue: props.modelValue,
});
</script>
