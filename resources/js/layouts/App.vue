<template>
  <div>
    <Navigation/>
    <main class="content">
      <div class="container">
        <Notification/>
        <router-view :key="route.fullPath"/>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import {useRoute} from 'vue-router';
import {useAuthStore} from '@/stores/useAuthStore.ts';
import Notification from '@/components/Notification.vue';
import Navigation from "@/components/Navigation.vue";
import {onMounted} from "vue";

const route = useRoute();
const auth = useAuthStore();

onMounted(async () => {
  if (auth.token) {
    await auth.fetchUser();
  }
});
</script>
