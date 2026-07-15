<template>
  <div class="container fade-in">
    <div class="page-header">
      <div>
        <h1 class="page-title">Members</h1>
        <p class="text-muted mt-2">Manage your congregation members</p>
      </div>
      <router-link to="/members/create" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Add Member
      </router-link>
    </div>

    <div class="glass-panel p-6">
      <div class="flex justify-between items-center mb-6">
        <div class="search-box">
          <input type="text" class="form-control" placeholder="Search members..." v-model="searchQuery">
        </div>
        <div class="filters flex gap-4">
          <select class="form-control" v-model="filterStatus">
            <option value="">All Statuses</option>
            <option value="employed">Employed</option>
            <option value="student">Student</option>
          </select>
        </div>
      </div>

      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Contact</th>
              <th>Status</th>
              <th>Position</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="5" class="text-center py-8 text-muted">Loading members...</td>
            </tr>
            <tr v-else-if="filteredMembers.length === 0">
              <td colspan="5" class="text-center py-8 text-muted">No members found.</td>
            </tr>
            <tr v-else v-for="member in filteredMembers" :key="member.id">
              <td>
                <div class="flex items-center gap-4">
                  <div class="avatar">{{ getInitials(member.full_name) }}</div>
                  <div>
                    <div class="font-medium">{{ member.full_name }}</div>
                    <div class="text-xs text-muted">{{ member.gender || 'Unknown' }} • {{ calculateAge(member.dob) }}</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="text-sm">{{ member.phone || 'N/A' }}</div>
              </td>
              <td>
                <span class="badge" :class="getStatusBadgeClass(member.employment_status)">
                  {{ formatStatus(member.employment_status) }}
                </span>
              </td>
              <td>
                <div class="text-sm">{{ member.position || 'Member' }}</div>
              </td>
              <td>
                <div class="flex gap-2">
                  <router-link :to="`/members/${member.id}`" class="btn btn-icon" title="View">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                  </router-link>
                  <button class="btn btn-icon" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                  </button>
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
import { ref, computed, onMounted } from 'vue';
import MemberService from '../services/MemberService';

const members = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const filterStatus = ref('');

onMounted(async () => {
  try {
    const data = await MemberService.getMembers();
    members.value = data.data || data; // handle typical laravel pagination wrap if present
  } catch (error) {
    console.error("Failed to load members", error);
  } finally {
    loading.value = false;
  }
});

const filteredMembers = computed(() => {
  return members.value.filter(member => {
    const matchesSearch = (member.full_name || '').toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesStatus = filterStatus.value ? member.employment_status === filterStatus.value : true;
    return matchesSearch && matchesStatus;
  });
});

const getInitials = (name) => {
  if (!name) return '??';
  const parts = name.split(' ');
  return parts.length > 1 
    ? (parts[0][0] + parts[parts.length-1][0]).toUpperCase()
    : parts[0].substring(0, 2).toUpperCase();
};

const calculateAge = (dob) => {
  if (!dob) return 'Age unknown';
  const diff = Date.now() - new Date(dob).getTime();
  const ageDate = new Date(diff);
  return Math.abs(ageDate.getUTCFullYear() - 1970) + ' yrs';
};

const formatStatus = (status) => {
  if (!status) return 'Unspecified';
  return status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ');
};

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'employed': return 'badge-success';
    case 'student': return 'badge-primary';
    case 'unemployed': return 'badge-warning';
    default: return 'badge-neutral';
  }
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
</style>
