<template>
  <div class="container fade-in" v-if="member">
    <div class="page-header">
      <div>
        <router-link to="/members" class="back-link">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          Back to Members
        </router-link>
      </div>
      <div class="flex gap-2">
        <router-link :to="`/members/${member.id}/edit`" class="btn btn-secondary">Edit Profile</router-link>
      </div>
    </div>

    <!-- Hero Profile Section -->
    <div class="glass-panel profile-hero mb-6">
      <div class="profile-header flex items-center gap-6">
        <div class="avatar-large">{{ getInitials(member.full_name) }}</div>
        <div>
          <h1 class="profile-name">{{ member.full_name }}</h1>
          <div class="profile-meta flex items-center gap-4 text-muted mt-2">
            <span class="flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              {{ member.phone || 'No phone provided' }}
            </span>
            <span>•</span>
            <span class="badge" :class="getStatusBadgeClass(member.employment_status)">
              {{ formatStatus(member.employment_status) }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Details Grid -->
    <div class="grid-2 gap-6">
      <!-- Personal Details -->
      <div class="card hover-lift">
        <h3 class="card-title">Personal Information</h3>
        <div class="detail-list mt-4">
          <div class="detail-item">
            <div class="detail-label">First Name</div>
            <div class="detail-value">{{ member.first_name }}</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Middle Name</div>
            <div class="detail-value">{{ member.middle_name || '-' }}</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Last Name</div>
            <div class="detail-value">{{ member.last_name }}</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Date of Birth</div>
            <div class="detail-value">{{ member.dob ? member.dob : '-' }}</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Gender</div>
            <div class="detail-value capitalize">{{ member.gender || '-' }}</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Marital Status</div>
            <div class="detail-value capitalize">{{ member.marital_status || '-' }}</div>
          </div>
        </div>
      </div>

      <!-- Professional & Church Details -->
      <div class="card hover-lift">
        <h3 class="card-title">Professional & Church Life</h3>
        <div class="detail-list mt-4">
          <div class="detail-item">
            <div class="detail-label">Church Position</div>
            <div class="detail-value">{{ member.position || 'Standard Member' }}</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Household</div>
            <div class="detail-value">
              <router-link v-if="member.household_id" :to="`/households/${member.household_id}`" class="text-primary hover:underline cursor-pointer">
                {{ householdName || 'Loading...' }}
              </router-link>
              <span v-else class="text-muted">None Assigned</span>
            </div>
          </div>
          <div class="detail-item full-width">
            <div class="detail-label">Employment Notes</div>
            <div class="detail-value note-box">{{ member.employment_notes || 'No notes provided.' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div v-else-if="loading" class="container text-center py-20 text-muted">
    Loading member profile...
  </div>
  <div v-else class="container text-center py-20 text-danger">
    Member not found.
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import MemberService from '../services/MemberService';
import HouseholdService from '../../households/services/HouseholdService';

const route = useRoute();
const member = ref(null);
const householdName = ref('');
const loading = ref(true);

onMounted(async () => {
  try {
    const data = await MemberService.getMember(route.params.id);
    member.value = data.data || data;
    
    if (member.value && member.value.household_id) {
      fetchHousehold(member.value.household_id);
    }
  } catch (error) {
    console.error("Failed to load member details", error);
  } finally {
    loading.value = false;
  }
});

const fetchHousehold = async (id) => {
  try {
    const data = await HouseholdService.getHousehold(id);
    householdName.value = data?.data?.name ?? data?.name ?? 'Unknown Household';
  } catch (error) {
    householdName.value = 'Unknown Household';
  }
};

const getInitials = (name) => {
  if (!name) return '??';
  const parts = name.split(' ');
  return parts.length > 1 
    ? (parts[0][0] + parts[parts.length-1][0]).toUpperCase()
    : parts[0].substring(0, 2).toUpperCase();
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
.capitalize { text-transform: capitalize; }
.cursor-pointer { cursor: pointer; }
.hover\:underline:hover { text-decoration: underline; }

.card-title {
  font-size: 1.25rem;
  border-bottom: 1px solid var(--color-border);
  padding-bottom: 0.75rem;
  margin-bottom: 1rem;
}
.detail-list {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
}
.detail-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}
.detail-item.full-width {
  grid-column: span 2;
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
.note-box {
  background-color: var(--color-bg);
  padding: 1rem;
  border-radius: 8px;
  font-weight: 400;
  font-size: 0.875rem;
  border: 1px solid var(--color-border);
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
