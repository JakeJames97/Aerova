<template>
  <div class="trip-card-skeleton" aria-hidden="true">
    <div class="trip-card-skeleton__top">
      <div class="skeleton-block trip-card-skeleton__name"></div>
      <div class="skeleton-block trip-card-skeleton__pill"></div>
    </div>

    <div class="skeleton-block trip-card-skeleton__dates"></div>
    <div class="skeleton-block trip-card-skeleton__meta"></div>
  </div>
</template>

<style lang="scss" scoped>
@use './../../../css/common/colours';

.skeleton-block {
  background: colours.$colour-border;
  border-radius: 6px;
  animation: skeleton-pulse 1.4s ease-in-out infinite;
}

.trip-card-skeleton {
  padding: 20px;
  background: colours.$colour-surface;
  border: 1px solid colours.$colour-border;
  border-radius: 12px;
  display: flex;
  flex-direction: column;
  gap: 12px;

  &__top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  &__name {
    width: 55%;
    height: 20px;
  }

  &__pill {
    width: 70px;
    height: 22px;
    border-radius: 999px;
    flex-shrink: 0;
  }

  &__dates {
    width: 40%;
    height: 14px;
  }

  &__meta {
    width: 30%;
    height: 13px;
  }
}
</style>
