import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import HouseholdEdit from '@/modules/households/views/HouseholdEdit.vue';
import HouseholdService from '@/modules/households/services/HouseholdService';
import CommunityService from '@/modules/communities/services/CommunityService';
import MemberService from '@/modules/members/services/MemberService';

const RouterLink = { template: '<a><slot></slot></a>' };

const mockRouter = {
    push: vi.fn()
};

vi.mock('vue-router', () => ({
    useRouter: () => mockRouter,
    useRoute: () => ({ params: { id: '1' } })
}));

vi.mock('@/modules/households/services/HouseholdService', () => ({
    default: {
        getHousehold: vi.fn(),
        updateHousehold: vi.fn()
    }
}));

vi.mock('@/modules/communities/services/CommunityService', () => ({
    default: {
        getAll: vi.fn()
    }
}));

vi.mock('@/modules/members/services/MemberService', () => ({
    default: {
        getMembers: vi.fn()
    }
}));

describe('HouseholdEdit.vue', () => {
    beforeEach(() => {
        vi.clearAllMocks();
        global.alert = vi.fn();
        
        HouseholdService.getHousehold.mockResolvedValue({
            data: {
                id: 1,
                name: 'The Doe Family',
                community_id: 1,
                leader_id: 2,
                ownership: 'owner'
            }
        });
        CommunityService.getAll.mockResolvedValue([
            { id: 1, name: 'Test Community' }
        ]);
        MemberService.getMembers.mockResolvedValue([
            { id: 2, first_name: 'Leader', last_name: 'Name' }
        ]);
    });

    it('renders the form and populates data', async () => {
        const wrapper = mount(HouseholdEdit, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();
        
        expect(wrapper.text()).toContain('Edit Household');
        
        const inputs = wrapper.findAll('input');
        const selects = wrapper.findAll('select');
        expect(inputs[0].element.value).toBe('The Doe Family'); // name
        expect(selects[0].element.value).toBe('1'); // community_id
        expect(selects[1].element.value).toBe('2'); // leader_id
        
        const ownershipSelect = selects[2];
        expect(ownershipSelect.element.value).toBe('owner'); // ownership
    });

    it('submits updated payload and redirects on success', async () => {
        HouseholdService.updateHousehold.mockResolvedValueOnce({ data: { id: 1 } });

        const wrapper = mount(HouseholdEdit, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();

        // Update name
        await wrapper.findAll('input')[0].setValue('The Updated Family');
        // Clear leader ID
        await wrapper.findAll('select')[1].setValue('');

        await wrapper.find('form').trigger('submit.prevent');
        await flushPromises();

        expect(HouseholdService.updateHousehold).toHaveBeenCalledTimes(1);

        const calledPayload = HouseholdService.updateHousehold.mock.calls[0][1];
        expect(calledPayload.name).toBe('The Updated Family');
        expect(calledPayload.leader_id).toBeNull(); // Empty string became null
        
        expect(mockRouter.push).toHaveBeenCalledWith('/households');
    });

    it('handles API errors on submit and shows alert', async () => {
        HouseholdService.updateHousehold.mockRejectedValueOnce(new Error('API Error'));

        const wrapper = mount(HouseholdEdit, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();

        await wrapper.find('form').trigger('submit.prevent');
        await flushPromises();

        expect(HouseholdService.updateHousehold).toHaveBeenCalledTimes(1);
        expect(mockRouter.push).not.toHaveBeenCalled();
        expect(global.alert).toHaveBeenCalledWith('Failed to update household. Please check the inputs.');
    });

    it('handles missing data and sets defaults when fetching', async () => {
        HouseholdService.getHousehold.mockResolvedValue({
            data: { id: 1, name: 'Minimal Family' } // other fields missing
        });

        const wrapper = mount(HouseholdEdit, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();

        const inputs = wrapper.findAll('input');
        const selects = wrapper.findAll('select');
        expect(inputs[0].element.value).toBe('Minimal Family');
        expect(selects[0].element.value).toBe(''); // community_id defaulted
        expect(selects[1].element.value).toBe(''); // leader_id defaulted
    });

    it('handles error when loading data on mount', async () => {
        const consoleSpy = vi.spyOn(console, 'error').mockImplementation(() => {});
        HouseholdService.getHousehold.mockRejectedValueOnce(new Error('Network error'));
        
        const wrapper = mount(HouseholdEdit, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();
        
        expect(HouseholdService.getHousehold).toHaveBeenCalledTimes(1);
        expect(consoleSpy).toHaveBeenCalledWith('Failed to load household', expect.any(Error));
        consoleSpy.mockRestore();
    });
});
