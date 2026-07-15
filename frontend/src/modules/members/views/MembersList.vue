<template>
  <div>
    <div class="header-action">
      <h2>Members Directory</h2>
      <button class="btn btn-primary" @click="showAddMember = true">Add Member</button>
    </div>

    <div class="card">
      <div v-if="loading" class="loading">Loading members...</div>
      <div v-else-if="error" class="error">{{ error }}</div>
      <table v-else class="data-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>DOB</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="member in members" :key="member.id">
            <td>{{ member.name }}</td>
            <td>{{ member.phone || 'N/A' }}</td>
            <td>{{ member.gender || 'N/A' }}</td>
            <td>{{ member.dob || 'N/A' }}</td>
          </tr>
          <tr v-if="members.length === 0">
            <td colspan="4" class="empty-state">No members found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../../plugins/axios';

const members = ref([]);
const loading = ref(true);
const error = ref(null);
const showAddMember = ref(false);

const fetchMembers = async () => {
  try {
    const response = await api.get('/members');
    members.value = response.data;
  } catch (err) {
    error.value = 'Failed to load members.';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchMembers();
});
</script>

<style scoped>
.header-action {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th, .data-table td {
  padding: 0.75rem 1rem;
  text-align: left;
  border-bottom: 1px solid var(--color-border);
}

.data-table th {
  background-color: #F3F4F6;
  font-weight: 600;
  color: var(--color-text-muted);
}

.empty-state {
  text-align: center;
  color: var(--color-text-muted);
  padding: 2rem !important;
}

.loading, .error {
  text-align: center;
  padding: 2rem;
}
.error {
  color: #DC2626;
}
</style>
