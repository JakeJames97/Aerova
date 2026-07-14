<template>
  <div class="trip-step__wrapper">
    <FormField name="name" label="Name" />
    <FormTextArea name="description" label="Description" />
    <div class="form__row">
      <FormField name="start_date" label="Start date" type="date" />
      <FormField name="end_date" label="End date" type="date" />
    </div>
    <FormSelect name="status" label="Status" :options="statusOptions" :model-value="statusOptions[0].value" />
    <FormCheckbox name="is_public">Make this trip public (visible to others)</FormCheckbox>
  </div>
</template>

<script setup lang="ts">
import FormField from '@/components/FormField.vue';
import FormSelect from '@/components/FormSelect.vue';
import FormCheckbox from '@/components/FormCheckbox.vue';
import FormTextArea from "@/components/FormTextArea.vue";

const statusOptions = [
  { value: 'planned', label: 'Planned' },
  { value: 'in_progress', label: 'In progress' },
  { value: 'completed', label: 'Completed' },
];
</script>

<style lang="scss" scoped>
.trip-step__wrapper {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
</style>
