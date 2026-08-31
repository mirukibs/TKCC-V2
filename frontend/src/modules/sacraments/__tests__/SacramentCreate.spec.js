import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import SacramentCreate from '../views/SacramentCreate.vue';
import SacramentService from '../services/SacramentService';
import MemberService from '@/modules/members/services/MemberService';
import { createRouter, createWebHistory } from 'vue-router';

vi.mock('../services/SacramentService');
vi.mock('@/modules/members/services/MemberService');

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/sacraments', name: 'SacramentsList', component: { template: '<div>Sacraments</div>' } },
    { path: '/sacraments/create', component: SacramentCreate }
  ],
});

describe('SacramentCreate.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('loads members for the dropdown', async () => {
    MemberService.getMembers.mockResolvedValue({ data: [{ id: 1, name: 'John Doe' }] });
    const wrapper = mount(SacramentCreate, {
      global: { plugins: [router] }
    });
    await flushPromises();
    expect(wrapper.find('select').text()).toContain('John Doe');
  });

  it('creates a new sacrament on submit', async () => {
    MemberService.getMembers.mockResolvedValue({ data: [{ id: 1, name: 'John Doe' }] });
    SacramentService.create.mockResolvedValue({});
    router.push('/sacraments/create');
    await router.isReady();

    const wrapper = mount(SacramentCreate, {
      global: { plugins: [router] }
    });
    await flushPromises();

    // Select a member
    const select = wrapper.find('select');
    await select.setValue('1');

    await wrapper.find('form').trigger('submit.prevent');
    await flushPromises();

    expect(SacramentService.create).toHaveBeenCalled();
    expect(router.currentRoute.value.path).toBe('/sacraments');
  });

  it('shows error if member is not selected on submit', async () => {
    MemberService.getMembers.mockResolvedValue({ data: [{ id: 1, name: 'John Doe' }] });
    const wrapper = mount(SacramentCreate, {
      global: { plugins: [router] }
    });
    await flushPromises();

    await wrapper.find('form').trigger('submit.prevent');
    await flushPromises();

    expect(wrapper.text()).toContain('Please select a member.');
    expect(SacramentService.create).not.toHaveBeenCalled();
  });

  it('handles member fetch error gracefully', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    MemberService.getMembers.mockRejectedValue(new Error('Network error'));
    
    const wrapper = mount(SacramentCreate, {
      global: { plugins: [router] }
    });
    await flushPromises();
    
    expect(console.error).toHaveBeenCalled();
    expect(wrapper.text()).toContain('Failed to load members list for the dropdown.');
  });

  it('handles creation error gracefully', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    MemberService.getMembers.mockResolvedValue({ data: [{ id: 1, name: 'John Doe' }] });
    SacramentService.create.mockRejectedValue({ response: { data: { message: 'Custom error message' } } });
    
    const wrapper = mount(SacramentCreate, {
      global: { plugins: [router] }
    });
    await flushPromises();
    
    await wrapper.find('select').setValue('1');
    await wrapper.find('form').trigger('submit.prevent');
    await flushPromises();

    expect(console.error).toHaveBeenCalled();
    expect(wrapper.text()).toContain('Custom error message');
  });
});
