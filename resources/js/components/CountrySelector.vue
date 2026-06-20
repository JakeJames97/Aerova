<template>
  <FormSelect
    name="country_code"
    label="Country"
    :options="options"
  />
</template>

<script setup lang="ts">
import FormSelect from '@/components/FormSelect.vue';
import * as countriesApi from "@/api/countries.ts";
import {onMounted, ref} from "vue";
import {useApiRequest} from "@/composables/useApiRequest.ts";
import type {Country} from "@/types/countries.ts";

interface Option {
  value: string;
  label: string;
}

const { execute } = useApiRequest();
const options = ref<Option[]>([]);

async function loadCountries() {
  return await execute(() => countriesApi.getCountries());
}

onMounted(async () => {
  const countries = await loadCountries();

  options.value = (countries ?? []).map((country: Country) => ({
    value: country.code,
    label: country.name,
  }));
});
</script>
