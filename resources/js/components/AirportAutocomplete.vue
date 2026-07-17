<template>
  <div class="form-field airport-autocomplete">
    <label class="form-field__label">
      <span>{{ label }}</span>
    </label>

    <div class="airport-autocomplete__control">
      <input
        :id="inputId"
        v-model="query"
        type="text"
        class="form-field__input"
        :placeholder="placeholder"
        autocomplete="off"
        @focus="open = true"
        @blur="open = false"
      />
    </div>

    <ul v-if="open && results.length" class="airport-autocomplete__results">
      <li
        v-for="airport in results"
        :key="airport.iata"
        class="airport-autocomplete__result"
        @mousedown.prevent="select(airport)"
      >
        <span class="airport-autocomplete__iata">{{ airport.iata }}</span>
        {{ airport.name }}
      </li>
    </ul>

    <p v-if="errorMessage" class="form-field__error">{{ errorMessage }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, useId } from 'vue';
import { useField } from 'vee-validate';
import * as airportsApi from '@/api/airports.ts';
import type { Airport } from '@/types/airports.ts';

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  placeholder: {
    type: String,
    default: 'Search airports…',
  },
  modelValue: {
    type: String,
  },
});

const emit = defineEmits<{
  select: [airport: Airport];
  clear: [];
}>();

const inputId = useId();

const { value: fieldValue, errorMessage, setValue } = useField<string | null>(() => props.name);

const query = ref(props.modelValue ?? '');
const results = ref<Airport[]>([]);
const open = ref(false);
const picked = ref(false);

let timer: ReturnType<typeof setTimeout> | undefined;

watch(query, (value) => {
  clearTimeout(timer);

  if (picked.value) {
    picked.value = false;

    return;
  }

  if (value.trim().length < 2) {
    results.value = [];

    return;
  }

  timer = setTimeout(async () => {
    results.value = await airportsApi.searchAirports(value.trim());
    open.value = true;
  }, 250);
});

function select(airport: Airport) {
  picked.value = true;
  query.value = `${airport.iata} — ${airport.name}`;
  setValue(airport.iata);
  results.value = [];
  open.value = false;
  emit('select', airport);
}

watch(
  fieldValue,
  (value) => {
    if (!value) {
      picked.value = true;
      query.value = '';
      results.value = [];
    }
  },
);
</script>
<style lang="scss" scoped>
@use './../../css/common/colours';
@use './../../css/common/typography';

.airport-autocomplete {
  position: relative;

  &__control {
    position: relative;
  }

  &__results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 20;
    list-style: none;
    margin: 4px 0 0;
    padding: 4px;
    max-height: 240px;
    overflow-y: auto;
    background: colours.$colour-surface;
    border: 1px solid colours.$colour-border;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  }

  &__result {
    display: flex;
    gap: 8px;
    padding: 8px 10px;
    border-radius: 6px;
    font-size: typography.$font-size-sm;
    color: colours.$colour-text;
    cursor: pointer;

    &:hover {
      background: colours.$colour-bg;
    }
  }

  &__iata {
    flex-shrink: 0;
    font-weight: 500;
    color: colours.$colour-text-muted;
  }
}
</style>
