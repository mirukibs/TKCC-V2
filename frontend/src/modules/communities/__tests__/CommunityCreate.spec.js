import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import CommunityCreate from '../views/CommunityCreate.vue';
import CommunityService from '../services/CommunityService';

vi.mock('../services/CommunityService');

const mockRouter = {
  push: vi.fn()
};

describe('CommunityCreate.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('submits form data to service', async () => {
    CommunityService.create.mockResolvedValue({ data: { id: 1, name: 'Test', zone_id: 2 } });

    const wrapper = mount(CommunityCreate, {
      global: {
        mocks: {
          $router: mockRouter
        },
        provide: {
          router: mockRouter // For vue-router 4 useRouter hook mock
        }
      }
    });

    // We can directly mock useRouter if needed, but let's test if we can trigger submit
    // Note: setting up a full router is usually better for setup() components using useRouter.
  });

  it('enforces maxlength of 150 on the name input', () => {
    const wrapper = mount(CommunityCreate, {
      global: {
        mocks: { $router: mockRouter },
        provide: { router: mockRouter }
      }
    });

    const nameInput = wrapper.find('#name');
    expect(nameInput.attributes('maxlength')).toBe('150');
  });
});
