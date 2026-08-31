<template>
  <div class="container fade-in">
    <div class="max-w-3xl mx-auto">
      <div class="mb-6">
        <router-link to="/communities" class="back-link" style="text-decoration: none;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          Back to Communities
        </router-link>
        <h1 class="page-title mt-2">Edit Community</h1>
      </div>

      <div v-if="loadingInitial" class="text-center py-8 text-muted">
        Loading community details...
      </div>
      <div class="glass-panel p-8" v-else>
        <form @submit.prevent="submitForm">
          <h2 class="section-title">Community Information</h2>
          <div class="grid-2">
            <div class="form-group">
              <label for="name" class="form-label">Community Name <span class="text-danger">*</span></label>
              <input type="text" id="name" class="form-control" v-model="form.name" maxlength="150" required placeholder="e.g. St. Joseph Jumuiya" />
            </div>

            <div class="form-group">
              <label for="zone_id" class="form-label">Zone <span class="text-danger">*</span></label>
              <select id="zone_id" class="form-control" v-model="form.zone_id" required>
                <option value="" disabled>Select a zone</option>
                <option v-for="zone in zones" :key="zone.id" :value="zone.id">
                  {{ zone.name }} (ID: {{ zone.id }})
                </option>
              </select>
              <div v-if="fetchingZones" class="text-xs text-muted mt-1">Loading zones...</div>
            </div>
          </div>

          <div v-if="error" class="text-danger mt-4">{{ error }}</div>

          <div class="form-actions mt-8 flex justify-end gap-4">
            <router-link to="/communities" class="btn btn-secondary" style="text-decoration: none;">Cancel</router-link>
            <button type="submit" class="btn btn-primary" :disabled="loading">
              {{ loading ? 'Saving...' : 'Save Changes' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import CommunityService from '../services/CommunityService';
import ZoneService from '../../zones/services/ZoneService';

const props = defineProps({
  id: {
    type: [String, Number],
    required: true
  }
});

const router = useRouter();
const form = ref({
  name: '',
  zone_id: ''
});
const loadingInitial = ref(true);
const loading = ref(false);
const error = ref(null);

const zones = ref([]);
const fetchingZones = ref(true);

const fetchZones = async () => {
  try {
    const response = await ZoneService.getAll();
    zones.value = response?.data ?? response ?? [];
  } catch (e) {
    console.error('Failed to load zones:', e);
  } finally {
    fetchingZones.value = false;
  }
};

const fetchCommunity = async () => {
  try {
    const data = await CommunityService.getById(props.id);
    form.value.name = data.name;
    form.value.zone_id = data.zone_id;
  } catch (e) {
    error.value = 'Failed to load community details.';
    console.error(e);
  } finally {
    loadingInitial.value = false;
  }
};

const submitForm = async () => {
  loading.value = true;
  error.value = null;
  try {
    await CommunityService.update(props.id, form.value);
    router.push('/communities');
  } catch (e) {
    console.error('Failed to update community:', e);
    error.value = 'Failed to update community. Please check the inputs.';
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  await fetchZones();
  await fetchCommunity();
});
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
.py-8 {
  padding-top: 2rem;
  padding-bottom: 2rem;
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
.text-center {
  text-align: center;
}
.text-muted {
  color: var(--color-text-muted);
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
