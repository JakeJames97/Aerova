<template>
  <div>
    <header class="header">
      <div class="header__inner container">
        <div class="header__brand">
          <span class="header__logo">T</span>
          <span class="header__title">Trip tracker</span>
        </div>

        <nav class="nav">
          <router-link
            v-for="link in navLinks"
            :key="link.name"
            :to="{ name: link.name }"
            class="nav__link"
          >
            {{ link.label }}
          </router-link>
        </nav>

        <BaseButton variant="outline" @click="handleLogout">Log out</BaseButton>
      </div>
    </header>

    <main class="content">
      <div class="container">
        <router-view />
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/useAuthStore.ts';
import BaseButton from "@/components/BaseButton.vue";

const router = useRouter();
const auth = useAuthStore();

const navLinks = [{ name: 'dashboard', label: 'Dashboard' }];

async function handleLogout() {
  await auth.logout();
  router.push({ name: 'login' });
}
</script>
