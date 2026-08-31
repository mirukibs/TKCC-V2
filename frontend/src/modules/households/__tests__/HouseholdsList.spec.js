import { describe, it, expect, vi } from 'vitest'
import { mount, flushPromises } from '@vue/test-utils'
import HouseholdsList from '../views/HouseholdsList.vue'
import HouseholdService from '../services/HouseholdService'
import CommunityService from '../../communities/services/CommunityService'
import MemberService from '../../members/services/MemberService'
import { createRouter, createWebHistory } from 'vue-router'

vi.mock('../../communities/services/CommunityService', () => ({
  default: {
    getAll: vi.fn()
  }
}))

vi.mock('../../members/services/MemberService', () => ({
  default: {
    getMembers: vi.fn()
  }
}))

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: { template: '<div></div>' } },
    { path: '/households/create', name: 'HouseholdCreate', component: { template: '<div></div>' } },
    { path: '/households/:id', name: 'HouseholdDetail', component: { template: '<div></div>' } }
  ]
})

describe('HouseholdsList.vue', () => {
  it('renders a list of households from the service', async () => {
    const mockHouseholds = [
      { id: 1, name: 'The Doe Family', ownership: 'owned', community_id: 1, leader_id: 2 },
      { id: 2, name: 'The Smith Family', ownership: 'rented', community_id: 2, leader_id: null }
    ]

    vi.spyOn(HouseholdService, 'getHouseholds').mockResolvedValue({ data: mockHouseholds })
    CommunityService.getAll.mockResolvedValue([{ id: 1, name: 'Community A' }, { id: 2, name: 'Community B' }])
    MemberService.getMembers.mockResolvedValue([{ id: 2, first_name: 'Jane', last_name: 'Leader' }])

    const wrapper = mount(HouseholdsList, {
      global: {
        plugins: [router]
      }
    })

    // Wait for the API call to resolve
    await flushPromises()

    // Expect the table to have rows for the households
    const rows = wrapper.findAll('tbody tr')
    expect(rows.length).toBe(2)

    expect(rows[0].text()).toContain('The Doe Family')
    expect(rows[0].text()).toContain('Community A') 
    expect(rows[0].text()).toContain('Jane Leader') 
    
    expect(rows[1].text()).toContain('The Smith Family')
    expect(rows[1].text()).toContain('Community B')
    expect(rows[1].text()).toContain('N/A') 
    
    // Check ownership badges
    const badges = wrapper.findAll('.badge')
    expect(badges[0].classes()).toContain('badge-success')
    expect(badges[1].classes()).toContain('badge-warning')
  })

  it('handles error when fetching households fails', async () => {
    const consoleSpy = vi.spyOn(console, 'error').mockImplementation(() => {})
    vi.spyOn(HouseholdService, 'getHouseholds').mockRejectedValueOnce(new Error('Network error'))

    const wrapper = mount(HouseholdsList, {
      global: { plugins: [router] }
    })

    await flushPromises()

    expect(consoleSpy).toHaveBeenCalledWith('Failed to fetch households:', expect.any(Error))
    expect(wrapper.text()).toContain('No households found.')
    consoleSpy.mockRestore()
  })

  it('triggers a fetch with debounce when searching', async () => {
    vi.useFakeTimers()
    const fetchSpy = vi.spyOn(HouseholdService, 'getHouseholds').mockResolvedValue({ data: [] })
    CommunityService.getAll.mockResolvedValue([])
    MemberService.getMembers.mockResolvedValue([])
    
    const wrapper = mount(HouseholdsList, {
      global: { plugins: [router] }
    })

    await flushPromises()
    fetchSpy.mockClear() // clear initial fetch on mount

    // Update search query
    await wrapper.find('input[type="text"]').setValue('Adam')
    
    // Should not fetch immediately due to debounce
    expect(fetchSpy).not.toHaveBeenCalled()

    // Fast-forward time
    vi.advanceTimersByTime(300)
    
    expect(fetchSpy).toHaveBeenCalledTimes(1)
    expect(fetchSpy).toHaveBeenCalledWith(expect.objectContaining({ search: 'Adam' }))
    
    vi.useRealTimers()
  })

  it('triggers a fetch with debounce when filtering ownership', async () => {
    vi.useFakeTimers()
    const fetchSpy = vi.spyOn(HouseholdService, 'getHouseholds').mockResolvedValue({ data: [] })
    CommunityService.getAll.mockResolvedValue([])
    MemberService.getMembers.mockResolvedValue([])
    
    const wrapper = mount(HouseholdsList, {
      global: { plugins: [router] }
    })

    await flushPromises()
    fetchSpy.mockClear() // clear initial fetch on mount

    // Update filter
    const select = wrapper.findAll('select')[0]
    await select.setValue('owned')
    
    vi.advanceTimersByTime(300)
    
    expect(fetchSpy).toHaveBeenCalledTimes(1)
    expect(fetchSpy).toHaveBeenCalledWith(expect.objectContaining({ ownership: 'owned' }))
    
    vi.useRealTimers()
  })

  it('returns correct fallback badge class for unknown ownership', async () => {
    vi.spyOn(HouseholdService, 'getHouseholds').mockResolvedValue({ 
      data: [{ id: 1, name: 'Family', ownership: 'unknown_ownership', community_id: 1, leader_id: null }] 
    })
    CommunityService.getAll.mockResolvedValue([])
    MemberService.getMembers.mockResolvedValue([])

    const wrapper = mount(HouseholdsList, {
      global: { plugins: [router] }
    })

    await flushPromises()

    const badge = wrapper.find('.badge')
    expect(badge.classes()).toContain('badge-warning')
  })
})
