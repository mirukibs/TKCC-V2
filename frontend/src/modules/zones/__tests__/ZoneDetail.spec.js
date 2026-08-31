import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import ZoneDetail from '../views/ZoneDetail.vue';
import ZoneService from '../services/ZoneService';
import { createRouter, createWebHistory } from 'vue-router';

vi.mock('../services/ZoneService');

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/zones', name: 'ZonesList', component: { template: '<div>Zones</div>' } },
    { path: '/zones/:id', component: ZoneDetail }
  ],
});

describe('ZoneDetail.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('loads and displays zone details', async () => {
    ZoneService.getById.mockResolvedValue({ data: { id: 1, name: 'Main Zone' } });
    router.push('/zones/1');
    await router.isReady();

    const wrapper = mount(ZoneDetail, {
      global: { plugins: [router] }
    });
    
    expect(wrapper.text()).toContain('Loading zone details...');
    await flushPromises();
    
    expect(wrapper.text()).not.toContain('Loading zone details...');
    expect(wrapper.text()).toContain('Main Zone');
  });
});
