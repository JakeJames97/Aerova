<template>
  <label class="form-field form-field--checkbox">
    <input
      type="checkbox"
      class="form-field__checkbox"
      :checked="value"
      @change="handleChange"
    />
    <span class="form-field__label"><slot /></span>
  </label>
</template>

<script setup lang="ts">
import { useField } from 'vee-validate';

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
  modelValue: {
    type: Boolean,
    default: true,
  },
});

const { value, handleChange } = useField<boolean>(
  () => props.name,
  undefined,
  {
    type: 'checkbox',
    checkedValue: true,
    uncheckedValue: false,
    initialValue: props.modelValue,
  },
);
</script>
