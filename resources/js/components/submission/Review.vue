<template>
  <div class="review">
    <div class="review__section">
      <h4 class="review__title">Trip details</h4>
      <dl class="review__list">
        <div class="review__item">
          <dt class="review__label">Name</dt>
          <dd class="review__value">{{ values.name || '—' }}</dd>
        </div>
        <div class="review__item">
          <dt class="review__label">Description</dt>
          <dd class="review__value">{{ values.description || '—' }}</dd>
        </div>
        <div class="review__item">
          <dt class="review__label">Dates</dt>
          <dd class="review__value">{{ formatDate(values.start_date) }} → {{ formatDate(values.end_date) }}</dd>
        </div>
        <div class="review__item">
          <dt class="review__label">Status</dt>
          <dd class="review__value">{{ statusLabel(values.status) }}</dd>
        </div>
        <div class="review__item">
          <dt class="review__label">Visibility</dt>
          <dd class="review__value">{{ values.is_public ? 'Public' : 'Private' }}</dd>
        </div>
      </dl>
    </div>

    <div class="review__section">
      <h4 class="review__title">Destinations ({{ destinations.length }})</h4>

      <p v-if="!destinations.length" class="review__empty">
        No destinations added. You can add them after creating the trip.
      </p>

      <ul v-else class="review__destinations">
        <li v-for="(destination, i) in destinations" :key="i" class="review__destination">
          <div class="review__destination-head">
            <span class="review__destination-city">{{ destination.city || 'Untitled' }}</span>
            <span class="review__destination-country">{{ destination.country_code }}</span>
          </div>
          <div class="review__destination-meta">
            <span>{{ formatDate(destination.arrival_date) }} → {{ formatDate(destination.departure_date) }}</span>
            <span v-if="destination.budget">{{ formatBudget(destination.budget) }}</span>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useFormValues } from 'vee-validate';
import {dayjs} from '@/lib/date.ts';

interface DestinationValue {
  city: string;
  country_code: string;
  budget: number | null;
  arrival_date: string;
  departure_date: string;
}

const values = useFormValues<{
  name: string;
  description: string;
  start_date: string;
  end_date: string;
  status: string;
  is_public: boolean;
  destinations: DestinationValue[];
}>();

const destinations = computed(() => values.value.destinations ?? []);

const statusOptions = [
  { value: 'planned', label: 'Planned' },
  { value: 'in_progress', label: 'In progress' },
  { value: 'completed', label: 'Completed' },
];

function statusLabel(value: string | undefined): string {
  return <string>statusOptions.find((option) => option.value === value)?.label ?? value;
}

function formatDate(date: string | undefined): string {
  return date ? dayjs(date).format('D MMM YYYY') : '—';
}

function formatBudget(budget: number): string {
  return `£${budget.toLocaleString()}`;
}
</script>

<style scoped lang="scss">
@use '../../../css/common/colours';
@use '../../../css/common/typography';

.review {
  display: flex;
  flex-direction: column;
  gap: 24px;

  &__section {
    display: flex;
    flex-direction: column;
    gap: 14px;
  }

  &__title {
    font-size: typography.$font-size-base;
    font-weight: 600;
    color: colours.$colour-text;
    margin: 0;
    padding-bottom: 10px;
    border-bottom: 1px solid colours.$colour-border;
  }

  &__list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin: 0;
  }

  &__item {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 16px;
  }

  &__label {
    font-size: typography.$font-size-sm;
    color: colours.$colour-text-muted;
    flex-shrink: 0;
  }

  &__value {
    font-size: typography.$font-size-sm;
    color: colours.$colour-text;
    margin: 0;
    text-align: right;
    word-break: break-word;
  }

  &__empty {
    font-size: typography.$font-size-sm;
    color: colours.$colour-text-muted;
    margin: 0;
    padding: 12px 0;
  }

  &__destinations {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  &__destination {
    padding: 14px;
    border: 1px solid colours.$colour-border;
    border-radius: 10px;
  }

  &__destination-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
  }

  &__destination-city {
    font-size: typography.$font-size-sm;
    font-weight: 600;
    color: colours.$colour-text;
  }

  &__destination-country {
    font-size: typography.$font-size-xs;
    color: colours.$colour-text-muted;
    text-transform: uppercase;
    letter-spacing: 0.4px;
  }

  &__destination-meta {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    font-size: typography.$font-size-xs;
    color: colours.$colour-text-muted;
  }
}
</style>
