import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import CommunitiesList from '../views/CommunitiesList.vue';
import CommunityService from '../services/CommunityService';
import { createRouter, createWebHistory } from 'vue-router';

// Mock the service
vi.mock('../services/CommunityService');
vi.mock('../../zones/services/ZoneService', () => ({
  default: {
    getAll: vi.fn().mockResolvedValue([{ id: 1, name: 'Zone A' }, { id: 2, name: 'Zone B' }])
  }
}));

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: CommunitiesList },
    { path: '/communities', component: CommunitiesList },
    { path: '/communities/create', component: CommunitiesList },
    { path: '/communities/:id', component: CommunitiesList },
    { path: '/communities/:id/edit', component: CommunitiesList }
  ],
});

describe('CommunitiesList.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('renders a list of communities', async () => {
    const mockCommunities = [
      { id: 1, name: 'Com A', zone_id: 1 },
      { id: 2, name: 'Com B', zone_id: 2 }
    ];
    CommunityService.getAll.mockResolvedValue(mockCommunities);

    const wrapper = mount(CommunitiesList, {
      global: { plugins: [router] }
    });

    expect(wrapper.text()).toContain('Loading communities...');
    
    await flushPromises();

    expect(wrapper.text()).not.toContain('Loading communities...');
    expect(wrapper.text()).toContain('Com A');
    expect(wrapper.text()).toContain('Com B');
    expect(wrapper.findAll('tbody tr')).toHaveLength(2);
  });
});
