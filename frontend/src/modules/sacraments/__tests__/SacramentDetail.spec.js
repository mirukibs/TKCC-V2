import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import SacramentDetail from '../views/SacramentDetail.vue';
import SacramentService from '../services/SacramentService';
import { createRouter, createWebHistory } from 'vue-router';

vi.mock('../services/SacramentService');

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/sacraments', name: 'SacramentsList', component: { template: '<div>Sacraments</div>' } },
    { path: '/sacraments/:id', component: SacramentDetail }
  ],
});

describe('SacramentDetail.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('loads and displays sacrament details', async () => {
    SacramentService.getById.mockResolvedValue({ 
      data: { 
        id: 1, 
        member_id: 1, 
        baptism_status: 'baptized',
        baptism_date: '2020-01-01',
        baptism_place: 'St. Peter',
        confirmation_status: 'not_confirmed',
        marriage_status: 'single',
        member: { name: 'John Doe' } 
      } 
    });
    router.push('/sacraments/1');
    await router.isReady();

    const wrapper = mount(SacramentDetail, {
      global: { plugins: [router] }
    });
    
    expect(wrapper.text()).toContain('Loading details...');
    await flushPromises();
    
    expect(wrapper.text()).not.toContain('Loading details...');
    expect(wrapper.text()).toContain('John Doe');
    expect(wrapper.text()).toContain('Baptized');
    expect(wrapper.text()).toContain('St. Peter');
  });

  it('handles delete action', async () => {
    window.confirm = vi.fn().mockReturnValue(true);
    SacramentService.getById.mockResolvedValue({ 
      data: { id: 1, member_id: 1, baptism_status: 'baptized' } 
    });
    SacramentService.delete.mockResolvedValue({});
    router.push('/sacraments/1');
    await router.isReady();

    const wrapper = mount(SacramentDetail, {
      global: { plugins: [router] }
    });
    await flushPromises();

    await wrapper.find('.btn-danger').trigger('click');
    await flushPromises();

    expect(window.confirm).toHaveBeenCalled();
    expect(SacramentService.delete).toHaveBeenCalledWith(1);
    expect(router.currentRoute.value.path).toBe('/sacraments');
  });

  it('handles fetch error gracefully', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    SacramentService.getById.mockRejectedValue(new Error('API error'));
    router.push('/sacraments/1');
    await router.isReady();

    const wrapper = mount(SacramentDetail, {
      global: { plugins: [router] }
    });
    
    await flushPromises();

    expect(console.error).toHaveBeenCalled();
    expect(wrapper.text()).toContain('Failed to load sacrament details. It might have been deleted.');
  });

  it('handles delete error gracefully', async () => {
    window.confirm = vi.fn().mockReturnValue(true);
    vi.spyOn(window, 'alert').mockImplementation(() => {});
    vi.spyOn(console, 'error').mockImplementation(() => {});
    
    SacramentService.getById.mockResolvedValue({ 
      data: { id: 1, member_id: 1, baptism_status: 'baptized' } 
    });
    SacramentService.delete.mockRejectedValue(new Error('Delete error'));
    
    router.push('/sacraments/1');
    await router.isReady();

    const wrapper = mount(SacramentDetail, {
      global: { plugins: [router] }
    });
    await flushPromises();

    await wrapper.find('.btn-danger').trigger('click');
    await flushPromises();

    expect(console.error).toHaveBeenCalled();
    expect(window.alert).toHaveBeenCalledWith('Failed to delete sacrament record.');
  });
});
