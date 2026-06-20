<template>
  <Modal
    :open="open"
    :title="isEdit ? 'Edit destination' : 'Add destination'"
    @update:open="emit('update:open', $event)"
  >
    <form class="form" @submit.prevent="onSubmit" :key="destination?.id">
      <CountrySelector :model-value="destination?.country_code ?? 'GB'"/>
      <div class="form__row">
        <FormField name="city" label="City" :model-value="destination?.city"/>
        <CurrencyInput name="budget" label="Budget" type="number" :model-value="destination?.budget" />
      </div>

      <div class="form__row">
        <FormField name="arrival_date" label="Arrival date" type="date" :model-value="destination?.arrival_date"/>
        <FormField name="departure_date" label="Departure date" type="date" :model-value="destination?.departure_date"/>
      </div>

      <p v-if="error" class="form__error">{{ error }}</p>

      <div class="form__actions">
        <BaseButton variant="outline" type="button" :disabled="isSubmitting" @click="emit('update:open', false)">
          Cancel
        </BaseButton>
        <BaseButton type="submit" :disabled="isSubmitting">
          {{ isSubmitting ? 'Saving…' : isEdit ? 'Save changes' : 'Add destination' }}
        </BaseButton>
      </div>
    </form>
  </Modal>
</template>

<script setup lang="ts">
import {computed, type PropType} from 'vue';
import {useForm} from 'vee-validate';
import {toTypedSchema} from '@vee-validate/yup';
import * as yup from 'yup';
import {useApiRequest} from '@/composables/useApiRequest';
import * as destinationsApi from '@/api/destinations';
import Modal from '@/components/Modal.vue';
import FormField from '@/components/FormField.vue';
import BaseButton from '@/components/BaseButton.vue';
import type {Destination, DestinationPayload} from '@/types/destinations.ts';
import {useNotificationStore} from "@/stores/useNotificationStore.ts";
import CountrySelector from "@/components/CountrySelector.vue";
import CurrencyInput from "@/components/CurrencyInput.vue";

const props = defineProps({
  open: {
    type: Boolean,
    default: false
  },
  tripId: {
    type: String,
    required: true
  },
  destination: {
    type: Object as PropType<Destination | null>,
    default: null
  },
});

const emit = defineEmits({
  'update:open': (value: boolean) => typeof value === 'boolean',
  saved: () => true,
});

const notify = useNotificationStore();
const isEdit = computed(() => props.destination !== null);

const schema = toTypedSchema(
  yup.object({
    city: yup.string().required('City is required').max(255),
    budget: yup.number().required('Budget is required'),
    country_code: yup.string().required('Country is required'),
    arrival_date: yup.string().required('Arrival date is required'),
    departure_date: yup
      .string()
      .required('Departure date is required')
      .test('after-arrival', 'Departure date must be after the arrival date', function (value) {
        const {arrival_date} = this.parent;
        return !arrival_date || !value || value >= arrival_date;
      }),
  }),
);

const {handleSubmit, isSubmitting, resetForm} = useForm({
  validationSchema: schema,
});

const {error, execute} = useApiRequest();

const onSubmit = handleSubmit(async (values) => {
  const payload = values as DestinationPayload;
  const result = await execute(() =>
    isEdit.value
      ? destinationsApi.updateDestination(props.destination!.id, payload)
      : destinationsApi.createDestination(props.tripId, payload),
  );
  if (result) {
    emit('saved');
    emit('update:open', false);

    if (!isEdit.value) {
      resetForm();

      notify.success('Destination successfully created!');
    }
  }
});
</script>
