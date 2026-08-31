import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import ZoneCreate from '../views/ZoneCreate.vue';
import ZoneService from '../services/ZoneService';
import { createRouter, createWebHistory } from 'vue-router';

vi.mock('../services/ZoneService');

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/zones', name: 'ZonesList', component: { template: '<div>Zones</div>' } },
    { path: '/zones/create', component: ZoneCreate }
  ],
});

describe('ZoneCreate.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('creates a new zone on submit', async () => {
    ZoneService.create.mockResolvedValue({});
    router.push('/zones/create');
    await router.isReady();

    const wrapper = mount(ZoneCreate, {
      global: { plugins: [router] }
    });
    
    const input = wrapper.find('input[type="text"]');
    await input.setValue('New Zone');

    await wrapper.find('form').trigger('submit.prevent');
    await flushPromises();

    expect(ZoneService.create).toHaveBeenCalledWith({ name: 'New Zone' });
    expect(router.currentRoute.value.path).toBe('/zones');
  });

  it('handles creation failure', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    vi.spyOn(window, 'alert').mockImplementation(() => {});
    
    ZoneService.create.mockRejectedValue(new Error('API Error'));
    router.push('/zones/create');
    await router.isReady();

    const wrapper = mount(ZoneCreate, {
      global: { plugins: [router] }
    });
    
    await wrapper.find('input[type="text"]').setValue('Bad Zone');
    await wrapper.find('form').trigger('submit.prevent');
    await flushPromises();

    expect(console.error).toHaveBeenCalled();
    expect(window.alert).toHaveBeenCalledWith('Failed to create zone. Check console for details.');
  });
});
