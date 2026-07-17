<template>
  <Modal
    :open="open"
    :title="isEdit ? 'Edit transport' : 'Add transport'"
    @update:open="emit('update:open', $event)"
  >
    <form class="form" @submit.prevent="onSubmit">
      <FormSelect name="type" label="Type" :options="typeOptions"/>

      <div class="form__row">
        <FormField name="from" label="From" placeholder="Edinburgh Airport"/>
        <FormField name="to" label="To" placeholder="Heathrow Airport"/>
      </div>

      <div class="form__row">
        <FormField name="departure_at" label="Departs" type="datetime-local"/>
        <FormField name="arrival_at" label="Arrives" type="datetime-local"/>
      </div>

      <CurrencyInput name="price" label="Price"/>

      <template v-if="isFlight">
        <div class="form__row">
          <FormField name="identifier" label="Flight number" placeholder="BA1440"/>
          <FormField name="airline" label="Airline" placeholder="British Airways"/>
        </div>

        <div class="form__row">
          <FormField name="from_iata" label="From (IATA)" placeholder="EDI" maxlength="3"/>
          <FormField name="to_iata" label="To (IATA)" placeholder="LHR" maxlength="3"/>
        </div>
      </template>

      <p v-if="error" class="form__error">{{ error }}</p>

      <div class="form__actions">
        <BaseButton variant="outline" type="button" :disabled="isSubmitting" @click="emit('update:open', false)">
          Cancel
        </BaseButton>
        <BaseButton type="submit" :disabled="isSubmitting">
          {{ isSubmitting ? 'Saving…' : isEdit ? 'Save changes' : 'Add transport' }}
        </BaseButton>
      </div>
    </form>
  </Modal>
</template>

<script setup lang="ts">
import {computed, type PropType, watch} from 'vue';
import {useForm} from 'vee-validate';
import {toTypedSchema} from '@vee-validate/yup';
import {transportSchema} from '@/schemas/transport.ts';
import {useApiRequest} from '@/composables/useApiRequest';
import * as transportsApi from '@/api/transports';
import Modal from '@/components/Modal.vue';
import FormField from '@/components/FormField.vue';
import FormSelect from '@/components/FormSelect.vue';
import CurrencyInput from '@/components/CurrencyInput.vue';
import BaseButton from '@/components/BaseButton.vue';
import {useTripStore} from '@/stores/useTripStore.ts';
import {useNotificationStore} from '@/stores/useNotificationStore.ts';
import type {Transport, TransportPayload} from '@/types/transports';

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  destinationId: {
    type: String,
    required: true,
  },
  transport: {
    type: Object as PropType<Transport | null>,
    default: null,
  },
});

const emit = defineEmits({
  'update:open': (value: boolean) => typeof value === 'boolean',
});

const notify = useNotificationStore();
const tripStore = useTripStore();

const isEdit = computed(() => props.transport !== null);

const typeOptions = [
  {value: 'flight', label: 'Flight'},
  {value: 'train', label: 'Train'},
  {value: 'car', label: 'Car'},
  {value: 'other', label: 'Other'},
];

const {handleSubmit, isSubmitting, values, setFieldValue, resetForm} = useForm<TransportPayload>({
  validationSchema: toTypedSchema(transportSchema),
  initialValues: {
    type: 'flight',
    from: '',
    to: '',
    identifier: null,
    departure_at: '',
    arrival_at: '',
    price: 0,
    airline: null,
    from_iata: null,
    to_iata: null,
  },
});

const {error, execute} = useApiRequest();

const isFlight = computed(() => values.type === 'flight');

watch(
  () => props.open,
  (open) => {
    if (!open) {
      return;
    }

    if (props.transport) {
      resetForm({
        values: {
          type: props.transport.type,
          from: props.transport.from,
          to: props.transport.to,
          identifier: props.transport.identifier,
          departure_at: props.transport.departure_at.slice(0, 16),
          arrival_at: props.transport.arrival_at.slice(0, 16),
          price: props.transport.price,
          airline: props.transport.airline,
          from_iata: props.transport.from_iata,
          to_iata: props.transport.to_iata,
        },
      });

      return;
    }

    resetForm();
  },
);

watch(isFlight, (flight) => {
  if (flight) {
    return;
  }

  setFieldValue('airline', null);
  setFieldValue('from_iata', null);
  setFieldValue('to_iata', null);
});

const onSubmit = handleSubmit(async (formValues) => {
  const result = await execute(() =>
    isEdit.value
      ? transportsApi.updateTransport(props.transport!.id, formValues)
      : transportsApi.createTransport(props.destinationId, formValues),
  );

  if (!result) {
    return;
  }

  await tripStore.reload();
  notify.success(`Transport ${isEdit.value ? 'updated' : 'added'}`);
  emit('update:open', false);
});
</script>
