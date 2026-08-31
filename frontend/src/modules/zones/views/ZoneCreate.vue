<template>
  <div class="container fade-in">
    <div class="page-header">
      <div>
        <h1 class="page-title">Add Zone</h1>
        <p class="text-muted mt-2">Create a new zone</p>
      </div>
      <router-link to="/zones" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Back to List
      </router-link>
    </div>

    <div class="glass-panel p-6 max-w-2xl">
      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" v-model="form.name" required maxlength="150" placeholder="Enter zone name">
        </div>

        <div class="form-actions mt-6">
          <button type="submit" class="btn btn-primary" :disabled="loading">
            <span v-if="loading">Saving...</span>
            <span v-else>Save Zone</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import ZoneService from '../services/ZoneService';

const router = useRouter();
const loading = ref(false);

const form = ref({
  name: ''
});

const submitForm = async () => {
  loading.value = true;
  try {
    await ZoneService.create(form.value);
    router.push('/zones');
  } catch (error) {
    console.error('Error creating zone:', error);
    alert('Failed to create zone. Check console for details.');
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.text-muted {
  color: var(--color-text-muted);
}
.p-6 {
  padding: 1.5rem;
}
.max-w-2xl {
  max-width: 42rem;
  margin: 0 auto;
}
.mt-6 {
  margin-top: 1.5rem;
}
.form-actions {
  display: flex;
  justify-content: flex-end;
}
</style>
