<template>
  <div class="container fade-in">
    <div class="page-header">
      <div>
        <router-link to="/households" class="back-link">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          Back to Households
        </router-link>
        <h1 class="page-title mt-2">Edit Household</h1>
      </div>
    </div>

    <div class="glass-panel p-8 max-w-3xl mx-auto">
      <form @submit.prevent="submitForm">
        
        <div class="form-section">
          <h3 class="section-title">Household Information</h3>
          
          <div class="grid-2">
            <div class="form-group">
              <label class="form-label">Household Name *</label>
              <input type="text" class="form-control" v-model="form.name" required>
            </div>
            
            <div class="form-group">
              <label class="form-label">Community *</label>
              <select class="form-control" v-model="form.community_id" required>
                <option value="" disabled>-- Select Community --</option>
                <option v-for="community in communities" :key="community.id" :value="community.id">
                  {{ community.name }}
                </option>
              </select>
            </div>
          </div>

          <div class="grid-2">
            <div class="form-group">
              <label class="form-label">Leader</label>
              <select id="leader_id" class="form-control" v-model="form.leader_id">
                <option value="">-- No Leader Assigned --</option>
                <option v-for="member in members" :key="member.id" :value="member.id">
                  {{ member.first_name }} {{ member.last_name }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Ownership Type *</label>
              <select class="form-control" v-model="form.ownership" required>
                <option value="">Select Ownership</option>
                <option value="owner">Owner</option>
                <option value="tenant">Tenant</option>
                <option value="family">Family Member</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-actions mt-8 flex justify-end gap-4">
          <router-link to="/households" class="btn btn-secondary">Cancel</router-link>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            <span v-if="loading">Saving...</span>
            <span v-else>Save Changes</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import HouseholdService from '../services/HouseholdService';
import CommunityService from '../../communities/services/CommunityService';
import MemberService from '../../members/services/MemberService';

const router = useRouter();
const route = useRoute();
const loading = ref(false);
const householdId = route.params.id;
const communities = ref([]);
const members = ref([]);

const form = ref({
  name: '',
  community_id: '',
  leader_id: '',
  ownership: ''
});

onMounted(async () => {
  try {
    const data = await HouseholdService.getHousehold(householdId);
    const household = data?.data ?? data;
    if (household) {
      form.value = {
        name: household.name || '',
        community_id: household.community_id || '',
        leader_id: household.leader_id || '',
        ownership: household.ownership || ''
      };
    }
  } catch (error) {
    console.error('Failed to load household', error);
  }

  try {
    communities.value = await CommunityService.getAll();
  } catch (error) {
    console.error('Failed to load communities', error);
  }

  try {
    const response = await MemberService.getMembers();
    members.value = response?.data ?? response ?? [];
  } catch (error) {
    console.error('Failed to load members', error);
  }
});

const submitForm = async () => {
  loading.value = true;
  try {
    const payload = Object.fromEntries(
      Object.entries(form.value).map(([k, v]) => [k, v === '' ? null : v])
    );
    
    await HouseholdService.updateHousehold(householdId, payload);
    router.push('/households');
  } catch (error) {
    console.error('Failed to update household:', error);
    alert('Failed to update household. Please check the inputs.');
  } finally {
    loading.value = false;
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
.max-w-3xl {
  max-width: 48rem;
}
.mx-auto {
  margin-left: auto;
  margin-right: auto;
}
.p-8 {
  padding: 2rem;
}
.mt-2 {
  margin-top: 0.5rem;
}
.mt-8 {
  margin-top: 2rem;
}
.section-title {
  font-size: 1.125rem;
  color: var(--color-primary);
  border-bottom: 1px solid var(--color-border);
  padding-bottom: 0.5rem;
  margin-bottom: 1.5rem;
}
.grid-2 {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 1rem;
}
@media (min-width: 640px) {
  .grid-2 {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
}
.form-actions {
  border-top: 1px solid var(--color-border);
  padding-top: 1.5rem;
}
</style>
