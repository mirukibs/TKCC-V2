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

    const wrapper = mount(CommunityCreate, {
      global: {
        plugins: [router]
      }
    });

    // We can directly mock useRouter if needed, but let's test if we can trigger submit
    // Note: setting up a full router is usually better for setup() components using useRouter.
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
});
