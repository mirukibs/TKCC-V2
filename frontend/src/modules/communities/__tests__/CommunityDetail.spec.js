import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import CommunityDetail from '../views/CommunityDetail.vue';
import CommunityService from '../services/CommunityService';
import { createRouter, createWebHistory } from 'vue-router';

vi.mock('../services/CommunityService');
import ZoneService from '../../zones/services/ZoneService';

const router = createRouter({
  history: createWebHistory(),
  routes: [{ path: '/communities/:id', component: CommunityDetail }],
});

describe('CommunityDetail.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('loads community details', async () => {
    const mockData = { id: 1, name: 'Detail View', zone_id: 3 };
    CommunityService.getById.mockResolvedValue(mockData);
    vi.spyOn(ZoneService, 'getById').mockResolvedValue({ data: { id: 3, name: 'Test Zone 3' }, name: 'Test Zone 3' });

    const wrapper = mount(CommunityDetail, {
      props: { id: '1' },
      global: { plugins: [router] }
    });

    expect(wrapper.text()).toContain('Loading community details...');
    
    await flushPromises();

    expect(wrapper.text()).toContain('Community Information');
    expect(wrapper.text()).toContain('Detail View');
    expect(wrapper.text()).toContain('Test Zone 3');
  });
});
