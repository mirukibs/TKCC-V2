import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import CommunityEdit from '../views/CommunityEdit.vue';
import CommunityService from '../services/CommunityService';
import ZoneService from '../../zones/services/ZoneService';
import { createRouter, createWebHistory } from 'vue-router';

vi.mock('../services/CommunityService');
vi.mock('../../zones/services/ZoneService');

const router = createRouter({
  history: createWebHistory(),
  routes: [{ path: '/communities/:id/edit', component: CommunityEdit }],
});

describe('CommunityEdit.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('loads community and updates form', async () => {
    const mockData = { id: 1, name: 'Edit Me', zone_id: 3 };
    CommunityService.getById.mockResolvedValue(mockData);
    CommunityService.update.mockResolvedValue({ data: mockData });
    ZoneService.getAll.mockResolvedValue({ data: [{ id: 3, name: 'Zone 3' }] });

    const wrapper = mount(CommunityEdit, {
      props: { id: '1' },
      global: { plugins: [router] }
    });

    await flushPromises();

    // Check if input has the value
    const nameInput = wrapper.find('#name');
    expect(nameInput.element.value).toBe('Edit Me');

    // Trigger update
    await wrapper.find('form').trigger('submit.prevent');
    
    expect(CommunityService.update).toHaveBeenCalledWith('1', { name: 'Edit Me', zone_id: 3 });
  });

  it('enforces maxlength of 150 on the name input', async () => {
    CommunityService.getById.mockResolvedValue({ id: 1, name: 'Edit Me', zone_id: 3 });
    ZoneService.getAll.mockResolvedValue({ data: [{ id: 3, name: 'Zone 3' }] });
    const wrapper = mount(CommunityEdit, {
      props: { id: '1' },
      global: { plugins: [router] }
    });
    
    await flushPromises();
    const nameInput = wrapper.find('#name');
    expect(nameInput.attributes('maxlength')).toBe('150');
  });

  it('handles zone fetching error', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    ZoneService.getAll.mockRejectedValue(new Error('Network error'));
    CommunityService.getById.mockResolvedValue({ id: 1, name: 'Edit Me', zone_id: 3 });

    const wrapper = mount(CommunityEdit, {
      props: { id: '1' },
      global: { plugins: [router] }
    });

    await flushPromises();
    expect(console.error).toHaveBeenCalled();
  });

  it('handles community fetch error', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    ZoneService.getAll.mockResolvedValue({ data: [{ id: 3, name: 'Zone 3' }] });
    CommunityService.getById.mockRejectedValue(new Error('API error'));

    const wrapper = mount(CommunityEdit, {
      props: { id: '1' },
      global: { plugins: [router] }
    });
    
    await flushPromises();
    expect(console.error).toHaveBeenCalled();
    expect(wrapper.text()).toContain('Failed to load community details.');
  });

  it('handles update error', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    const mockData = { id: 1, name: 'Edit Me', zone_id: 3 };
    CommunityService.getById.mockResolvedValue(mockData);
    CommunityService.update.mockRejectedValue(new Error('API Error'));
    ZoneService.getAll.mockResolvedValue({ data: [{ id: 3, name: 'Zone 3' }] });

    const wrapper = mount(CommunityEdit, {
      props: { id: '1' },
      global: { plugins: [router] }
    });

    await flushPromises();
    await wrapper.find('form').trigger('submit.prevent');
    await flushPromises();

    expect(console.error).toHaveBeenCalled();
    expect(wrapper.text()).toContain('Failed to update community. Please check the inputs.');
  });
});
