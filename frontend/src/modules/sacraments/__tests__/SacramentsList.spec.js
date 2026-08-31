import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import SacramentsList from '../views/SacramentsList.vue';
import SacramentService from '../services/SacramentService';
import { createRouter, createWebHistory } from 'vue-router';

// Mock the service
vi.mock('../services/SacramentService');

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: SacramentsList },
    { path: '/sacraments', component: SacramentsList },
    { path: '/sacraments/create', component: SacramentsList },
    { path: '/sacraments/:id', component: SacramentsList },
    { path: '/sacraments/:id/edit', component: SacramentsList }
  ],
});

describe('SacramentsList.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('renders a list of sacraments', async () => {
    const mockSacraments = [
      { id: 1, member_id: 1, baptism_status: 'baptized', confirmation_status: 'confirmed', marriage_status: 'single', member: { name: 'John Doe' } },
      { id: 2, member_id: 2, baptism_status: 'not_baptized', confirmation_status: 'not_confirmed', marriage_status: 'single', member: { name: 'Jane Doe' } }
    ];
    SacramentService.getAll.mockResolvedValue({ data: mockSacraments });

    const wrapper = mount(SacramentsList, {
      global: { plugins: [router] }
    });

    expect(wrapper.text()).toContain('Loading sacraments...');
    
    await flushPromises();

    expect(wrapper.text()).not.toContain('Loading sacraments...');
    expect(wrapper.text()).toContain('John Doe');
    expect(wrapper.text()).toContain('Jane Doe');
    expect(wrapper.text()).toContain('Baptized');
    expect(wrapper.text()).toContain('Confirmed');
    expect(wrapper.text()).toContain('Single');
    expect(wrapper.findAll('tbody tr')).toHaveLength(2);
  });

  it('handles search debounce', async () => {
    vi.useFakeTimers();
    SacramentService.getAll.mockResolvedValue({ data: [] });

    const wrapper = mount(SacramentsList, {
      global: { plugins: [router] }
    });

    await flushPromises();

    // Trigger search
    const searchInput = wrapper.find('input[type="text"]');
    await searchInput.setValue('John');
    
    // Fast forward debounce timer
    vi.advanceTimersByTime(300);
    await flushPromises();

    expect(SacramentService.getAll).toHaveBeenCalledWith({ search: 'John' });

    vi.useRealTimers();
  });

  it('handles API errors gracefully', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {});
    SacramentService.getAll.mockRejectedValue(new Error('API Error'));

    const wrapper = mount(SacramentsList, {
      global: { plugins: [router] }
    });

    await flushPromises();
    expect(wrapper.text()).toContain('No sacraments found.');
    expect(console.error).toHaveBeenCalled();
  });

  it('handles null member name gracefully', async () => {
    const mockSacraments = [
      { id: 1, member_id: 1, baptism_status: 'baptized', confirmation_status: 'confirmed', marriage_status: 'single', member: null }
    ];
    SacramentService.getAll.mockResolvedValue({ data: mockSacraments });

    const wrapper = mount(SacramentsList, {
      global: { plugins: [router] }
    });

    await flushPromises();
    expect(wrapper.text()).toContain('Unknown');
    expect(wrapper.text()).toContain('??');
  });
});
