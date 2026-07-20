<template>
  <div class="container fade-in">
    <div class="max-w-3xl mx-auto">
      <div class="mb-6">
        <router-link to="/households" class="back-link" style="text-decoration: none;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          Back to Households
        </router-link>
        <h1 class="page-title mt-2">Create New Household</h1>
      </div>

      <div class="glass-panel p-8">
        <form @submit.prevent="submitForm">
          <h2 class="section-title">Household Information</h2>
          <div class="grid-2">
            <div class="form-group">
              <label for="name" class="form-label">Household Name <span class="text-danger">*</span></label>
              <input type="text" id="name" class="form-control" v-model="formData.name" required placeholder="e.g. Baba John's Family" />
            </div>

            <div class="form-group">
              <label for="community_id" class="form-label">Community ID <span class="text-danger">*</span></label>
              <input type="number" id="community_id" class="form-control" v-model="formData.community_id" required placeholder="e.g. 1" />
            </div>

            <div class="form-group">
              <label for="leader_id" class="form-label">Leader</label>
              <select id="leader_id" class="form-control" v-model="formData.leader_id">
                <option value="">-- No Leader Assigned --</option>
                <option v-for="member in members" :key="member.id" :value="member.id">
                  {{ member.first_name }} {{ member.last_name }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label for="ownership" class="form-label">Ownership Type <span class="text-danger">*</span></label>
              <select id="ownership" class="form-control" v-model="formData.ownership" required>
                <option value="owned">Owned</option>
                <option value="rented">Rented</option>
              </select>
            </div>
          </div>

          <div class="form-actions mt-8 flex justify-end gap-4">
            <router-link to="/households" class="btn btn-secondary" style="text-decoration: none;">Cancel</router-link>
            <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
              {{ isSubmitting ? 'Saving...' : 'Save Household' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import HouseholdService from '../services/HouseholdService';
import MemberService from '../../members/services/MemberService';

const router = useRouter();
const isSubmitting = ref(false);
const members = ref([]);

const formData = ref({
  name: '',
  community_id: '',
  leader_id: '',
  ownership: 'owned'
});

const fetchMembers = async () => {
  try {
    const response = await MemberService.getMembers();
    members.value = response.data || response || [];
  } catch (error) {
    console.error('Failed to fetch members for dropdown:', error);
  }
};

const submitForm = async () => {
  isSubmitting.value = true;
  try {
    const payload = { ...formData.value };
    if (!payload.leader_id) {
      payload.leader_id = null;
    }
    
    await HouseholdService.createHousehold(payload);
    router.push('/households');
  } catch (error) {
    console.error('Failed to create household:', error);
    alert('Failed to create household. Check console for details.');
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(() => {
  fetchMembers();
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

