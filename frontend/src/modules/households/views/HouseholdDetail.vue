<template>
  <div class="container fade-in" v-if="household">
    <div class="page-header">
      <div>
        <router-link to="/households" class="back-link">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          Back to Households
        </router-link>
      </div>
      <div class="flex gap-2">
        <!-- We can add Edit Household button here later if needed -->
      </div>
    </div>

    <!-- Hero Section -->
    <div class="glass-panel profile-hero mb-6">
      <div class="profile-header flex items-center gap-6">
        <div class="avatar-large">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        </div>
        <div>
          <h1 class="profile-name">{{ household.name }}</h1>
          <div class="profile-meta flex items-center gap-4 text-muted mt-2">
            <span class="flex items-center gap-1">
              Community #{{ household.community_id }}
            </span>
            <span>•</span>
            <span class="badge" :class="household.ownership === 'owned' ? 'badge-success' : 'badge-warning'">
              {{ capitalize(household.ownership) }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Details Grid -->
    <div class="grid-2 gap-6">
      <!-- Main Details Card -->
      <div class="card hover-lift">
        <h3 class="card-title">Household Information</h3>
        <div class="detail-list mt-4">
          <div class="detail-item">
            <div class="detail-label">Household ID</div>
            <div class="detail-value">#{{ household.id }}</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Community (Jumuiya)</div>
            <div class="detail-value">{{ household.community_id }}</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Ownership Type</div>
            <div class="detail-value">{{ capitalize(household.ownership) }}</div>
          </div>
        </div>
      </div>

      <!-- Leader Card -->
      <div class="card hover-lift">
        <h3 class="card-title">Leadership</h3>
        <div v-if="leader" class="flex items-center gap-4 mt-4">
          <div class="leader-avatar">
            {{ leader.first_name.charAt(0) }}{{ leader.last_name.charAt(0) }}
          </div>
          <div>
            <div class="detail-value">{{ leader.full_name }}</div>
            <div class="text-sm text-muted mb-2">{{ leader.phone || 'No phone provided' }}</div>
            <router-link :to="`/members/${leader.id}`" class="text-primary text-sm hover:underline font-medium">View Profile &rarr;</router-link>
          </div>
        </div>
        <div v-else class="mt-4 text-muted">
          No leader assigned to this household.
        </div>
      </div>
    </div>
  </div>
  
  <div v-else-if="loading" class="container text-center py-20 text-muted">
    Loading household details...
  </div>
  
  <div v-else class="container text-center py-20 text-danger">
    <p>Household not found.</p>
    <button class="btn btn-secondary mt-4" @click="$router.push('/households')">Go Back</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import HouseholdService from '../services/HouseholdService';
import MemberService from '../../members/services/MemberService';

const route = useRoute();
const household = ref(null);
const leader = ref(null);
const loading = ref(true);

const fetchHousehold = async () => {
  try {
    const id = route.params.id;
    const data = await HouseholdService.getHousehold(id);
    const resolvedData = data.data || data;
    household.value = resolvedData;
    
    if (resolvedData.leader_id) {
      await fetchLeader(resolvedData.leader_id);
    }
  } catch (error) {
    console.error('Failed to fetch household details:', error);
  } finally {
    loading.value = false;
  }
};

const fetchLeader = async (leaderId) => {
  try {
    const data = await MemberService.getMember(leaderId);
    leader.value = data.data || data;
  } catch (error) {
    console.error('Failed to fetch leader details:', error);
  }
};

const capitalize = (str) => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
};

onMounted(() => {
  fetchHousehold();
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
.leader-avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--color-primary), var(--color-primary-hover));
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 1.25rem;
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
