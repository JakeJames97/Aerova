<template>
  <select class="country-filter" v-model="selectedCountry" @change="onChange(selectedCountry)">
    <option value="">All countries</option>
    <option v-for="option in options" :key="option.value" :value="option.value">
      {{ option.label }}
    </option>
  </select>
</template>

<script setup lang="ts">
import {computed, onMounted, ref} from 'vue';
import { useApiRequest } from '@/composables/useApiRequest';
import * as countriesApi from '@/api/countries';
import type { Country } from '@/types/countries';
import {useRoute, useRouter} from "vue-router";

const router = useRouter();
const route = useRoute();
const selectedCountry = ref(route.query.country as string || '');

const { execute } = useApiRequest();
const options = ref<{ value: string; label: string }[]>([]);

function onChange(code: string) {
  router.push({
    query: {
      ...route.query,
      country: code || undefined,
      page: 1,
    },
  });
}

onMounted(async () => {
  const countries = await execute(() => countriesApi.getCountries());
  options.value = (countries ?? []).map((country: Country) => ({ value: country.code, label: country.name }));
});
</script>
