<template>
  <div class="trip-create">
    <header class="trip-create__header">
      <h1 class="trip-create__title">Create trip</h1>
      <p class="trip-create__subtitle">Plan your trip and add your destinations.</p>
    </header>

    <div class="trip-create__card">
      <Stepper :steps="stepLabels" :current="step" />

      <form class="form" @submit.prevent="onNext">
        <CreateTripStep v-show="step === 0" />
        <CreateDestinationRepeater v-show="step === 1" />
        <Review v-show="step === 2" />

        <p v-if="error" class="form__error">{{ error }}</p>

        <div class="form__actions">
          <BaseButton v-if="step > 0" variant="outline" type="button" :disabled="isSubmitting" @click="step--">
            Back
          </BaseButton>
          <BaseButton v-else variant="outline" type="button" :disabled="isSubmitting" @click="cancel">
            Cancel
          </BaseButton>
          <BaseButton type="submit" :disabled="isSubmitting">
            {{ submitLabel }}
          </BaseButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/yup';
import { useApiRequest } from '@/composables/useApiRequest';
import * as tripsApi from '@/api/trips';
import { tripStepSchema, destinationsStepSchema, reviewStepSchema } from '@/schemas/tripCreate.ts';
import Stepper from '@/components/submission/Stepper.vue';
import CreateTripStep from '@/components/submission/CreateTripStep.vue';
import CreateDestinationRepeater from '@/components/submission/CreateDestinationRepeater.vue';
import BaseButton from '@/components/BaseButton.vue';
import Review from "@/components/submission/Review.vue";
import type {TripPayload} from "@/types/trips.ts";
import {useNotificationStore} from "@/stores/useNotificationStore.ts";

const router = useRouter();
const notify = useNotificationStore();
const step = ref(0);
const stepLabels = ['Trip details', 'Destinations', 'Review'];

const stepSchemas = [tripStepSchema, destinationsStepSchema, reviewStepSchema];

const { handleSubmit, isSubmitting } = useForm({
  validationSchema: computed(() => toTypedSchema(stepSchemas[step.value])),
  initialValues: {
    name: '', description: '', start_date: '', end_date: '',
    status: 'planned', is_public: true, destinations: [],
  },
});

const submitLabel = computed(() =>
  isSubmitting.value ? 'Saving…' : step.value === 2 ? 'Create trip' : 'Continue',
);

function cancel() {
  router.push({ name: 'trips' });
}

const { error, execute } = useApiRequest();

const onNext = handleSubmit(async (formValues) => {
  if (step.value < 2) {
    step.value++;
    return;
  }
  const result = await execute(() => tripsApi.createTrip(formValues as TripPayload));
  if (!result || error.value) {
    notify.error('An error occurred when trying to create your trip, please try again later');
    return;
  }
  router.push({ name: 'trip', params: { id: result.id } });
});
</script>
