<template>
  <div class="container fade-in">
    <div class="max-w-3xl mx-auto">
      <div class="mb-6">
        <router-link to="/communities" class="back-link" style="text-decoration: none;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          Back to Communities
        </router-link>
        <h1 class="page-title mt-2">Create New Community</h1>
      </div>

      <div class="glass-panel p-8">
        <form @submit.prevent="submitForm">
          <h2 class="section-title">Community Information</h2>
          <div class="grid-2">
            <div class="form-group">
              <label for="name" class="form-label">Community Name <span class="text-danger">*</span></label>
              <input type="text" id="name" class="form-control" v-model="form.name" maxlength="150" required placeholder="e.g. St. Joseph Jumuiya" />
            </div>

            <div class="form-group">
              <label for="zone_id" class="form-label">Zone ID <span class="text-danger">*</span></label>
              <input type="number" id="zone_id" class="form-control" v-model="form.zone_id" required placeholder="e.g. 1" />
            </div>
          </div>

          <div v-if="error" class="text-danger mt-4">{{ error }}</div>

          <div class="form-actions mt-8 flex justify-end gap-4">
            <router-link to="/communities" class="btn btn-secondary" style="text-decoration: none;">Cancel</router-link>
            <button type="submit" class="btn btn-primary" :disabled="loading">
              {{ loading ? 'Creating...' : 'Create Community' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import CommunityService from '../services/CommunityService';

const router = useRouter();
const loading = ref(false);
const error = ref(null);

const form = ref({
  name: '',
  zone_id: null
});

const submitForm = async () => {
  loading.value = true;
  error.value = null;
  try {
    await CommunityService.create(form.value);
    router.push('/communities');
  } catch (e) {
    console.error('Failed to create community:', e);
    error.value = 'Failed to create community. Please check the inputs.';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.back-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--color-text-muted);
  font-size: 0.875rem;
  font-weight: 500;
  margin-bottom: 0.5rem;
}
.back-link:hover {
  color: var(--color-primary);
}
.max-w-3xl {
  max-width: 48rem;
}
.mx-auto {
  margin-left: auto;
  margin-right: auto;
}
.p-8 {
  padding: 2rem;
}
.mt-2 {
  margin-top: 0.5rem;
}
.mt-4 {
  margin-top: 1rem;
}
.mt-8 {
  margin-top: 2rem;
}
.section-title {
  font-size: 1.125rem;
  color: var(--color-primary);
  border-bottom: 1px solid var(--color-border);
  padding-bottom: 0.5rem;
  margin-bottom: 1.5rem;
}
.grid-2 {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 1rem;
}
@media (min-width: 640px) {
  .grid-2 {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
}
.form-actions {
  border-top: 1px solid var(--color-border);
  padding-top: 1.5rem;
}
.text-danger {
  color: var(--color-danger);
}
</style>
