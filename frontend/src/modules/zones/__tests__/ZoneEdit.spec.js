import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import ZoneEdit from '../views/ZoneEdit.vue';
import ZoneService from '../services/ZoneService';
import { createRouter, createWebHistory } from 'vue-router';

vi.mock('../services/ZoneService');

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/zones', name: 'ZonesList', component: { template: '<div>Zones</div>' } },
    { path: '/zones/:id/edit', component: ZoneEdit }
  ],
});

describe('ZoneEdit.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('loads zone data on mount', async () => {
    ZoneService.getById.mockResolvedValue({ id: 1, name: 'Old Zone', description: 'Old Description' });
    router.push('/zones/1/edit');
    await router.isReady();

    const wrapper = mount(ZoneEdit, {
      global: { plugins: [router] }
    });
    
    expect(wrapper.text()).toContain('Loading zone data...');
    await flushPromises();
    
    expect(wrapper.text()).not.toContain('Loading zone data...');
    expect(ZoneService.getById).toHaveBeenCalledWith('1');
    const input = wrapper.find('input[type="text"]');
    expect(input.element.value).toBe('Old Zone');
  });

  it('updates zone on submit', async () => {
    ZoneService.getById.mockResolvedValue({ id: 1, name: 'Old Zone', description: 'Old Description' });
    ZoneService.update.mockResolvedValue({});
    router.push('/zones/1/edit');
    await router.isReady();

    const wrapper = mount(ZoneEdit, {
      global: { plugins: [router] }
    });
    await flushPromises();

    await wrapper.find('form').trigger('submit.prevent');
    await flushPromises();

    expect(ZoneService.update).toHaveBeenCalled();
    expect(router.currentRoute.value.path).toBe('/zones');
  });

  it('handles fetch error gracefully', async () => {
    vi.spyOn(window, 'alert').mockImplementation(() => {});
    vi.spyOn(console, 'error').mockImplementation(() => {});
    ZoneService.getById.mockRejectedValue(new Error('API error'));
    router.push('/zones/1/edit');
    await router.isReady();

    const wrapper = mount(ZoneEdit, {
      global: { plugins: [router] }
    });
    
    await flushPromises();

    expect(console.error).toHaveBeenCalled();
    expect(window.alert).toHaveBeenCalledWith('Failed to load zone data.');
    expect(router.currentRoute.value.path).toBe('/zones');
  });

  it('handles update error gracefully', async () => {
    vi.spyOn(window, 'alert').mockImplementation(() => {});
    vi.spyOn(console, 'error').mockImplementation(() => {});
    ZoneService.getById.mockResolvedValue({ id: 1, name: 'Old Zone', description: 'Old Description' });
    ZoneService.update.mockRejectedValue(new Error('Update error'));
    
    router.push('/zones/1/edit');
    await router.isReady();

    const wrapper = mount(ZoneEdit, {
      global: { plugins: [router] }
    });
    
    await flushPromises();
    await wrapper.find('form').trigger('submit.prevent');
    await flushPromises();

    expect(console.error).toHaveBeenCalled();
    expect(window.alert).toHaveBeenCalledWith('Failed to update zone. Check console for details.');
  });
});
