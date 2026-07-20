import { describe, it, expect, vi } from 'vitest'
import { mount, flushPromises } from '@vue/test-utils'
import HouseholdDetail from '../views/HouseholdDetail.vue'
import HouseholdService from '../services/HouseholdService'
import MemberService from '../../members/services/MemberService'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: { template: '<div></div>' } },
    { path: '/households/:id', name: 'HouseholdDetail', component: { template: '<div></div>' } }
  ]
})

// Mock the route param
router.currentRoute.value.params = { id: '1' }

describe('HouseholdDetail.vue', () => {
  it('renders household details when leader is assigned', async () => {
    vi.spyOn(HouseholdService, 'getHousehold').mockResolvedValue({
      data: {
        id: 1,
        name: 'The Harrison Family',
        ownership: 'owned',
        community_id: 2,
        leader_id: 1
      }
    })
    
    vi.spyOn(MemberService, 'getMember').mockResolvedValue({
      data: {
        id: 1,
        first_name: 'George',
        last_name: 'Harrison',
        full_name: 'George Harrison',
        phone: '123-456-7890'
      }
    })

    const wrapper = mount(HouseholdDetail, {
      global: { plugins: [router] }
    })
    await flushPromises()

    expect(wrapper.text()).toContain('The Harrison Family')
    expect(wrapper.text()).toContain('2')
    expect(wrapper.text()).toContain('George Harrison')
    expect(wrapper.text()).toContain('GH') // Initials
    expect(wrapper.find('.badge').classes()).toContain('badge-success')
  })

  it('renders empty state when no leader is assigned', async () => {
    vi.spyOn(HouseholdService, 'getHousehold').mockResolvedValue({
      data: {
        id: 1,
        name: 'The Lonely Family',
        ownership: 'rented',
        community_id: 3,
        leader_id: null
      }
    })

    const wrapper = mount(HouseholdDetail, {
      global: { plugins: [router] }
    })
    await flushPromises()

    expect(wrapper.text()).toContain('The Lonely Family')
    expect(wrapper.text()).toContain('Rented')
    expect(wrapper.text()).toContain('No leader assigned to this household')
  })

  it('renders not found state and handles go back button', async () => {
    vi.spyOn(HouseholdService, 'getHousehold').mockRejectedValueOnce(new Error('Not found'))
    const pushSpy = vi.spyOn(router, 'push')

    const wrapper = mount(HouseholdDetail, {
      global: { plugins: [router] }
    })
    await flushPromises()

    expect(wrapper.text()).toContain('Household not found.')
    
    await wrapper.find('button.btn-secondary').trigger('click')
    expect(pushSpy).toHaveBeenCalledWith('/households')
  })

  it('handles error when fetching household details fails', async () => {
    const consoleSpy = vi.spyOn(console, 'error').mockImplementation(() => {})
    vi.spyOn(HouseholdService, 'getHousehold').mockRejectedValueOnce(new Error('Network error'))

    mount(HouseholdDetail, {
      global: { plugins: [router] }
    })
    await flushPromises()

    expect(consoleSpy).toHaveBeenCalledWith('Failed to fetch household details:', expect.any(Error))
    consoleSpy.mockRestore()
  })

  it('handles error when fetching leader details fails', async () => {
    const consoleSpy = vi.spyOn(console, 'error').mockImplementation(() => {})
    vi.spyOn(HouseholdService, 'getHousehold').mockResolvedValue({
      data: { id: 1, name: 'Family', leader_id: 2 }
    })
    vi.spyOn(MemberService, 'getMember').mockRejectedValueOnce(new Error('Leader not found'))

    mount(HouseholdDetail, {
      global: { plugins: [router] }
    })
    await flushPromises()

    expect(consoleSpy).toHaveBeenCalledWith('Failed to fetch leader details:', expect.any(Error))
    consoleSpy.mockRestore()
  })
})
