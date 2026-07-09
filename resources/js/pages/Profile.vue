<template>
  <div class="profile">
    <header class="profile__header">
      <h1 class="profile__title">Profile</h1>
      <p class="profile__subtitle">Your account details.</p>
    </header>

    <section class="profile__card">
      <h2 class="profile__title">Account details</h2>

      <dl class="profile__details">
        <div class="profile__detail">
          <dt class="profile__label">Username</dt>
          <dd class="profile__value">{{ auth.user?.username }}</dd>
        </div>
        <div class="profile__detail">
          <dt class="profile__label">Email</dt>
          <dd class="profile__value">{{ auth.user?.email }}</dd>
        </div>
        <div class="profile__detail">
          <dt class="profile__label">Member since</dt>
          <dd class="profile__value">{{ memberSince }}</dd>
        </div>
      </dl>
    </section>

    <section class="profile__danger">
      <div class="profile__danger-copy">
        <h2 class="profile__danger-title">Delete account</h2>
        <p class="profile__danger-text">
          Permanently removes your trips, destinations, and tasks. This cannot be undone.
        </p>
      </div>
      <BaseButton variant="danger" @click="modalOpen = true">Delete account</BaseButton>
    </section>

    <ConfirmDialog
      v-model:open="modalOpen"
      title="Delete account"
      message="This permanently removes your account, trips, destinations, and tasks. This cannot be undone."
      confirm-label="Delete my account"
      :loading="loading"
      @confirm="handleDelete"
    />
  </div>
</template>

<script setup lang="ts">
import {ref, computed} from 'vue';
import {useRouter} from 'vue-router';
import {useAuthStore} from '@/stores/useAuthStore';
import {profileApi} from '@/api/profile';
import BaseButton from '@/components/BaseButton.vue';
import {dayjs} from '@/lib/date';
import ConfirmDialog from "@/components/modals/ConfirmDialog.vue";
import {useApiRequest} from "@/composables/useApiRequest.ts";
import {useNotificationStore} from "@/stores/useNotificationStore.ts";

const auth = useAuthStore();
const notify = useNotificationStore();
const router = useRouter();
const modalOpen = ref(false);
const {loading, error, execute} = useApiRequest();

const memberSince = computed(() =>
  auth.user?.created_at ? dayjs(auth.user.created_at).format('D MMMM YYYY') : '—',
);

async function handleDelete() {
  await execute(() => profileApi.deleteAccount());
  if (error.value) {
    notify.error('An error occurred trying to delete your profile');
    return;
  }
  auth.clearSession();
  router.push({name: 'home'});
}
</script>
