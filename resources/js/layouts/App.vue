<template>
  <div>
    <header class="header">
      <div class="header__pill">
        <div class="header__inner">
          <div class="header__brand">
            <router-link :to="{ name: 'home' }">
              <img src="@/images/Logo.png" alt="logo"/>
            </router-link>
          </div>

          <nav class="header__nav header__nav--desktop">
            <router-link
              v-for="link in navLinks"
              :key="link.name"
              :to="{ name: link.name }"
              class="header__link"
            >
              {{ link.label }}
            </router-link>
          </nav>

          <div class="header__actions">
            <NotificationBell/>

            <BaseButton
              variant="outline"
              class="header__logout header__logout--desktop"
              @click="handleLogout"
            >
              Log out
            </BaseButton>

            <button
              type="button"
              class="header__burger"
              :aria-label="menuOpen ? 'Close menu' : 'Open menu'"
              :aria-expanded="menuOpen"
              @click="menuOpen = !menuOpen"
            >
              <Bars3Icon v-if="!menuOpen" class="header__burger-icon"/>
              <XMarkIcon v-else class="header__burger-icon"/>
            </button>
          </div>
        </div>
      </div>

      <transition name="menu">
        <nav v-if="menuOpen" class="header__nav header__nav--mobile">
          <router-link
            v-for="link in navLinks"
            :key="link.name"
            :to="{ name: link.name }"
            class="header__link header__link--mobile"
            @click="menuOpen = false"
          >
            {{ link.label }}
          </router-link>
          <button class="header__logout header__logout--mobile" @click="handleLogout">
            Log out
          </button>
        </nav>
      </transition>
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
import {ref, watch} from 'vue';
import {useRoute, useRouter} from 'vue-router';
import {useAuthStore} from '@/stores/useAuthStore.ts';
import BaseButton from '@/components/BaseButton.vue';
import Notification from '@/components/Notification.vue';
import NotificationBell from '@/components/NotificationBell.vue';
import {Bars3Icon, XMarkIcon} from '@heroicons/vue/24/outline';

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();

const menuOpen = ref(false);

const navLinks = [
  {name: 'home', label: 'Home'},
  {name: 'dashboard', label: 'Dashboard'},
  {name: 'discover', label: 'Discover'},
];

watch(() => route.fullPath, () => {
  menuOpen.value = false;
});

async function handleLogout() {
  menuOpen.value = false;
  await auth.logout();
  router.push({name: 'login'});
}
</script>
