<template>
  <div class="container fade-in">
    <div class="page-header flex justify-between items-start">
      <div>
        <router-link to="/sacraments" class="back-link">← Back to Sacraments</router-link>
        <h1 class="page-title mt-2">Sacrament Details</h1>
      </div>
      <div v-if="!loading && sacrament" class="flex gap-4">
        <router-link :to="`/sacraments/${sacrament.id}/edit`" class="btn btn-primary">
          Edit Details
        </router-link>
        <button @click="confirmDelete" class="btn btn-danger">
          Delete
        </button>
      </div>
    </div>

    <div v-if="loading" class="text-center py-12 text-muted">
      Loading details...
    </div>
    
    <div v-else-if="error" class="alert alert-error">
      {{ error }}
    </div>

    <div v-else class="glass-panel p-8">
      <div class="mb-8 border-b border-gray-100 pb-6 flex items-center gap-6">
        <div class="avatar-large">{{ getInitials(sacrament.member?.name) }}</div>
        <div>
          <h2 class="text-2xl font-bold">{{ sacrament.member?.name || 'Unknown Member' }}</h2>
          <p class="text-muted mt-1">Member ID: {{ sacrament.member_id }}</p>
        </div>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Baptism Section -->
        <div class="detail-card">
          <div class="detail-header">
            <h3>Baptism</h3>
            <span class="badge" :class="sacrament.baptism_status === 'baptized' ? 'badge-success' : 'badge-neutral'">
              {{ formatStatus(sacrament.baptism_status) }}
            </span>
          </div>
          <div class="detail-body" v-if="sacrament.baptism_status === 'baptized'">
            <div class="detail-row">
              <span class="detail-label">Date</span>
              <span class="detail-value">{{ formatDate(sacrament.baptism_date) }}</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Place</span>
              <span class="detail-value">{{ sacrament.baptism_place || 'Not specified' }}</span>
            </div>
          </div>
          <div class="detail-body text-center text-muted py-4" v-else>
            No baptism details recorded.
          </div>
        </div>

        <!-- Confirmation Section -->
        <div class="detail-card">
          <div class="detail-header">
            <h3>Confirmation</h3>
            <span class="badge" :class="sacrament.confirmation_status === 'confirmed' ? 'badge-primary' : 'badge-neutral'">
              {{ formatStatus(sacrament.confirmation_status) }}
            </span>
          </div>
          <div class="detail-body" v-if="sacrament.confirmation_status === 'confirmed'">
            <div class="detail-row">
              <span class="detail-label">Date</span>
              <span class="detail-value">{{ formatDate(sacrament.confirmation_date) }}</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Place</span>
              <span class="detail-value">{{ sacrament.confirmation_place || 'Not specified' }}</span>
            </div>
          </div>
          <div class="detail-body text-center text-muted py-4" v-else>
            No confirmation details recorded.
          </div>
        </div>
        
        <!-- Marriage Section -->
        <div class="detail-card">
          <div class="detail-header">
            <h3>Marriage</h3>
            <span class="badge" :class="sacrament.marriage_status === 'married' ? 'badge-secondary' : 'badge-neutral'">
              {{ formatStatus(sacrament.marriage_status) }}
            </span>
          </div>
          <div class="detail-body" v-if="sacrament.marriage_status === 'married'">
            <div class="detail-row">
              <span class="detail-label">Date</span>
              <span class="detail-value">{{ formatDate(sacrament.marriage_date) }}</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Place</span>
              <span class="detail-value">{{ sacrament.marriage_place || 'Not specified' }}</span>
            </div>
          </div>
          <div class="detail-body text-center text-muted py-4" v-else>
            No marriage details recorded.
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import SacramentService from '../services/SacramentService';

const route = useRoute();
const router = useRouter();
const sacrament = ref(null);
const loading = ref(true);
const error = ref('');

onMounted(async () => {
  try {
    const id = route.params.id;
    const response = await SacramentService.getById(id);
    sacrament.value = response.data;
  } catch (err) {
    console.error('Failed to load sacrament:', err);
    error.value = 'Failed to load sacrament details. It might have been deleted.';
  } finally {
    loading.value = false;
  }
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

const formatDate = (dateString) => {
  if (!dateString) return 'Not specified';
  const date = new Date(dateString);
  return date.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
};

const confirmDelete = async () => {
  if (confirm('Are you sure you want to delete this sacrament record?')) {
    try {
      await SacramentService.delete(sacrament.value.id);
      router.push('/sacraments');
    } catch (err) {
      console.error('Failed to delete sacrament:', err);
      alert('Failed to delete sacrament record.');
    }
  }
};
</script>

<style scoped>
.back-link { color: var(--color-primary); text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: color var(--transition-fast); }
.back-link:hover { color: var(--color-primary-hover); }
.alert-error { background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; border-left: 4px solid #ef4444; }
.btn-danger { background-color: #ef4444; color: white; border: none; padding: 0.5rem 1.25rem; border-radius: 6px; cursor: pointer; transition: all var(--transition-fast); font-weight: 500;}
.btn-danger:hover { background-color: #dc2626; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }

.avatar-large {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background-color: var(--color-primary-light);
  color: var(--color-primary-hover);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1.5rem;
}

.detail-card {
  background: rgba(255, 255, 255, 0.6);
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.8);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
  overflow: hidden;
}

.detail-header {
  padding: 1.25rem 1.5rem;
  background: rgba(255, 255, 255, 0.8);
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.detail-header h3 {
  margin: 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: var(--color-text);
}

.detail-body {
  padding: 1.5rem;
}

.detail-row {
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;
}

.detail-row:last-child {
  margin-bottom: 0;
}

.detail-label {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--color-text-muted);
  margin-bottom: 0.25rem;
  font-weight: 600;
}

.detail-value {
  font-size: 0.9375rem;
  color: var(--color-text);
  font-weight: 500;
}

.badge-secondary {
  background-color: #f3e8ff;
  color: #7e22ce;
}
</style>
