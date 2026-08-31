<template>
  <div class="container fade-in">
    <div class="page-header">
      <div>
        <h1 class="page-title">Households</h1>
        <p class="text-muted mt-2">Manage church households and jumuiyas</p>
      </div>
      <router-link to="/households/create" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Add Household
      </router-link>
    </div>

    <div class="glass-panel p-6">
      <div class="flex justify-between items-center mb-6">
        <div class="search-box">
          <input type="text" class="form-control" placeholder="Search households..." v-model="searchQuery">
        </div>
        <div class="filters flex gap-4">
          <select class="form-control" v-model="filterOwnership">
            <option value="">All Ownership Types</option>
            <option value="owned">Owned</option>
            <option value="rented">Rented</option>
          </select>
        </div>
      </div>

      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Community</th>
              <th>Ownership</th>
              <th>Leader</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="5" class="text-center py-8 text-muted">Loading households...</td>
            </tr>
            <tr v-else-if="filteredHouseholds.length === 0">
              <td colspan="5" class="text-center py-8 text-muted">No households found.</td>
            </tr>
            <tr v-else v-for="household in filteredHouseholds" :key="household.id">
              <td>
                <div class="flex items-center gap-4">
                  <div class="avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                  </div>
                  <div>
                    <div class="font-medium">{{ household.name }}</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="text-sm">{{ getCommunityName(household.community_id) }}</div>
              </td>
              <td>
                <span class="badge" :class="household.ownership === 'owned' ? 'badge-success' : 'badge-warning'">
                  {{ capitalize(household.ownership) }}
                </span>
              </td>
              <td>
                <div class="text-sm">{{ getLeaderName(household.leader_id) }}</div>
              </td>
              <td>
                <div class="flex gap-2">
                  <router-link :to="`/households/${household.id}`" class="btn btn-icon" title="View">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                  </router-link>
                  <router-link :to="`/households/${household.id}/edit`" class="btn btn-icon" title="Edit">
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
import HouseholdService from '../services/HouseholdService';
import MemberService from '../../members/services/MemberService';
import CommunityService from '../../communities/services/CommunityService';

const households = ref([]);
const communities = ref([]);
const members = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const filterOwnership = ref('');
let debounceTimeout = null;

const fetchHouseholds = async () => {
  loading.value = true;
  try {
    const params = {};
    if (searchQuery.value) params.search = searchQuery.value;
    if (filterOwnership.value) params.ownership = filterOwnership.value;
    
    // Pass params for backend filtering when backend is ready
    const [householdsResponse, communitiesResponse, membersResponse] = await Promise.all([
      HouseholdService.getHouseholds(params),
      CommunityService.getAll(),
      MemberService.getMembers()
    ]);
    
    households.value = householdsResponse?.data ?? householdsResponse ?? [];
    communities.value = communitiesResponse?.data ?? communitiesResponse ?? [];
    members.value = membersResponse?.data ?? membersResponse ?? [];
  } catch (error) {
    console.error('Failed to fetch households:', error);
    households.value = [];
  } finally {
    loading.value = false;
  }
};

const capitalize = (str) => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
};

onMounted(() => {
  fetchHouseholds();
});

// Watchers to trigger fetch on search/filter changes with a debounce
watch([searchQuery, filterOwnership], () => {
  clearTimeout(debounceTimeout);
  debounceTimeout = setTimeout(() => {
    fetchHouseholds();
  }, 300); // 300ms debounce
});

// Fallback frontend computed property until backend filtering is fully working
const filteredHouseholds = computed(() => {
  return households.value; // The backend fetch handles the filtering
});

const getCommunityName = (id) => {
  const c = communities.value.find(x => x.id === id);
  return c ? c.name : 'Unknown Community';
};

const getLeaderName = (id) => {
  if (!id) return 'N/A';
  const m = members.value.find(x => x.id === id);
  return m ? m.first_name + ' ' + m.last_name : 'Unknown Leader';
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
