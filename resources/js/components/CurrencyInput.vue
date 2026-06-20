<template>
  <div class="form-field">
    <label class="form-field__label">
      <span>{{ label }}</span>
    </label>

    <div class="input-wrapper">
      <span class="prefix">£</span>

      <input
        v-model="value"
        type="number"
        step="0.01"
        @blur="handleBlur"
      />
    </div>

    <span v-if="errorMessage" class="form-field__error">
      {{ errorMessage }}
    </span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
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
  modelValue: {
    type: Number,
    default: 0,
  },
});

const { value, errorMessage, handleBlur } = useField<number>(
  () => props.name,
  undefined,
  { initialValue: props.modelValue ?? 0 }
);
</script>

<style scoped>
.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.prefix {
  position: absolute;
  left: 10px;
}

input {
  padding-left: 24px;
}
</style>
