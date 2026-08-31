<template>
  <div class="container fade-in" v-if="community">
    <div class="page-header">
      <div>
        <router-link to="/communities" class="back-link">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          Back to Communities
        </router-link>
      </div>
      <div class="flex gap-2">
        <router-link :to="`/communities/${community.id}/edit`" class="btn btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
          Edit Community
        </router-link>
      </div>
    </div>

    <!-- Hero Section -->
    <div class="glass-panel profile-hero mb-6">
      <div class="profile-header flex items-center gap-6">
        <div class="avatar-large">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div>
          <h1 class="profile-name">{{ community.name }}</h1>
          <div class="profile-meta flex items-center gap-4 text-muted mt-2">
            <!-- IDs removed for better UX -->
          </div>
        </div>
      </div>
    </div>

    <!-- Details Grid -->
    <div class="grid-2 gap-6">
      <!-- Main Details Card -->
      <div class="card hover-lift">
        <h3 class="card-title">Community Information</h3>
        <div class="detail-list mt-4">
          <div class="detail-item">
            <div class="detail-label">Zone</div>
            <div class="detail-value">{{ zoneName || 'Loading...' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div v-else-if="loading" class="container text-center py-20 text-muted">
    Loading community details...
  </div>
  
  <div v-else class="container text-center py-20 text-danger">
    <p>{{ error || 'Community not found.' }}</p>
    <button class="btn btn-secondary mt-4" @click="$router.push('/communities')">Go Back</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import CommunityService from '../services/CommunityService';
import ZoneService from '../../zones/services/ZoneService';

const props = defineProps({
  id: {
    type: [String, Number],
    required: true
  }
});

const community = ref(null);
const loading = ref(true);
const error = ref(null);
const zoneName = ref('');

const fetchCommunity = async () => {
  try {
    loading.value = true;
    const data = await CommunityService.getById(props.id);
    community.value = data;
    if (data && data.zone_id) {
      try {
        const zoneData = await ZoneService.getById(data.zone_id);
        zoneName.value = zoneData.name;
      } catch (ze) {
        zoneName.value = 'Unknown Zone';
      }
    }
  } catch (e) {
    error.value = 'Failed to load community details.';
    console.error(e);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchCommunity();
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
  text-decoration: none;
}
.back-link:hover {
  color: var(--color-primary);
}
.profile-hero {
  padding: 2.5rem;
  background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.6));
}
.avatar-large {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--color-primary), var(--color-primary-hover));
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 2rem;
  box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}
.profile-name {
  font-size: 2.25rem;
  margin: 0;
  letter-spacing: -0.025em;
}
.text-muted { color: var(--color-text-muted); }
.text-primary { color: var(--color-primary); }
.text-danger { color: var(--color-danger); }
.text-center { text-align: center; }
.py-20 { padding-top: 5rem; padding-bottom: 5rem; }

.card-title {
  font-size: 1.25rem;
  border-bottom: 1px solid var(--color-border);
  padding-bottom: 0.75rem;
  margin-bottom: 1rem;
}
.detail-list {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 1.5rem;
}
.detail-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}
.detail-label {
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--color-text-muted);
}
.detail-value {
  font-size: 1rem;
  color: var(--color-text);
  font-weight: 500;
}
.grid-2 {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
}
@media (min-width: 768px) {
  .grid-2 {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
