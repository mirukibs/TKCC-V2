<template>
  <div class="container fade-in">
    <div class="page-header">
      <div>
        <h1 class="page-title">Communities</h1>
        <p class="text-muted mt-2">Manage church communities</p>
      </div>
      <router-link to="/communities/create" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Add Community
      </router-link>
    </div>

    <div class="glass-panel p-6">
      <div class="flex justify-between items-center mb-6">
        <div class="search-box">
          <input type="text" class="form-control" placeholder="Search communities..." v-model="searchQuery">
        </div>
      </div>

      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Zone</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="3" class="text-center py-8 text-muted">Loading communities...</td>
            </tr>
            <tr v-else-if="filteredCommunities.length === 0">
              <td colspan="3" class="text-center py-8 text-muted">No communities found.</td>
            </tr>
            <tr v-else v-for="community in filteredCommunities" :key="community.id">
              <td>
                <div class="flex items-center gap-4">
                  <div class="avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                  </div>
                  <div>
                    <div class="font-medium">{{ community.name }}</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="text-sm">{{ getZoneName(community.zone_id) }}</div>
              </td>
              <td>
                <div class="flex gap-2">
                  <router-link :to="`/communities/${community.id}`" class="btn btn-icon" title="View">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                  </router-link>
                  <router-link :to="`/communities/${community.id}/edit`" class="btn btn-icon" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                  </router-link>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import CommunityService from '../services/CommunityService';
import ZoneService from '../../zones/services/ZoneService';

const communities = ref([]);
const zones = ref([]);
const loading = ref(true);
const searchQuery = ref('');
let debounceTimeout = null;

const fetchCommunities = async () => {
  loading.value = true;
  try {
    const params = {};
    if (searchQuery.value) params.search = searchQuery.value;
    
    const [communityResponse, zoneResponse] = await Promise.all([
      CommunityService.getAll(),
      ZoneService.getAll()
    ]);
    communities.value = communityResponse?.data ?? communityResponse ?? [];
    zones.value = zoneResponse?.data ?? zoneResponse ?? [];
  } catch (error) {
    console.error('Failed to fetch communities:', error);
    communities.value = [];
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchCommunities();
});

watch(searchQuery, () => {
  clearTimeout(debounceTimeout);
  debounceTimeout = setTimeout(() => {
    fetchCommunities();
  }, 300);
});

const filteredCommunities = computed(() => {
  if (!searchQuery.value) return communities.value;
  const q = searchQuery.value.toLowerCase();
  return communities.value.filter(c => c.name && c.name.toLowerCase().includes(q));
});

const getZoneName = (zoneId) => {
  const zone = zones.value.find(z => z.id === zoneId);
  return zone ? zone.name : 'Unknown Zone';
};
</script>

<style scoped>
.text-muted {
  color: var(--color-text-muted);
}
.font-medium {
  font-weight: 500;
}
.text-xs {
  font-size: 0.75rem;
}
.text-sm {
  font-size: 0.875rem;
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
.search-box {
  width: 300px;
}
.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: var(--color-primary-light);
  color: var(--color-primary-hover);
  display: flex;
  align-items: center;
  justify-content: center;
}
.btn-icon {
  background: transparent;
  border: none;
  padding: 0.5rem;
  border-radius: 6px;
  color: var(--color-text-muted);
  cursor: pointer;
  transition: all var(--transition-fast);
}
.btn-icon:hover {
  background: var(--color-bg);
  color: var(--color-primary);
}
</style>
