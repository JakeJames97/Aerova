<template>
  <div>
    <header class="header">
      <div class="header__inner container">
        <div class="header__brand">
          <router-link
            :to="{ name: 'home' }"
          >
            <LogoIcon/>
          </router-link>
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
        <NotificationBell/>
        <BaseButton variant="outline" @click="handleLogout">Log out</BaseButton>
      </div>
    </header>
    <main class="content">
      <div class="container">
        <Notification/>
        <router-view :key="route.fullPath"/>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import {useRoute, useRouter} from 'vue-router';
import {useAuthStore} from '@/stores/useAuthStore.ts';
import BaseButton from "@/components/BaseButton.vue";
import Notification from "@/components/Notification.vue";
import LogoIcon from '@/icons/logo.svg?component';
import NotificationBell from "@/components/NotificationBell.vue";

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();

const navLinks = [
  {name: 'home', label: 'Home'},
  {name: 'dashboard', label: 'Dashboard'},
  {name: 'discover', label: 'Discover'},
];

async function handleLogout() {
  await auth.logout();
  router.push({name: 'login'});
}
</script>
