<template>
  <div class="steps">
    <span
      v-for="(label, i) in steps"
      :key="label"
      class="step"
      :class="{ 'step--active': current === i, 'step--done': current > i }"
    >
      <span class="step__num">{{ i + 1 }}</span>
      {{ label }}
    </span>
  </div>
</template>

<script setup lang="ts">
import type {PropType} from "vue";

defineProps({
  steps: {
    type: Array as PropType<string[]>,
    required: true
  },
  current: {
    type: Number,
    default: 1,
  }
});
</script>

<style scoped lang="scss">
@use '../../../css/common/colours';
@use '../../../css/common/typography';

.steps {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-bottom: 28px;
}

.step {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: typography.$font-size-sm;
  color: colours.$colour-text-muted;
  white-space: nowrap;

  &:not(:last-child)::after {
    content: '';
    width: 32px;
    height: 1px;
    background: colours.$colour-border;
    margin-left: 4px;
  }

  &__num {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 1px solid colours.$colour-border;
    background: colours.$colour-surface;
    font-size: typography.$font-size-base;
    flex-shrink: 0;
  }

  &--active {
    color: colours.$colour-accent;
    font-weight: 500;

    .step__num {
      background: colours.$colour-accent;
      border-color: colours.$colour-accent;
      color: colours.$colour-text-light;
    }
  }

  &--done {
    color: colours.$colour-text;

    .step__num {
      background: colours.$colour-accent;
      border-color: colours.$colour-accent;
      color: colours.$colour-text-light;
    }
  }
}
</style>
