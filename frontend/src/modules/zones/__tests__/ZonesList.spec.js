import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import ZonesList from '../views/ZonesList.vue';
import ZoneService from '../services/ZoneService';
import { createRouter, createWebHistory } from 'vue-router';

vi.mock('../services/ZoneService');

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/zones', component: ZonesList },
    { path: '/zones/create', component: ZonesList },
    { path: '/zones/:id', component: ZonesList },
    { path: '/zones/:id/edit', component: ZonesList }
  ],
});

describe('ZonesList.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('renders a list of zones', async () => {
    const mockZones = [
      { id: 1, name: 'Zone A', description: 'Desc A' },
      { id: 2, name: 'Zone B', description: 'Desc B' }
    ];
    ZoneService.getAll.mockResolvedValue(mockZones);

    const wrapper = mount(ZonesList, {
      global: { plugins: [router] }
    });

    expect(wrapper.text()).toContain('Loading zones...');
    
    await flushPromises();

    expect(wrapper.text()).not.toContain('Loading zones...');
    expect(wrapper.text()).toContain('Zone A');
    expect(wrapper.text()).toContain('Zone B');
    expect(wrapper.findAll('tbody tr')).toHaveLength(2);
  });

  it('filters zones by search query with debounce', async () => {
    vi.useFakeTimers();
    const mockZones = [
      { id: 1, name: 'Zone A', description: 'Desc A' },
      { id: 2, name: 'Zone B', description: 'Desc B' }
    ];
    ZoneService.getAll.mockResolvedValue(mockZones);

    const wrapper = mount(ZonesList, {
      global: { plugins: [router] }
    });

    await flushPromises();

    // Trigger search
    const searchInput = wrapper.find('input[type="text"]');
    await searchInput.setValue('Zone A');
    
    // Fast forward debounce timer
    vi.advanceTimersByTime(300);
    await flushPromises();

    expect(wrapper.text()).toContain('Zone A');
    expect(wrapper.text()).not.toContain('Zone B');

    vi.useRealTimers();
  });

  it('handles API errors gracefully', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    ZoneService.getAll.mockRejectedValue(new Error('API Error'));

    const wrapper = mount(ZonesList, {
      global: { plugins: [router] }
    });

    await flushPromises();
    expect(wrapper.text()).toContain('No zones found.');
    expect(console.error).toHaveBeenCalled();
  });
});
