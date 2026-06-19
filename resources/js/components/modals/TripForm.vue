<template>
  <Modal
    :open="open"
    :title="isEdit ? 'Edit trip' : 'Create trip'"
    @update:open="emit('update:open', $event)"
  >
    <form class="trip-form" @submit.prevent="onSubmit">
      <FormField name="name" label="Name" :model-value="trip?.name"/>
      <FormField name="description" label="Description" :model-value="trip?.description" />

      <div class="trip-form__row">
        <FormField name="start_date" label="Start date" type="date" :model-value="trip?.start_date"/>
        <FormField name="end_date" label="End date" type="date" :model-value="trip?.end_date"/>
      </div>

      <FormSelect name="status" label="Status" :options="statusOptions" :model-value="trip?.status ?? 'planned'" />

      <p v-if="error" class="trip-form__error">{{ error }}</p>

      <div class="trip-form__actions">
        <BaseButton variant="outline" type="button" :disabled="isSubmitting" @click="emit('update:open', false)">
          Cancel
        </BaseButton>
        <BaseButton type="submit" :disabled="isSubmitting">
          {{ isSubmitting ? 'Saving…' : isEdit ? 'Save changes' : 'Create trip' }}
        </BaseButton>
      </div>
    </form>
  </Modal>
</template>

<script setup lang="ts">
import { computed, type PropType } from 'vue';
import { useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/yup';
import * as yup from 'yup';
import { useApiRequest } from '@/composables/useApiRequest';
import * as tripsApi from '@/api/trips';
import Modal from '@/components/Modal.vue';
import FormField from '@/components/FormField.vue';
import FormSelect from '@/components//FormSelect.vue';
import BaseButton from '@/components//BaseButton.vue';
import { TRIP_STATUSES } from '@/types/trips.ts';
import type { Trip, TripPayload } from '@/types/trips.ts';

const props = defineProps({
  open: {
    type: Boolean,
    default: false
  },
  trip: {
    type: Object as PropType<Trip | null>,
    default: null
  },
});

const emit = defineEmits({
  'update:open': (value: boolean) => typeof value === 'boolean',
  saved: (trip: Trip) => !!trip,
});

const isEdit = computed(() => props.trip !== null);

const statusOptions = [
  { value: 'planned', label: 'Planned' },
  { value: 'in_progress', label: 'In progress' },
  { value: 'completed', label: 'Completed' },
];

const schema = toTypedSchema(
  yup.object({
    name: yup.string().required('Name is required').max(255),
    description: yup.string().nullable(),
    start_date: yup.string().required('Start date is required'),
    end_date: yup
      .string()
      .required('End date is required')
      .test('after-start', 'End date must be after the start date', function (value) {
        const { start_date } = this.parent;
        return !start_date || !value || value >= start_date;
      }),
    status: yup.string().oneOf(TRIP_STATUSES).required(),
  }),
);

const { handleSubmit, isSubmitting, resetForm } = useForm({
  validationSchema: schema,
});

const { error, execute } = useApiRequest();

const onSubmit = handleSubmit(async (values) => {
  const payload = values as TripPayload;

  const result = await execute(() =>
    isEdit.value
      ? tripsApi.updateTrip(props.trip!.id, payload)
      : tripsApi.createTrip(payload),
  );

  if (result) {
    emit('saved', result);
    emit('update:open', false);

    if (!isEdit.value) {
      resetForm();
    }
  }
});
</script>
