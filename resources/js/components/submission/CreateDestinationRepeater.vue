<template>
  <div class="create-destination">
    <p class="create-destination__hint">Add the places you'll visit. You can add these later if you'd prefer.</p>

    <div v-for="(field, index) in fields" :key="field.key" class="create-destination__wrapper">
      <div class="create-destination__head">
        <span class="create-destination__title">Destination {{ index + 1 }}</span>
        <button type="button" class="create-destination__remove" @click="remove(index)">
          <TrashIcon class="create-destination__remove-icon" />
        </button>
      </div>

      <CountrySelector :name="`destinations[${index}].country_code`" />
      <div class="form__row">
        <FormField :name="`destinations[${index}].city`" label="City" />
        <CurrencyInput :name="`destinations[${index}].budget`" label="Budget" type="number" />
      </div>
      <div class="form__row">
        <FormField :name="`destinations[${index}].arrival_date`" label="Arrival date" type="date" />
        <FormField :name="`destinations[${index}].departure_date`" label="Departure date" type="date" />
      </div>
    </div>

    <button type="button" class="create-destination__add" @click="addDestination">
      <PlusIcon class="create-destination__add-icon" />
      Add destination
    </button>
  </div>
</template>

<script setup lang="ts">
import { useFieldArray } from 'vee-validate';
import { PlusIcon, TrashIcon } from '@heroicons/vue/24/outline';
import FormField from '@/components/FormField.vue';
import CountrySelector from '@/components/CountrySelector.vue';
import CurrencyInput from '@/components/CurrencyInput.vue';

const { fields, push, remove } = useFieldArray('destinations');

function addDestination() {
  push({ country_code: 'GB', city: '', budget: null, arrival_date: '', departure_date: '' });
}
</script>

<style scoped lang="scss">
@use '../../../css/common/colours';
@use '../../../css/common/typography';

.create-destination {
  &__hint {
    font-size: typography.$font-size-sm;
    color: colours.$colour-text-muted;
    margin: 0 0 20px;
  }

  &__wrapper {
    border: 1px solid colours.$colour-border;
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 14px;
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  &__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
  }

  &__title {
    font-size: typography.$font-size-sm;
    font-weight: 600;
    color: colours.$colour-text;
  }

  &__remove {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border: none;
    background: none;
    border-radius: 8px;
    color: colours.$colour-text-muted;
    cursor: pointer;
    transition: background 0.15s ease, color 0.15s ease;

    &:hover {
      background: rgba(220, 38, 38, 0.08);
      color: colours.$colour-danger;
    }
  }

  &__remove-icon {
    width: 18px;
    height: 18px;
  }

  &__add {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    border: 1px dashed colours.$colour-border;
    border-radius: 10px;
    background: none;
    color: colours.$colour-text-muted;
    font-size: typography.$font-size-sm;
    font-weight: 500;
    cursor: pointer;
    transition: border-color 0.15s ease, color 0.15s ease;

    &:hover {
      border-color: colours.$colour-accent;
      color: colours.$colour-accent;
    }
  }

  &__add-icon {
    width: 16px;
    height: 16px;
  }
}
</style>
