<template>
  <div class="home">
    <div class="home-hero__wrap">
      <section class="home-hero" :style="{ '--hero-image': `url(${heroImage})` }">
        <div class="home-hero__cover"></div>
        <div class="home-hero__content">
        <span class="home-hero__eyebrow">
          <span class="home-hero__pip"></span>
          Your trips, mapped
        </span>
          <h1 class="home-hero__title">Every journey<br />starts with a plan</h1>
          <p class="home-hero__lead">
            Map out destinations, track what's left to do, and find inspiration in trips other travellers have shared.
          </p>
          <div class="home-hero__actions">
            <BaseButton @click="goToDashboard">Get Started</BaseButton>
            <BaseButton variant="light" @click="goToDiscover">Discover</BaseButton>
          </div>
        </div>
      </section>
      <Search />
    </div>
    <FeaturedDestinations />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import BaseButton from '@/components/BaseButton.vue';
import heroImage from '@/images/hero.jpg';
import FeaturedDestinations from "@/components/FeaturedDestinations.vue";
import Search from "@/components/Search.vue";

const router = useRouter();

function goToDiscover() {
  router.push({ name: 'discover' });
}

function goToDashboard() {
  router.push({ name: 'dashboard' });
}
</script>
