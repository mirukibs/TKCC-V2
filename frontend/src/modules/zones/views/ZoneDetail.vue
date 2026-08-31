<template>
  <div class="container fade-in">
    <div class="page-header">
      <div>
        <h1 class="page-title">Zone Details</h1>
        <p class="text-muted mt-2">View zone information</p>
      </div>
      <div class="flex gap-2">
        <router-link to="/zones" class="btn btn-secondary">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          Back
        </router-link>
        <router-link :to="`/zones/${zoneId}/edit`" class="btn btn-primary" v-if="!loading && zone">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
          Edit
        </router-link>
      </div>
    </div>

    <div v-if="loading" class="glass-panel p-8 text-center text-muted">
      Loading zone details...
    </div>

    <div v-else-if="!zone" class="glass-panel p-8 text-center text-muted">
      Zone not found.
    </div>

    <div v-else class="grid-2">
      <!-- Profile Card -->
      <div class="glass-panel p-6 profile-card">
        <div class="profile-header">
          <div class="avatar large">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
          </div>
          <h2 class="profile-name">{{ zone.name }}</h2>
          <div class="status-badge active">Active Zone</div>
        </div>
        
        <div class="divider"></div>
        
        <!-- Detail list removed since no IDs are shown -->
      </div>
      
      <!-- Placeholder for associated items like Communities -->
      <div class="glass-panel p-6">
        <h3 class="section-title">Associated Communities</h3>
        <p class="text-muted mt-4 text-sm">
          This section can later display the list of communities belonging to this zone.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import ZoneService from '../services/ZoneService';

const route = useRoute();
const zoneId = route.params.id;
const zone = ref(null);
const loading = ref(true);

onMounted(async () => {
  try {
    const response = await ZoneService.getById(zoneId);
    zone.value = response?.data ?? response;
  } catch (error) {
    console.error('Failed to load zone:', error);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.text-muted {
  color: var(--color-text-muted);
}
.text-center {
  text-align: center;
}
.text-sm {
  font-size: 0.875rem;
}
.p-8 {
  padding: 2rem;
}
.p-6 {
  padding: 1.5rem;
}
.mt-4 {
  margin-top: 1rem;
}
.flex {
  display: flex;
}
.gap-2 {
  gap: 0.5rem;
}

/* Profile Card Styles */
.profile-card {
  display: flex;
  flex-direction: column;
}

.profile-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  margin-bottom: 1.5rem;
}

.avatar.large {
  width: 96px;
  height: 96px;
  border-radius: 50%;
  background-color: var(--color-primary-light);
  color: var(--color-primary);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1rem;
}

.profile-name {
  font-size: 1.5rem;
  font-weight: 600;
  margin: 0 0 0.5rem 0;
  color: var(--color-text);
}

.status-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.status-badge.active {
  background-color: rgba(16, 185, 129, 0.1);
  color: #10b981;
}

.divider {
  height: 1px;
  background-color: var(--color-border);
  margin: 1.5rem 0;
}

.detail-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.detail-label {
  color: var(--color-text-muted);
  font-size: 0.875rem;
}

.detail-value {
  font-weight: 500;
  color: var(--color-text);
}

.section-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0 0 1rem 0;
  color: var(--color-text);
}
</style>
