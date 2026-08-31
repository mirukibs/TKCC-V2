<template>
  <div class="container fade-in">
    <div class="page-header">
      <div>
        <router-link to="/sacraments" class="back-link">← Back to Sacraments</router-link>
        <h1 class="page-title mt-2">Add Sacrament</h1>
        <p class="text-muted mt-1">Record a new sacrament for a member</p>
      </div>
    </div>

    <div class="glass-panel p-8 max-w-3xl">
      <form @submit.prevent="submitForm" class="form-layout">
        <div v-if="error" class="alert alert-error mb-6">
          {{ error }}
        </div>

        <div class="form-section">
          <h2 class="section-title">Member Selection</h2>
          <div class="form-group">
            <label class="form-label">Member <span class="required">*</span></label>
            <select v-model="formData.member_id" class="form-control" required :disabled="loadingMembers">
              <option value="" disabled>Select a member</option>
              <option v-for="member in members" :key="member.id" :value="member.id">
                {{ member.name || member.full_name || (member.first_name + ' ' + member.last_name) }}
              </option>
            </select>
            <div v-if="loadingMembers" class="text-xs text-muted mt-1">Loading members...</div>
          </div>
        </div>

        <div class="form-section mt-8">
          <h2 class="section-title">Baptism Details</h2>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Status <span class="required">*</span></label>
              <select v-model="formData.baptism_status" class="form-control" required>
                <option value="not_baptized">Not Baptized (Hajabatizwa)</option>
                <option value="baptized">Baptized (Amebatizwa)</option>
              </select>
            </div>
            
            <template v-if="formData.baptism_status === 'baptized'">
              <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" v-model="formData.baptism_date" class="form-control">
              </div>
              <div class="form-group">
                <label class="form-label">Place (Parish/Church)</label>
                <input type="text" v-model="formData.baptism_place" class="form-control" placeholder="e.g. St. Joseph, Dar es Salaam">
              </div>
            </template>
          </div>
        </div>

        <div class="form-section mt-8">
          <h2 class="section-title">Confirmation Details</h2>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Status <span class="required">*</span></label>
              <select v-model="formData.confirmation_status" class="form-control" required>
                <option value="not_confirmed">Not Confirmed (Hajapewa Kipaimara)</option>
                <option value="confirmed">Confirmed (Amepewa Kipaimara)</option>
              </select>
            </div>
            
            <template v-if="formData.confirmation_status === 'confirmed'">
              <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" v-model="formData.confirmation_date" class="form-control">
              </div>
              <div class="form-group">
                <label class="form-label">Place (Parish/Church)</label>
                <input type="text" v-model="formData.confirmation_place" class="form-control" placeholder="e.g. St. Peter, Dodoma">
              </div>
            </template>
          </div>
        </div>
        
        <div class="form-section mt-8">
          <h2 class="section-title">Marriage Details</h2>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Status <span class="required">*</span></label>
              <select v-model="formData.marriage_status" class="form-control" required>
                <option value="single">Single (Hajaoa/Hajaolewa)</option>
                <option value="married">Married (Ameoa/Ameolewa)</option>
              </select>
            </div>
            
            <template v-if="formData.marriage_status === 'married'">
              <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" v-model="formData.marriage_date" class="form-control">
              </div>
              <div class="form-group">
                <label class="form-label">Place (Parish/Church)</label>
                <input type="text" v-model="formData.marriage_place" class="form-control" placeholder="e.g. Holy Spirit, Arusha">
              </div>
            </template>
          </div>
        </div>

        <div class="form-actions mt-8 pt-6 border-t border-gray-100 flex justify-end gap-4">
          <router-link to="/sacraments" class="btn btn-secondary">Cancel</router-link>
          <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
            {{ isSubmitting ? 'Saving...' : 'Save Sacrament' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import SacramentService from '../services/SacramentService';
import MemberService from '@/modules/members/services/MemberService';

const router = useRouter();
const isSubmitting = ref(false);
const error = ref('');
const members = ref([]);
const loadingMembers = ref(true);

const formData = ref({
  member_id: '',
  baptism_status: 'not_baptized',
  baptism_date: '',
  baptism_place: '',
  confirmation_status: 'not_confirmed',
  confirmation_date: '',
  confirmation_place: '',
  marriage_status: 'single',
  marriage_date: '',
  marriage_place: '',
});

onMounted(async () => {
  try {
    const response = await MemberService.getMembers({ per_page: 1000 }); // fetch enough members for the dropdown
    members.value = response?.data ?? response ?? [];
  } catch (err) {
    console.error('Failed to load members', err);
    error.value = 'Failed to load members list for the dropdown.';
  } finally {
    loadingMembers.value = false;
  }
});

const submitForm = async () => {
  if (!formData.value.member_id) {
    error.value = 'Please select a member.';
    return;
  }
  
  isSubmitting.value = true;
  error.value = '';

  try {
    await SacramentService.create(formData.value);
    router.push('/sacraments');
  } catch (err) {
    console.error('Failed to create sacrament:', err);
    error.value = err.response?.data?.message || 'Failed to create sacrament. Please check the inputs.';
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
.max-w-3xl { max-width: 48rem; margin: 0 auto; }
.form-layout { display: flex; flex-direction: column; }
.form-section { background: rgba(255, 255, 255, 0.5); padding: 1.5rem; border-radius: 12px; }
.section-title { font-size: 1.125rem; font-weight: 600; margin-bottom: 1.25rem; color: var(--color-text); }
.form-grid { display: grid; grid-template-columns: 1fr; gap: 1.25rem; }
@media (min-width: 640px) { .form-grid { grid-template-columns: repeat(2, 1fr); } }
.back-link { color: var(--color-primary); text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: color var(--transition-fast); }
.back-link:hover { color: var(--color-primary-hover); }
.alert-error { background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; border-left: 4px solid #ef4444; }
.required { color: #ef4444; }
</style>
