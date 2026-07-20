import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount, flushPromises } from '@vue/test-utils'
import HouseholdCreate from '../views/HouseholdCreate.vue'
import HouseholdService from '../services/HouseholdService'
import MemberService from '../../members/services/MemberService'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: { template: '<div></div>' } },
    { path: '/households', name: 'HouseholdsList', component: { template: '<div></div>' } }
  ]
})

describe('HouseholdCreate.vue', () => {
  beforeEach(() => {
    vi.spyOn(MemberService, 'getMembers').mockResolvedValue({
      data: [{ id: 1, first_name: 'John', last_name: 'Doe' }]
    })
  })

  it('renders form inputs', async () => {
    const wrapper = mount(HouseholdCreate, {
      global: { plugins: [router] }
    })
    
    await flushPromises()

    expect(wrapper.find('input#name').exists()).toBe(true)
    expect(wrapper.find('input#community_id').exists()).toBe(true)
    expect(wrapper.find('select#leader_id').exists()).toBe(true)
    expect(wrapper.find('select#ownership').exists()).toBe(true)
    
    // Check if leader options loaded
    const leaderOptions = wrapper.findAll('select#leader_id option')
    expect(leaderOptions.length).toBeGreaterThan(1) // Placeholder + John Doe
  })

  it('submits the form data to the service', async () => {
    const createSpy = vi.spyOn(HouseholdService, 'createHousehold').mockResolvedValue({})
    
    const wrapper = mount(HouseholdCreate, {
      global: { plugins: [router] }
    })
    await flushPromises()

    await wrapper.find('input#name').setValue('The Adams Family')
    await wrapper.find('input#community_id').setValue('1')
    await wrapper.find('select#leader_id').setValue('1')
    await wrapper.find('select#ownership').setValue('owned')

    await wrapper.find('form').trigger('submit.prevent')
    
    expect(createSpy).toHaveBeenCalledWith({
      name: 'The Adams Family',
      community_id: 1,
      leader_id: 1,
      ownership: 'owned'
    })
  })

  it('handles error when fetching members fails', async () => {
    const consoleSpy = vi.spyOn(console, 'error').mockImplementation(() => {})
    vi.spyOn(MemberService, 'getMembers').mockRejectedValueOnce(new Error('Network error'))

    mount(HouseholdCreate, {
      global: { plugins: [router] }
    })
    
    await flushPromises()
    
    expect(consoleSpy).toHaveBeenCalledWith('Failed to fetch members for dropdown:', expect.any(Error))
    consoleSpy.mockRestore()
  })

  it('sets leader_id to null when submitting without a leader', async () => {
    const createSpy = vi.spyOn(HouseholdService, 'createHousehold').mockResolvedValue({})

    const wrapper = mount(HouseholdCreate, {
      global: { plugins: [router] }
    })
    await flushPromises()

    await wrapper.find('input#name').setValue('The Adams Family')
    await wrapper.find('input#community_id').setValue('1')
    await wrapper.find('select#leader_id').setValue('')

    await wrapper.find('form').trigger('submit.prevent')

    expect(createSpy).toHaveBeenCalledWith(expect.objectContaining({
      leader_id: null
    }))
  })

  it('handles errors when creating a household fails', async () => {
    const consoleSpy = vi.spyOn(console, 'error').mockImplementation(() => {})
    global.alert = vi.fn()
    vi.spyOn(HouseholdService, 'createHousehold').mockRejectedValueOnce(new Error('Server error'))

    const wrapper = mount(HouseholdCreate, {
      global: { plugins: [router] }
    })
    await flushPromises()

    await wrapper.find('input#name').setValue('The Adams Family')
    await wrapper.find('input#community_id').setValue('1')
    await wrapper.find('form').trigger('submit.prevent')

    await flushPromises()

    expect(consoleSpy).toHaveBeenCalledWith('Failed to create household:', expect.any(Error))
    expect(global.alert).toHaveBeenCalledWith('Failed to create household. Check console for details.')
    
    consoleSpy.mockRestore()
  })
})
