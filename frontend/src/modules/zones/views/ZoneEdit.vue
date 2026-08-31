<template>
  <div class="container fade-in">
    <div class="page-header">
      <div>
        <h1 class="page-title">Edit Zone</h1>
        <p class="text-muted mt-2">Update zone details</p>
      </div>
      <router-link to="/zones" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Back to List
      </router-link>
    </div>

    <div class="glass-panel p-6 max-w-2xl">
      <div v-if="fetching" class="text-center py-8 text-muted">
        Loading zone data...
      </div>
      <form v-else @submit.prevent="submitForm">
        <div class="form-group">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" v-model="form.name" required maxlength="150" placeholder="Enter zone name">
        </div>

        <div class="form-actions mt-6 flex justify-between items-center">
          <button type="button" class="btn btn-danger" @click="deleteZone" :disabled="deleting">
            {{ deleting ? 'Deleting...' : 'Delete Zone' }}
          </button>
          
          <button type="submit" class="btn btn-primary" :disabled="loading">
            <span v-if="loading">Saving...</span>
            <span v-else>Save Changes</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import ZoneService from '../services/ZoneService';

const route = useRoute();
const router = useRouter();
const loading = ref(false);
const fetching = ref(true);
const deleting = ref(false);

const form = ref({
  name: ''
});

const zoneId = route.params.id;

onMounted(async () => {
  try {
    const response = await ZoneService.getById(zoneId);
    const data = response?.data ?? response;
    form.value.name = data.name || '';
  } catch (error) {
    console.error('Failed to load zone:', error);
    alert('Failed to load zone data.');
    router.push('/zones');
  } finally {
    fetching.value = false;
  }
});

const submitForm = async () => {
  loading.value = true;
  try {
    await ZoneService.update(zoneId, form.value);
    router.push('/zones');
  } catch (error) {
    console.error('Error updating zone:', error);
    alert('Failed to update zone. Check console for details.');
  } finally {
    loading.value = false;
  }
};

const deleteZone = async () => {
  if (!confirm('Are you sure you want to delete this zone? This action cannot be undone.')) return;
  
  deleting.value = true;
  try {
    await ZoneService.delete(zoneId);
    router.push('/zones');
  } catch (error) {
    console.error('Failed to delete zone:', error);
    alert('Failed to delete zone.');
    deleting.value = false;
  }
};
</script>

<style scoped>
.text-muted {
  color: var(--color-text-muted);
}
.text-center {
  text-align: center;
}
.py-8 {
  padding-top: 2rem;
  padding-bottom: 2rem;
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
}
.flex {
  display: flex;
}
.justify-between {
  justify-content: space-between;
}
.items-center {
  align-items: center;
}
.btn-danger {
  background-color: transparent;
  color: #ef4444;
  border: 1px solid #ef4444;
}
.btn-danger:hover {
  background-color: #ef4444;
  color: white;
}
</style>
