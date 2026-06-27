<template>
  <form class="search" @submit.prevent="onSearch">
    <SearchIcon class="search__icon" />
    <input v-model="query" type="text" placeholder="Where would you like to go?" class="search__input" />
    <BaseButton type="submit" class="search__button">Search</BaseButton>
  </form>
</template>
<script lang="ts" setup>
import {ref} from "vue";
import router from "@/router";
import SearchIcon from '@/icons/search.svg?component';
import BaseButton from "@/components/BaseButton.vue";

const query = ref('');
function onSearch() {
  if (!query.value.trim()) {
    return;
  }

  router.push({ name: 'discover', query: { search: query.value.trim() } });
}
</script>
