import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import CommunityCreate from '../views/CommunityCreate.vue';
import CommunityService from '../services/CommunityService';
import ZoneService from '../../zones/services/ZoneService';
import { createRouter, createWebHistory } from 'vue-router';

vi.mock('../services/CommunityService');
vi.mock('../../zones/services/ZoneService');

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: CommunityCreate },
    { path: '/communities', component: CommunityCreate },
    { path: '/communities/create', component: CommunityCreate }
  ],
});

describe('CommunityCreate.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('submits form data to service', async () => {
    CommunityService.create.mockResolvedValue({ data: { id: 1, name: 'Test', zone_id: 2 } });
    ZoneService.getAll.mockResolvedValue({ data: [{ id: 2, name: 'Zone 2' }] });
    router.push('/communities/create');
    await router.isReady();

    const wrapper = mount(CommunityCreate, {
      global: {
        plugins: [router]
      }
    });

    await flushPromises();

    await wrapper.find('#name').setValue('Test Community');
    await wrapper.find('#zone_id').setValue('2');
    
    await wrapper.find('form').trigger('submit.prevent');
    await flushPromises();

    expect(CommunityService.create).toHaveBeenCalledWith({ name: 'Test Community', zone_id: 2 });
    expect(router.currentRoute.value.path).toBe('/communities');
  });

  it('enforces maxlength of 150 on the name input', async () => {
    ZoneService.getAll.mockResolvedValue({ data: [{ id: 2, name: 'Zone 2' }] });
    const wrapper = mount(CommunityCreate, {
      global: {
        plugins: [router]
      }
    });

    await flushPromises();
    const nameInput = wrapper.find('#name');
    expect(nameInput.attributes('maxlength')).toBe('150');
  });

  it('handles zone fetching error', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    ZoneService.getAll.mockRejectedValue(new Error('Network error'));

    const wrapper = mount(CommunityCreate, {
      global: { plugins: [router] }
    });

    await flushPromises();
    expect(console.error).toHaveBeenCalled();
  });

  it('handles creation error', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    ZoneService.getAll.mockResolvedValue({ data: [{ id: 2, name: 'Zone 2' }] });
    CommunityService.create.mockRejectedValue(new Error('API Error'));

    const wrapper = mount(CommunityCreate, {
      global: { plugins: [router] }
    });

    await flushPromises();
    
    await wrapper.find('#name').setValue('Test Community');
    await wrapper.find('#zone_id').setValue(2);
    await wrapper.find('form').trigger('submit.prevent');
    
    await flushPromises();

    expect(console.error).toHaveBeenCalled();
    expect(wrapper.text()).toContain('Failed to create community. Please check the inputs.');
  });
});
