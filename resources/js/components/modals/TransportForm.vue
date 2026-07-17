<template>
  <Modal
    :open="open"
    :title="isEdit ? 'Edit transport' : 'Add transport'"
    @update:open="emit('update:open', $event)"
  >
    <form class="form" @submit.prevent="onSubmit">
      <FormSelect name="type" label="Type" :options="typeOptions" :model-value="transport?.type ?? 'flight'"/>

      <div class="form__row">
        <AirportAutocomplete
          name="from_iata"
          label="From (airport)"
          placeholder="Edinburgh"
          :model-value="transport?.from as string"
          :model-label="transport ? `${transport.from_iata} — ${transport.from}` : ''"
          @select="(airport) => setFieldValue('from', airport.name)"
          @clear="() => setFieldValue('from', '')"
        />
        <AirportAutocomplete
          name="to_iata"
          label="To (airport)"
          placeholder="Heathrow"
          :model-value="transport?.to as string"
          :model-label="transport ? `${transport.to_iata} — ${transport.to}` : ''"
          @select="(airport) => setFieldValue('to', airport.name)"
          @clear="() => setFieldValue('to', '')"
        />
      </div>

      <div class="form__row">
        <FormField name="departure_at" label="Departs" type="datetime-local" :model-value="transport?.departure_at.slice(0, 16)"/>
        <FormField name="arrival_at" label="Arrives" type="datetime-local" :model-value="transport?.arrival_at.slice(0, 16)"/>
      </div>

      <CurrencyInput name="price" label="Price" :model-value="transport?.price"/>

      <template v-if="isFlight">
        <div class="form__row">
          <FormField name="identifier" label="Flight number" placeholder="BA1440" :model-value="transport?.identifier"/>
          <FormField name="airline" label="Airline" placeholder="British Airways" :model-value="transport?.airline"/>
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
import { useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/yup';
import { transportSchema } from '@/schemas/transport.ts';
import { useApiRequest } from '@/composables/useApiRequest';
import * as transportsApi from '@/api/transports';
import Modal from '@/components/Modal.vue';
import FormField from '@/components/FormField.vue';
import FormSelect from '@/components/FormSelect.vue';
import CurrencyInput from '@/components/CurrencyInput.vue';
import BaseButton from '@/components/BaseButton.vue';
import AirportAutocomplete from '@/components/AirportAutocomplete.vue';
import { useTripStore } from '@/stores/useTripStore.ts';
import { useNotificationStore } from '@/stores/useNotificationStore.ts';
import type { Transport, TransportPayload } from '@/types/transports';

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
  { value: 'flight', label: 'Flight' },
  { value: 'train', label: 'Train' },
  { value: 'car', label: 'Car' },
  { value: 'other', label: 'Other' },
];

const { handleSubmit, isSubmitting, values, setFieldValue, resetForm } = useForm<TransportPayload>({
  validationSchema: toTypedSchema(transportSchema),
});

const { error, execute } = useApiRequest();

const isFlight = computed(() => values.type === 'flight');

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

  if (!isEdit.value) {
    resetForm();
  }
});
</script>
