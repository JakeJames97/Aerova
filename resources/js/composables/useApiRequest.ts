import { ref } from 'vue';

export function useApiRequest() {
  const loading = ref<boolean>(false);
  const error = ref<string | null>(null);

  async function execute<T>(fn: () => Promise<T>): Promise<T | null> {
    loading.value = true;
    error.value = null;
    try {
      return await fn();
    } catch (e) {
      error.value = 'Something went wrong.';
      return null;
    } finally {
      loading.value = false;
    }
  }

  return { loading, error, execute };
}
