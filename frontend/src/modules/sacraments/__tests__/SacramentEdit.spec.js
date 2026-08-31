import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import SacramentEdit from '../views/SacramentEdit.vue';
import SacramentService from '../services/SacramentService';
import { createRouter, createWebHistory } from 'vue-router';

vi.mock('../services/SacramentService');

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/sacraments', name: 'SacramentsList', component: { template: '<div>Sacraments</div>' } },
    { path: '/sacraments/:id/edit', component: SacramentEdit }
  ],
});

describe('SacramentEdit.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('loads sacrament data on mount', async () => {
    SacramentService.getById.mockResolvedValue({ 
      data: { id: 1, member_id: 1, baptism_status: 'baptized', member: { name: 'John Doe' } } 
    });
    router.push('/sacraments/1/edit');
    await router.isReady();

    const wrapper = mount(SacramentEdit, {
      global: { plugins: [router] }
    });
    
    expect(wrapper.text()).toContain('Loading sacrament data...');
    await flushPromises();
    
    expect(wrapper.text()).not.toContain('Loading sacrament data...');
    expect(SacramentService.getById).toHaveBeenCalledWith('1');
    const select = wrapper.find('select');
    expect(select.text()).toContain('John Doe');
  });

  it('updates sacrament on submit', async () => {
    SacramentService.getById.mockResolvedValue({ 
      data: { id: 1, member_id: 1, baptism_status: 'not_baptized', member: { name: 'John Doe' } } 
    });
    SacramentService.update.mockResolvedValue({});
    router.push('/sacraments/1/edit');
    await router.isReady();

    const wrapper = mount(SacramentEdit, {
      global: { plugins: [router] }
    });
    await flushPromises();

    await wrapper.find('form').trigger('submit.prevent');
    await flushPromises();

    expect(SacramentService.update).toHaveBeenCalled();
    expect(router.currentRoute.value.path).toBe('/sacraments');
  });

  it('handles fetch error gracefully', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    SacramentService.getById.mockRejectedValue(new Error('API error'));
    router.push('/sacraments/1/edit');
    await router.isReady();

    const wrapper = mount(SacramentEdit, {
      global: { plugins: [router] }
    });
    
    await flushPromises();

    expect(console.error).toHaveBeenCalled();
    expect(wrapper.text()).toContain('Failed to load sacrament data.');
  });

  it('handles update error gracefully', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    SacramentService.getById.mockResolvedValue({ 
      data: { id: 1, member_id: 1, baptism_status: 'not_baptized', member: { name: 'John Doe' } } 
    });
    SacramentService.update.mockRejectedValue({ response: { data: { message: 'Custom update error' } } });
    
    router.push('/sacraments/1/edit');
    await router.isReady();

    const wrapper = mount(SacramentEdit, {
      global: { plugins: [router] }
    });
    
    await flushPromises();
    await wrapper.find('form').trigger('submit.prevent');
    await flushPromises();

    expect(console.error).toHaveBeenCalled();
    expect(wrapper.text()).toContain('Custom update error');
  });
});
