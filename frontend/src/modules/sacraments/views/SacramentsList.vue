<template>
  <div class="container fade-in">
    <div class="page-header">
      <div>
        <h1 class="page-title">Sacraments</h1>
        <p class="text-muted mt-2">Manage member sacraments</p>
      </div>
      <router-link to="/sacraments/create" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Add Sacrament
      </router-link>
    </div>

    <div class="glass-panel p-6">
      <div class="flex justify-between items-center mb-6">
        <div class="search-box">
          <input type="text" class="form-control" placeholder="Search by member name..." v-model="searchQuery">
        </div>
      </div>

      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>Member Name</th>
              <th>Baptism</th>
              <th>Confirmation</th>
              <th>Marriage</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="5" class="text-center py-8 text-muted">Loading sacraments...</td>
            </tr>
            <tr v-else-if="filteredSacraments.length === 0">
              <td colspan="5" class="text-center py-8 text-muted">No sacraments found.</td>
            </tr>
            <tr v-else v-for="sacrament in filteredSacraments" :key="sacrament.id">
              <td>
                <div class="flex items-center gap-4">
                  <div class="avatar">{{ getInitials(sacrament.member?.name) }}</div>
                  <div>
                    <div class="font-medium">{{ sacrament.member?.name || 'Unknown' }}</div>
                  </div>
                </div>
              </td>
              <td>
                <span class="badge" :class="sacrament.baptism_status === 'baptized' ? 'badge-success' : 'badge-neutral'">
                  {{ formatStatus(sacrament.baptism_status) }}
                </span>
                <div v-if="sacrament.baptism_date" class="text-xs text-muted mt-1">{{ sacrament.baptism_date }}</div>
              </td>
              <td>
                <span class="badge" :class="sacrament.confirmation_status === 'confirmed' ? 'badge-primary' : 'badge-neutral'">
                  {{ formatStatus(sacrament.confirmation_status) }}
                </span>
                <div v-if="sacrament.confirmation_date" class="text-xs text-muted mt-1">{{ sacrament.confirmation_date }}</div>
              </td>
              <td>
                <span class="badge" :class="sacrament.marriage_status === 'married' ? 'badge-secondary' : 'badge-neutral'">
                  {{ formatStatus(sacrament.marriage_status) }}
                </span>
                <div v-if="sacrament.marriage_date" class="text-xs text-muted mt-1">{{ sacrament.marriage_date }}</div>
              </td>
              <td>
                <div class="flex gap-2">
                  <router-link :to="`/sacraments/${sacrament.id}`" class="btn btn-icon" title="View">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                  </router-link>
                  <router-link :to="`/sacraments/${sacrament.id}/edit`" class="btn btn-icon" title="Edit">
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
import SacramentService from '../services/SacramentService';

const sacraments = ref([]);
const loading = ref(true);
const searchQuery = ref('');
let debounceTimeout = null;

const fetchSacraments = async () => {
  loading.value = true;
  try {
    const params = {};
    if (searchQuery.value) params.search = searchQuery.value;
    
    const data = await SacramentService.getAll(params);
    sacraments.value = data?.data ?? [];
  } catch (error) {
    console.error("Failed to load sacraments", error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchSacraments();
});

watch(searchQuery, () => {
  clearTimeout(debounceTimeout);
  debounceTimeout = setTimeout(() => {
    fetchSacraments();
  }, 300);
});

const filteredSacraments = computed(() => {
  return sacraments.value;
});

const getInitials = (name) => {
  if (!name) return '??';
  const parts = name.split(' ');
  return parts.length > 1 
    ? (parts[0][0] + parts[parts.length-1][0]).toUpperCase()
    : parts[0].substring(0, 2).toUpperCase();
};

const formatStatus = (status) => {
  if (!status) return 'Unknown';
  return status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ');
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
  font-weight: 600;
  font-size: 0.875rem;
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
.badge-secondary {
  background-color: #f3e8ff;
  color: #7e22ce;
}
</style>
