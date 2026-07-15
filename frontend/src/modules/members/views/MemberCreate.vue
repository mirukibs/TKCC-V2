<template>
  <div class="container fade-in">
    <div class="page-header">
      <div>
        <router-link to="/members" class="back-link">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          Back to Members
        </router-link>
        <h1 class="page-title mt-2">Register New Member</h1>
      </div>
    </div>

    <div class="glass-panel p-8 max-w-3xl mx-auto">
      <form @submit.prevent="submitForm">
        
        <div class="form-section">
          <h3 class="section-title">Personal Information</h3>
          
          <div class="grid-2">
            <div class="form-group">
              <label class="form-label">First Name *</label>
              <input type="text" class="form-control" v-model="form.first_name" required>
            </div>
            
            <div class="form-group">
              <label class="form-label">Last Name *</label>
              <input type="text" class="form-control" v-model="form.last_name" required>
            </div>
          </div>

          <div class="grid-2">
            <div class="form-group">
              <label class="form-label">Middle Name</label>
              <input type="text" class="form-control" v-model="form.middle_name">
            </div>

            <div class="form-group">
              <label class="form-label">Date of Birth</label>
              <input type="date" class="form-control" v-model="form.dob">
            </div>
          </div>
          
          <div class="grid-2">
            <div class="form-group">
              <label class="form-label">Gender</label>
              <select class="form-control" v-model="form.gender">
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Marital Status</label>
              <select class="form-control" v-model="form.marital_status">
                <option value="">Select Status</option>
                <option value="single">Single</option>
                <option value="married">Married</option>
                <option value="divorced">Divorced</option>
                <option value="widowed">Widowed</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-section mt-8">
          <h3 class="section-title">Contact & Professional</h3>
          
          <div class="grid-2">
            <div class="form-group">
              <label class="form-label">Phone Number</label>
              <input type="text" class="form-control" placeholder="e.g. 0712345678" v-model="form.phone">
            </div>

            <div class="form-group">
              <label class="form-label">Church Position</label>
              <input type="text" class="form-control" placeholder="e.g. Choir Member" v-model="form.position">
            </div>
          </div>

          <div class="grid-2">
            <div class="form-group">
              <label class="form-label">Employment Status</label>
              <select class="form-control" v-model="form.employment_status">
                <option value="">Select Status</option>
                <option value="employed">Employed</option>
                <option value="unemployed">Unemployed</option>
                <option value="student">Student</option>
                <option value="retired">Retired</option>
                <option value="self_employed">Self Employed</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Employment Notes</label>
              <input type="text" class="form-control" placeholder="e.g. Software Engineer at Tech Co" v-model="form.employment_notes">
            </div>
          </div>
        </div>

        <div class="form-actions mt-8 flex justify-end gap-4">
          <router-link to="/members" class="btn btn-secondary">Cancel</router-link>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            <span v-if="loading">Saving...</span>
            <span v-else>Register Member</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import MemberService from '../services/MemberService';

const router = useRouter();
const loading = ref(false);

const form = ref({
  first_name: '',
  last_name: '',
  middle_name: '',
  dob: '',
  gender: '',
  marital_status: '',
  phone: '',
  position: '',
  employment_status: '',
  employment_notes: ''
});

const submitForm = async () => {
  loading.value = true;
  try {
    // Clean up empty strings to nulls for the backend
    const payload = Object.fromEntries(
      Object.entries(form.value).map(([k, v]) => [k, v === '' ? null : v])
    );
    
    await MemberService.createMember(payload);
    router.push('/members');
  } catch (error) {
    console.error('Failed to create member:', error);
    // TODO: show a nice toast notification here
    alert('Failed to save member. Please check the inputs.');
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
